<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Accommodation;
use App\Models\AccommodationRoom;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['accommodation', 'accommodationRoom', 'customer']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('accommodation', function($accommodationQuery) use ($search) {
                      $accommodationQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->status;
            switch ($status) {
                case 'pending':
                    $query->where('is_paid', false)->where('is_cancelled', false);
                    break;
                case 'paid':
                    $query->where('is_paid', true);
                    break;
                case 'checked_in':
                    $query->where('is_checked_in', true);
                    break;
                case 'checked_out':
                    $query->where('is_checked_out', true);
                    break;
                case 'cancelled':
                    $query->where('is_cancelled', true);
                    break;
            }
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('from_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('to_date', '<=', $request->date_to);
        }

        // Amount range filter
        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }

        // Accommodation filter
        if ($request->filled('accommodation_id')) {
            $query->where('accommodation_id', $request->accommodation_id);
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Apply pagination
        $bookings = $query->paginate(15);

        // Get accommodations for filter dropdown
        $accommodations = Accommodation::where('is_active', true)->get();

        // Calculate stats and revenue
        $totalBookings = Booking::count();
        $paidBookings = Booking::where('is_paid', true)->count();
        $pendingBookings = Booking::where('is_paid', false)->where('is_cancelled', false)->count();
        $cancelledBookings = Booking::where('is_cancelled', true)->count();
        $totalRevenue = Booking::where('is_paid', true)->sum('amount');
        $monthlyRevenue = Booking::where('is_paid', true)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('amount');

        return view('admin.bookings.index', compact(
            'bookings', 
            'accommodations', 
            'totalBookings', 
            'paidBookings', 
            'pendingBookings', 
            'cancelledBookings',
            'totalRevenue',
            'monthlyRevenue'
        ));
    }

    public function create()
    {
        $accommodations = Accommodation::where('is_active', true)->get();
        $customers = Customer::all();
        
        return view('admin.bookings.create', compact('accommodations', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'accommodation_id' => 'required|exists:accommodations,id',
            'accommodation_room_id' => 'required|exists:accommodation_rooms,id',
            'customer_id' => 'required|exists:customers,id',
            'from_date' => 'required|date|after_or_equal:today',
            'to_date' => 'required|date|after:from_date',
            'pacs' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'is_paid' => 'boolean',
            'is_checked_in' => 'boolean',
            'is_checked_out' => 'boolean',
            'is_cancelled' => 'boolean',
        ]);

        $data = $request->all();
        
        // Calculate amount based on duration and price
        $fromDate = Carbon::parse($data['from_date']);
        $toDate = Carbon::parse($data['to_date']);
        $duration = $fromDate->diffInDays($toDate);
        $data['amount'] = $data['price'] * $duration;
        
        // Generate unique reference
        $data['reference'] = $this->generateReference();
        
        // Set payment status
        $data['is_paid'] = $request->has('is_paid') ? 1 : 0;
        $data['is_checked_in'] = $request->has('is_checked_in') ? 1 : 0;
        $data['is_checked_out'] = $request->has('is_checked_out') ? 1 : 0;
        $data['is_cancelled'] = $request->has('is_cancelled') ? 1 : 0;
        
        // Set timestamps for check-in/out if applicable
        if ($data['is_checked_in']) {
            $data['checked_in_at'] = now();
        }
        if ($data['is_checked_out']) {
            $data['checked_out_at'] = now();
        }
        if ($data['is_paid']) {
            $data['paid_at'] = now();
            $data['amount_paid'] = $data['amount'];
        }

        Booking::create($data);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking created successfully.');
    }

    public function show(Booking $booking)
    {
        $booking->load(['accommodation', 'accommodationRoom', 'customer']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $accommodations = Accommodation::where('is_active', true)->get();
        $rooms = AccommodationRoom::where('accommodation_id', $booking->accommodation_id)->get();
        $customers = Customer::all();
        
        return view('admin.bookings.edit', compact('booking', 'accommodations', 'rooms', 'customers'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'accommodation_id' => 'required|exists:accommodations,id',
            'accommodation_room_id' => 'required|exists:accommodation_rooms,id',
            'customer_id' => 'required|exists:customers,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after:from_date',
            'pacs' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'is_paid' => 'boolean',
            'is_checked_in' => 'boolean',
            'is_checked_out' => 'boolean',
            'is_cancelled' => 'boolean',
        ]);

        $data = $request->all();
        
        // Calculate amount based on duration and price
        $fromDate = Carbon::parse($data['from_date']);
        $toDate = Carbon::parse($data['to_date']);
        $duration = $fromDate->diffInDays($toDate);
        $data['amount'] = $data['price'] * $duration;
        
        // Set payment status
        $data['is_paid'] = $request->has('is_paid') ? 1 : 0;
        $data['is_checked_in'] = $request->has('is_checked_in') ? 1 : 0;
        $data['is_checked_out'] = $request->has('is_checked_out') ? 1 : 0;
        $data['is_cancelled'] = $request->has('is_cancelled') ? 1 : 0;
        
        // Handle check-in/out timestamps
        if ($data['is_checked_in'] && !$booking->is_checked_in) {
            $data['checked_in_at'] = now();
        }
        if ($data['is_checked_out'] && !$booking->is_checked_out) {
            $data['checked_out_at'] = now();
        }
        if ($data['is_paid'] && !$booking->is_paid) {
            $data['paid_at'] = now();
            $data['amount_paid'] = $data['amount'];
        }

        $booking->update($data);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking updated successfully.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }

    public function getRooms(Request $request)
    {
        $accommodationId = $request->accommodation_id;
        $rooms = AccommodationRoom::where('accommodation_id', $accommodationId)
            ->where('is_active', true)
            ->get();
        
        return response()->json($rooms);
    }

    public function export(Booking $booking)
    {
        // Check if dompdf is installed
        if (!class_exists('Barryvdh\\DomPDF\\Facade\\Pdf')) {
            return response()->view('admin.bookings.export-placeholder', compact('booking'));
        }

        $pdf = \PDF::loadView('admin.bookings.export', compact('booking'));
        $filename = 'booking_' . $booking->reference . '.pdf';
        return $pdf->download($filename);
    }

    private function generateReference()
    {
        $random = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 4));
        $timestamp = now()->format('YmdHis');
        $uniq = substr(uniqid(), -4);
        
        return "{$random}-{$timestamp}-{$uniq}";
    }
} 