<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HouseBooking;
use App\Models\House;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HouseBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HouseBooking::with(['house', 'customer']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('payment_token', 'like', "%{$search}%")
                  ->orWhereHas('house', function($houseQuery) use ($search) {
                      $houseQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('registration_number', 'like', "%{$search}%")
                                ->orWhere('unique_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('first_name', 'like', "%{$search}%")
                                   ->orWhere('last_name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'paid':
                    $query->where('is_paid', true);
                    break;
                case 'pending':
                    $query->where('is_paid', false);
                    break;
            }
        }

        // Filter by date from
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // Filter by house
        if ($request->filled('house_id')) {
            $query->where('house_id', $request->house_id);
        }

        $houseBookings = $query->latest()->paginate(15);

        // Get filter options
        $houses = House::orderBy('name')->get();

        // Stats
        $totalBookings = HouseBooking::count();
        $paidBookings = HouseBooking::where('is_paid', true)->count();
        $pendingBookings = HouseBooking::where('is_paid', false)->count();
        $totalRevenue = HouseBooking::where('is_paid', true)->sum('amount');
        $monthlyRevenue = HouseBooking::where('is_paid', true)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('amount');

        return view('admin.house-bookings.index', compact(
            'houseBookings',
            'houses',
            'totalBookings',
            'paidBookings',
            'pendingBookings',
            'totalRevenue',
            'monthlyRevenue'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $houses = House::where('is_active', true)->orderBy('name')->get();
        $customers = Customer::orderBy('first_name')->get();
        
        return view('admin.house-bookings.create', compact('houses', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'house_id' => 'required|exists:houses,id',
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'is_paid' => 'boolean',
        ]);

        $data = $request->all();
        
        // Generate unique reference
        $data['reference'] = $this->generateReference();
        
        // Set payment status
        $data['is_paid'] = $request->has('is_paid') ? 1 : 0;
        
        if ($data['is_paid']) {
            $data['paid_at'] = now();
            $data['amount_paid'] = $data['amount'];
        }

        HouseBooking::create($data);

        return redirect()->route('admin.house-bookings.index')
            ->with('success', 'House booking created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HouseBooking $houseBooking)
    {
        $houseBooking->load(['house', 'customer']);
        return view('admin.house-bookings.show', compact('houseBooking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HouseBooking $houseBooking)
    {
        $houses = House::where('is_active', true)->orderBy('name')->get();
        $customers = Customer::orderBy('first_name')->get();
        
        return view('admin.house-bookings.edit', compact('houseBooking', 'houses', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HouseBooking $houseBooking)
    {
        $request->validate([
            'house_id' => 'required|exists:houses,id',
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'is_paid' => 'boolean',
        ]);

        $data = $request->all();
        
        // Set payment status
        $data['is_paid'] = $request->has('is_paid') ? 1 : 0;
        
        // Handle payment timestamp
        if ($data['is_paid'] && !$houseBooking->is_paid) {
            $data['paid_at'] = now();
            $data['amount_paid'] = $data['amount'];
        } elseif (!$data['is_paid']) {
            $data['paid_at'] = null;
            $data['amount_paid'] = null;
        }

        $houseBooking->update($data);

        return redirect()->route('admin.house-bookings.index')
            ->with('success', 'House booking updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HouseBooking $houseBooking)
    {
        $houseBooking->delete();

        return redirect()->route('admin.house-bookings.index')
            ->with('success', 'House booking deleted successfully.');
    }

    /**
     * Toggle payment status
     */
    public function togglePaymentStatus(HouseBooking $houseBooking)
    {
        $houseBooking->update([
            'is_paid' => !$houseBooking->is_paid,
            'paid_at' => !$houseBooking->is_paid ? now() : null,
            'amount_paid' => !$houseBooking->is_paid ? $houseBooking->amount : null,
        ]);

        return response()->json([
            'success' => true,
            'is_paid' => $houseBooking->fresh()->is_paid,
            'message' => $houseBooking->is_paid ? 'Payment marked as completed' : 'Payment marked as pending'
        ]);
    }

    /**
     * Mark as paid
     */
    public function markAsPaid(HouseBooking $houseBooking)
    {
        $houseBooking->update([
            'is_paid' => true,
            'paid_at' => now(),
            'amount_paid' => $houseBooking->amount,
        ]);

        return redirect()->route('admin.house-bookings.index')
            ->with('success', 'House booking marked as paid successfully.');
    }

    /**
     * Generate unique reference
     */
    private function generateReference()
    {
        do {
            $reference = strtoupper(uniqid()) . '-' . date('YmdHis') . '-' . strtoupper(substr(md5(rand()), 0, 4));
        } while (HouseBooking::where('reference', $reference)->exists());

        return $reference;
    }
}
