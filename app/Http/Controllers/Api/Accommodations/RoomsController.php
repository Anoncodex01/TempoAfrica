<?php

namespace App\Http\Controllers\Api\Accommodations;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\AccommodationRoom;
use App\Models\AccommodationRoomPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Booking;

class RoomsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except([
            'publicRooms',
            'publicRoom',
            'checkRoomAvailability',
            'getRoomAvailabilityCalendar'
        ]);
    }

    public function storeOrUpdate(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'nullable|exists:accommodation_rooms,id', // only required for update
            'accommodation_id' => 'required|exists:accommodations,id',
            'name' => 'required|string|max:255',
            'currency' => 'required|string|max:10',
            'price_duration' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'photo' => $request->has('id') ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048' : 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors(),
            ], 422);
        }

        $data = $validation->validated();

        $customer = $request->user();

        if (! empty($data['accommodation_id'])) {
            $accommodation = Accommodation::find($data['accommodation_id']);
            if (! $accommodation) {
                $message = 'Accommodation found!';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 404);
            }

            if ($accommodation->customer_id != $customer->id) {
                $message = 'Not related to your account found!';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 404);
            }
        }

        $data['customer_id'] = $customer->id;
        $data['accommodation_id'] = $accommodation->id;
        $unique_number = $this->uniqueReference($data);

        $data['unique_number'] = $unique_number;

        // Handle file upload if provided
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $fileName = time().'_'.uniqid().'.'.$photo->getClientOriginalExtension();
            $destination = public_path('uploads/accommodation-rooms');
            $photo->move($destination, $fileName);
            $data['photo'] = 'uploads/accommodation-rooms/'.$fileName;
        }

        // Determine whether to create or update
        if (! empty($data['id'])) {
            $accommodationRoom = AccommodationRoom::with('accommodation')->find($data['id']);
            
            // Security check: ensure user can only update rooms in their own properties
            if (!$accommodationRoom || $accommodationRoom->accommodation->customer_id != $customer->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Room not found or not authorized to update',
                ], 403);
            }
            
            $accommodationRoom->update($data);
            $message = 'Room updated successfully';
        } else {
            $accommodationRoom = AccommodationRoom::create($data);
            $message = 'Room created successfully';
        }

        // Handle facilities
        if ($request->has('facility_ids') && is_array($request->input('facility_ids'))) {
            $facilityIds = $request->input('facility_ids');
            $accommodationRoom->facilities()->sync($facilityIds);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'room' => $accommodationRoom,
        ], 200);
    }

    public function rooms(Request $request, $accommodationId)
    {
        $customer = $request->user();

        // First check if the accommodation belongs to the current user
        $accommodation = Accommodation::where('id', $accommodationId)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$accommodation) {
            return response()->json([
                'success' => false,
                'message' => 'Accommodation not found or not authorized',
            ], 404);
        }

        $rooms = AccommodationRoom::where('accommodation_id', $accommodationId)
            ->with(['photos', 'facilities'])
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Rooms',
            'data' => $rooms,
        ]);
    }

    public function room(Request $request, $roomId)
    {
        $customer = $request->user();
        
        $room = AccommodationRoom::with(['photos', 'facilities', 'accommodation'])
            ->whereHas('accommodation', function($query) use ($customer) {
                $query->where('customer_id', $customer->id);
            })
            ->findOrFail($roomId);

        return response()->json([
            'success' => true,
            'message' => 'Room',
            'data' => $room,
        ]);
    }

    public function addUpdatePhoto(Request $request)
    {
        \Log::info('Room photo upload request received', [
            'request_data' => $request->all(),
            'files' => $request->allFiles(),
        ]);

        $validation = Validator::make($request->all(), [
            'id' => 'nullable|exists:accommodation_room_photos,id',
            'accommodation_room_id' => 'required|exists:accommodation_rooms,id',
            'description' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validation->fails()) {
            \Log::error('Room photo upload validation failed', [
                'errors' => $validation->errors(),
            ]);
            return response()->json([
                'success' => false,
                'errors' => $validation->errors(),
            ], 422);
        }

        $data = $validation->validated();

        $customer = $request->user();

        if (! empty($data['id'])) {
            $accommodationPhoto = AccommodationRoomPhoto::with('accommodation')->find($data['id']);
            if (! $accommodationPhoto) {
                $message = 'AccommodationRoom photo found!';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 404);
            }

            if ($accommodationPhoto->accommodation->customer_id != $customer->id) {
                $message = 'Not related to your account found!';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 404);
            }

            if ($accommodationPhoto->photo && file_exists(public_path($accommodationPhoto->photo))) {
                unlink(public_path($accommodationPhoto->photo));
            }

            $accommodationPhoto->delete();

            return response()->json([
                'success' => true,
                'message' => 'Photo deleted successfully.',
            ]);

        }

        $accommodationRoom = AccommodationRoom::with('accommodation')->find($data['accommodation_room_id']);

        if (! $accommodationRoom) {
            $message = 'AccommodationRoom not found!';

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 404);
        }

        if ($accommodationRoom->accommodation->customer_id != $customer->id) {
            $message = 'Not associated to your account!';

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 504);
        }

        $data = $validation->validated();

        // Handle file upload if provided
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $fileName = time().'_'.uniqid().'.'.$photo->getClientOriginalExtension();
            $destination = public_path('uploads/accommodation-rooms');
            $photo->move($destination, $fileName);
            $data['photo'] = 'uploads/accommodation-rooms/'.$fileName;
        }

        $accommodationPhoto = AccommodationRoomPhoto::create($data);
        $message = 'Photo uploaded successfully';

        \Log::info('Room photo uploaded successfully', [
            'accommodation_room_id' => $data['accommodation_room_id'],
            'photo_path' => $data['photo'],
            'photo_id' => $accommodationPhoto->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $accommodationPhoto,
        ], 200);
    }

    public function deleteRoom(Request $request, $roomId)
    {
        $customer = $request->user();
        
        $room = AccommodationRoom::with('accommodation')->find($roomId);
        
        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'Room not found',
            ], 404);
        }
        
        // Security check: ensure user can only delete rooms in their own properties
        if ($room->accommodation->customer_id != $customer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Not authorized to delete this room',
            ], 403);
        }
        
        $room->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Room deleted successfully',
        ], 200);
    }

    public function toggleRoomStatus(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'room_id' => 'required|exists:accommodation_rooms,id',
            'is_active' => 'required|boolean',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors(),
            ], 422);
        }

        $customer = $request->user();
        $data = $validation->validated();
        
        $room = AccommodationRoom::with('accommodation')->find($data['room_id']);
        
        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'Room not found',
            ], 404);
        }
        
        // Security check: ensure user can only toggle rooms in their own properties
        if ($room->accommodation->customer_id != $customer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Not authorized to modify this room',
            ], 403);
        }
        
        $room->update(['is_active' => $data['is_active']]);
        
        return response()->json([
            'success' => true,
            'message' => 'Room status updated successfully',
        ], 200);
    }

    public function uniqueReference($data)
    {
        $name = Str::upper(Str::slug($data['name'], '')); // sanitize name
        $nameLetters = str_split(Str::padRight(Str::substr($name, 0, 4), 4, 'X')); // ensure 4 letters
        $unique_number =
            $data['accommodation_id'].$nameLetters[0].'-'.
            $data['customer_id'].$nameLetters[1].'-'.
            now()->format('Ymd');

        return $unique_number;
    }

    // Public methods for browsing rooms (no auth required)
    public function publicRooms($accommodationId)
    {
        // Check if the accommodation exists and is approved
        $accommodation = Accommodation::where('id', $accommodationId)
            ->where('is_visible', true)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->first();

        if (!$accommodation) {
            return response()->json([
                'success' => false,
                'message' => 'Accommodation not found',
            ], 404);
        }

        $rooms = AccommodationRoom::where('accommodation_id', $accommodationId)
            ->where('is_visible', true)
            ->where('is_active', true)
            ->with(['photos', 'facilities'])
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Public Rooms',
            'data' => $rooms,
        ]);
    }

    public function publicRoom($roomId)
    {
        $room = AccommodationRoom::with(['photos', 'facilities', 'accommodation'])
            ->whereHas('accommodation', function($query) {
                $query->where('is_visible', true)
                      ->where('is_active', true)
                      ->where('is_approved', true);
            })
            ->where('is_visible', true)
            ->where('is_active', true)
            ->findOrFail($roomId);

        return response()->json([
            'success' => true,
            'message' => 'Public Room',
            'data' => $room,
        ]);
    }

    public function checkRoomAvailability(Request $request, $roomId)
    {
        $validation = Validator::make($request->all(), [
            'from_date' => 'required|date|after_or_equal:today',
            'to_date' => 'required|date|after:from_date',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors(),
            ], 422);
        }

        $room = AccommodationRoom::find($roomId);
        
        if (!$room || !$room->is_active || !$room->is_visible || !$room->is_available) {
            return response()->json([
                'success' => false,
                'message' => 'Room is not available for booking.',
                'available' => false,
            ], 422);
        }

        $fromDate = Carbon::parse($request->from_date)->startOfDay();
        $toDate = Carbon::parse($request->to_date)->endOfDay();

        // Check for existing bookings in the date range
        $conflictingBookings = Booking::where('accommodation_room_id', $roomId)
            ->where(function ($query) use ($fromDate, $toDate) {
                $query->where('from_date', '<=', $toDate)
                    ->where('to_date', '>=', $fromDate);
            })
            ->where(function ($query) {
                $query->where('is_paid', 1)
                    ->orWhere(function ($q) {
                        $q->where('is_paid', 0)
                            ->where('created_at', '>', now()->subMinutes(15));
                    });
            })
            ->get();

        $isAvailable = $conflictingBookings->isEmpty();

        return response()->json([
            'success' => true,
            'available' => $isAvailable,
            'message' => $isAvailable ? 'Room is available for the selected dates.' : 'Room is not available for the selected dates.',
            'conflicting_bookings' => $conflictingBookings->map(function ($booking) {
                return [
                    'from_date' => $booking->from_date,
                    'to_date' => $booking->to_date,
                    'is_paid' => $booking->is_paid,
                ];
            }),
        ]);
    }

    public function getRoomAvailabilityCalendar(Request $request, $roomId)
    {
        $room = AccommodationRoom::find($roomId);
        
        if (!$room || !$room->is_active || !$room->is_visible || !$room->is_available) {
            return response()->json([
                'success' => false,
                'message' => 'Room is not available for booking.',
            ], 422);
        }

        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // Get all bookings for this room in the month
        $bookings = Booking::where('accommodation_room_id', $roomId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where('from_date', '<=', $endDate)
                    ->where('to_date', '>=', $startDate);
            })
            ->where(function ($query) {
                $query->where('is_paid', 1)
                    ->orWhere(function ($q) {
                        $q->where('is_paid', 0)
                            ->where('created_at', '>', now()->subMinutes(15));
                    });
            })
            ->get();

        // Create availability calendar
        $calendar = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->toDateString();
            $isBooked = false;
            $isPending = false;
            $bookingInfo = null;

            // Check if this date is booked
            foreach ($bookings as $booking) {
                $bookingStart = Carbon::parse($booking->from_date);
                $bookingEnd = Carbon::parse($booking->to_date);
                
                if ($currentDate->between($bookingStart, $bookingEnd)) {
                    $isBooked = true;
                    $isPending = !$booking->is_paid; // Check if booking is unpaid (pending)
                    $bookingInfo = [
                        'from_date' => $booking->from_date,
                        'to_date' => $booking->to_date,
                        'is_paid' => $booking->is_paid,
                        'status' => $booking->is_paid ? 'paid' : 'pending',
                    ];
                    break;
                }
            }

            $calendar[] = [
                'date' => $dateString,
                'day' => $currentDate->day,
                'is_available' => !$isBooked,
                'is_pending' => $isPending, // New field for pending status
                'is_today' => $currentDate->isToday(),
                'is_past' => $currentDate->isPast(),
                'booking_info' => $bookingInfo,
            ];

            $currentDate->addDay();
        }

        return response()->json([
            'success' => true,
            'calendar' => $calendar,
            'year' => $year,
            'month' => $month,
            'month_name' => $startDate->format('F'),
        ]);
    }
}
