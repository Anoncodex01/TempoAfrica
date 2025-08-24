<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Models\AccommodationRoom;
use App\Models\Booking;
use App\Services\DPOPaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function bookRoom(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'accommodation_room_id' => 'nullable|integer|exists:accommodation_rooms,id',
            'pacs' => 'required|integer|min:1',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors(),
            ], 422);
        }

        $data = $validation->validated();

        $customer = $request->user()->load([
            'country',
            'province',
            'street',
        ]);

        $missingFields = [];

        if (empty($customer->country_id)) {
            $missingFields[] = 'country';
        }

        if (empty($customer->province_id)) {
            $missingFields[] = 'province';
        }

        if (empty($customer->email)) {
            $missingFields[] = 'email';
        }

        if (empty($customer->dob)) {
            $missingFields[] = 'date of birth';
        }

        if (! empty($missingFields)) {
            $missingText = implode(', ', $missingFields);

            return response()->json([
                'success' => false,
                'message' => 'Please complete your profile. Missing: '.$missingText.'.',
            ], 422);
        }

        $data['customer_id'] = $customer->id;
        $reference = $this->uniqueReference($data);
        $data['reference'] = $reference;

        $start = Carbon::parse($data['from_date'])->toDateString();
        $end = Carbon::parse($data['to_date'])->toDateString();

        $data['from_date'] = $start;
        $data['to_date'] = $end;

        $today = now()->startOfDay();

        if ($end < $start) {
            return response()->json(['success' => false, 'message' => 'Sorry, checkout date cannot be before check-in date.'], 422);
        }

        if ($start < $today) {
            return response()->json(['success' => false, 'message' => 'Sorry, you cannot book for past dates.'], 422);
        }

        if (Carbon::parse($start)->isSameDay(Carbon::parse($end))) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, same-day bookings are not allowed.',
            ], 422);
        }

        // Check if user already has an unpaid booking less than 15 minutes old
        $hasPendingBooking = Booking::where('customer_id', $customer->id)
            ->where('is_paid', 0)
            ->where('created_at', '>', now()->subMinutes(15))
            ->exists();

        if ($hasPendingBooking) {

            $pendingBooking = Booking::where('customer_id', $customer->id)
                ->where('is_paid', 0)
                ->where('created_at', '>', now()->subMinutes(15))
                ->first();

            $paymentPayload = app(DPOPaymentService::class)->preparePaymentPayload($pendingBooking, $customer);

            return response()->json([
                'success' => $paymentPayload['success'],
                'url' => $paymentPayload['url'],
                'message' => 'You have a pending booking. Please complete payment or wait 15 minutes for it to expire.',
                'booking' => $pendingBooking,
            ]);

        }

        $roomId = $data['accommodation_room_id'];
        $accommodationRoom = AccommodationRoom::find($roomId);

        if (! $accommodationRoom || ! $accommodationRoom->is_active || ! $accommodationRoom->is_visible || ! $accommodationRoom->is_available) {
            return response()->json(['success' => false, 'message' => 'Sorry, the room is not currently open for booking.'], 422);
        }

        $duration = Carbon::parse($start)->diffInDays(Carbon::parse($end));

        // Check for existing booking conflict (excluding expired unpaid ones)
        $conflict = Booking::where('accommodation_room_id', $roomId)
            ->where(function ($query) use ($start, $end) {
                $query->where('from_date', '<=', $end)
                    ->where('to_date', '>=', $start);
            })
            ->where(function ($query) {
                $query->where('is_paid', 1)
                    ->orWhere(function ($q) {
                        $q->where('is_paid', 0)
                            ->where('created_at', '>', now()->subMinutes(30));
                    });
            })
            ->when(! empty($data['id']), function ($query) use ($data) {
                $query->where('id', '!=', $data['id']);
            })
            ->exists();

        if ($conflict) {
            // Suggest next available slot
            $nextStart = Carbon::parse($end)->copy()->addDay();
            $maxCheckDate =  Carbon::parse($nextStart)->copy()->addDays(30); // 1-month window
            $nextAvailable = null;

            while ($nextStart->lte($maxCheckDate)) {
                $nextEnd = $nextStart->copy()->addDays($duration - 1);

                $overlap = Booking::where('accommodation_room_id', $roomId)
                    ->where(function ($query) use ($nextStart, $nextEnd) {
                        $query->where('from_date', '<=', $nextEnd)
                            ->where('to_date', '>=', $nextStart);
                    })
                    ->where(function ($query) {
                        $query->where('is_paid', 1)
                            ->orWhere(function ($q) {
                                $q->where('is_paid', 0)
                                    ->where('created_at', '>', now()->subMinutes(30));
                            });
                    })
                    ->exists();

                if (! $overlap) {
                    $nextAvailable = [
                        'from_date' => $nextStart->toDateString(),
                        'to_date' => $nextEnd->toDateString(),
                    ];
                    break;
                }

                $nextStart->addDay();
            }

            return response()->json([
                'success' => false,
                'message' => 'This room is already booked during the selected dates.',
                'suggested_alternative' => $nextAvailable ?: 'No free slot found within the next 30 days.',
            ], 409);
        }

        // Calculate cost
        $data['currency'] = $accommodationRoom->currency;
        $data['accommodation_id'] = $accommodationRoom->accommodation_id;
        $data['price'] = $accommodationRoom->price;
        $data['amount'] = $accommodationRoom->price * $duration;

        // Create or update the booking
        if (! empty($data['id'])) {
            $booking = Booking::findOrFail($data['id']);
            $booking->update($data);
            $message = 'Booking updated successfully';
        } else {
            $booking = Booking::create($data);
            $message = 'Booking created successfully';
        }

        $paymentPayload = (new DPOPaymentService)->preparePaymentPayload($booking, $customer);

        // If booking was created but payment failed, still return success with booking info
        if (!$paymentPayload['success'] && $booking) {
            return response()->json([
                'success' => false,
                'url' => null,
                'message' => 'Booking created but payment setup failed. Please try again.',
                'booking' => $booking,
            ]);
        }

        return response()->json([
            'success' => $paymentPayload['success'],
            'url' => $paymentPayload['url'],
            'message' => $message,
            'booking' => $booking,
        ]);
    }
    
    
    
    public function updateBooking(Request $request)
{

    
     $validation = Validator::make($request->all(), [
             'id' => 'required|integer|exists:bookings,id',
            'type' => 'required|in:update,delete',
            'from_date' => 'sometimes|nullable|date|after_or_equal:today',
            'to_date' => 'sometimes|nullable|date|after_or_equal:from_date',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors(),
            ], 422);
        }
        

    $booking = Booking::findOrFail($request->id);

    if ($booking->is_paid) {
        return response()->json([
            'success' => false,
            'message' => 'This booking has already been paid and cannot be modified.',
        ], 403);
    }

    if ($request->type === 'update') {
        if ($request->filled(['from_date', 'to_date'])) {
            $from = Carbon::parse($request->from_date);
            $to = Carbon::parse($request->to_date);

            // Check for conflicts
            $conflict = Booking::where('room_id', $booking->room_id)
                ->where('id', '!=', $booking->id)
                ->where(function ($query) use ($from, $to) {
                    $query->whereBetween('from_date', [$from, $to])
                          ->orWhereBetween('to_date', [$from, $to])
                          ->orWhere(function ($q) use ($from, $to) {
                              $q->where('from_date', '<=', $from)
                                ->where('to_date', '>=', $to);
                          });
                })
                ->exists();

            if ($conflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected date range is not available for this room.',
                ], 409);
            }

            // Recalculate amount
            $nights = $from->diffInDays($to);
            $room = $booking->room; // assuming Booking has a room() relationship

            $booking->from_date = $from;
            $booking->to_date = $to;
            $booking->amount = $nights * $booking->price;
            $booking->save();

            return response()->json([
                'success' => true,
                'message' => 'Booking updated successfully.',
                'booking' => $booking,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No valid update fields provided.',
        ], 400);
    }

    if ($request->type === 'delete') {
        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Booking deleted successfully.',
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Invalid request type.',
    ], 400);
}
   

    public function uniqueReference(): string
    {
        $random = Str::upper(Str::random(4)); // Random 4-character string (A-Z, 0-9)
        $timestamp = now()->format('YmdHis'); // Current time for uniqueness
        $uniq = substr(uniqid(), -4); // 4-character unique fragment

        return strtoupper("{$random}-{$timestamp}-{$uniq}");
    }

    public function clearPendingBooking(Request $request)
    {
        $customer = $request->user();
        
        // Delete old unpaid bookings (older than 30 minutes)
        $deletedCount = Booking::where('customer_id', $customer->id)
            ->where('is_paid', 0)
            ->where('created_at', '<', now()->subMinutes(30))
            ->delete();
            
        // If force_clear is requested, also delete recent unpaid bookings
        if ($request->has('force_clear')) {
            $recentDeletedCount = Booking::where('customer_id', $customer->id)
                ->where('is_paid', 0)
                ->where('created_at', '>', now()->subMinutes(5))
                ->delete();
            $deletedCount += $recentDeletedCount;
        }

        return response()->json([
            'success' => true,
            'message' => "Cleared $deletedCount pending bookings.",
        ]);
    }

    public function getReceipt(Request $request, $id)
    {
        $customer = $request->user();
        
        $booking = Booking::where('id', $id)
            ->where('customer_id', $customer->id)
            ->where('is_paid', true)
            ->first();
            
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found or not paid',
            ], 404);
        }
        
        // Always regenerate receipt to ensure it uses the new public folder system
        // This fixes the issue where old storage URLs were being returned
        try {
            \Log::info('Generating receipt for booking', [
                'booking_id' => $booking->id,
                'old_receipt_url' => $booking->receipt_url
            ]);
            
            $receiptService = new \App\Services\ReceiptService();
            $receipt = $receiptService->generateReceipt($booking);
            
            $booking->update([
                'receipt_url' => $receipt['url'],
                'receipt_filename' => $receipt['filename'],
            ]);
            
            \Log::info('Receipt generated successfully', [
                'booking_id' => $booking->id,
                'new_receipt_url' => $receipt['url']
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to generate receipt', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate receipt: ' . $e->getMessage(),
            ], 500);
        }
        
        return response()->json([
            'success' => true,
            'receipt_url' => $booking->receipt_url,
            'receipt_filename' => $booking->receipt_filename,
            'booking' => $booking,
        ]);
    }

    /**
     * Get payment URL for an existing booking
     */
    public function getPaymentUrl(Request $request, $id)
    {
        $customer = $request->user();
        
        $booking = Booking::where('id', $id)
            ->where('customer_id', $customer->id)
            ->where('is_paid', false)
            ->first();
            
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found or already paid',
            ], 404);
        }

        // Check if booking has expired (older than 15 minutes)
        if ($booking->created_at < now()->subMinutes(15)) {
            return response()->json([
                'success' => false,
                'message' => 'This booking has expired. Please create a new booking.',
            ], 400);
        }

        try {
            // Generate payment URL using DPO service
            $dpoService = new \App\Services\DPOPaymentService();
            $paymentResult = $dpoService->preparePaymentPayload($booking, $customer);

            if ($paymentResult['success']) {
                return response()->json([
                    'success' => true,
                    'paymentUrl' => $paymentResult['url'],
                    'message' => 'Payment URL generated successfully',
                    'booking' => $booking,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate payment URL. Please try again.',
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to generate payment URL', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate payment URL: ' . $e->getMessage(),
            ], 500);
        }
    }



    public function getUserBookings(Request $request)
    {
        try {
            $customer = $request->user();
            
            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication failed',
                ], 401);
            }
            
            $bookings = Booking::where('customer_id', $customer->id)
                ->with(['accommodation', 'accommodationRoom'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'bookings' => $bookings,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getBookingDetails(Request $request, $id)
    {
        $customer = $request->user();
        
        $booking = Booking::where('id', $id)
            ->where('customer_id', $customer->id)
            ->with(['accommodation', 'accommodationRoom', 'customer'])
            ->first();
            
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'booking' => $booking,
        ]);
    }
}
