<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\House;
use App\Models\Customer;
use App\Models\Country;
use App\Models\Province;
use App\Models\District;
use App\Models\Street;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class HouseController extends Controller
{
    public function index(Request $request)
    {
        $query = House::with(['customer', 'country', 'province', 'district', 'street']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%")
                  ->orWhere('unique_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('first_name', 'like', "%{$search}%")
                                   ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                case 'visible':
                    $query->where('is_visible', true);
                    break;
                case 'featured':
                    $query->where('is_featured', true);
                    break;
                case 'approved':
                    $query->where('is_approved', true);
                    break;
            }
        }

        // Individual filters
        if ($request->filled('is_active')) $query->where('is_active', $request->is_active);
        if ($request->filled('is_visible')) $query->where('is_visible', $request->is_visible);
        if ($request->filled('is_approved')) $query->where('is_approved', $request->is_approved);
        if ($request->filled('is_featured')) $query->where('is_featured', $request->is_featured);
        if ($request->filled('category')) $query->where('category', $request->category);
        if ($request->filled('country_id')) $query->where('country_id', $request->country_id);
        if ($request->filled('province_id')) $query->where('province_id', $request->province_id);
        if ($request->filled('district_id')) $query->where('district_id', $request->district_id);
        if ($request->filled('street_id')) $query->where('street_id', $request->street_id);
        if ($request->filled('customer_id')) $query->where('customer_id', $request->customer_id);

        // Price range filter
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $houses = $query->paginate(12)->withQueryString();

        // Get filter data
        $countries = Country::all();
        $provinces = Province::all();
        $districts = District::all();
        $streets = Street::all();
        $customers = Customer::all();
        $categories = House::distinct()->pluck('category')->filter();

        return view('admin.houses.index', compact('houses', 'countries', 'provinces', 'districts', 'streets', 'customers', 'categories'));
    }

    public function show(House $house) {
        $house->load(['customer', 'country', 'province', 'district', 'street']);
        return view('admin.houses.show', compact('house'));
    }

    public function create() {
        $countries = Country::all();
        $provinces = Province::all();
        $districts = District::all();
        $streets = Street::all();
        $customers = Customer::all();
        $categories = ['Apartment', 'House', 'Villa', 'Cottage', 'Studio', 'Penthouse', 'Townhouse', 'Duplex'];
        return view('admin.houses.create', compact('countries', 'provinces', 'districts', 'streets', 'customers', 'categories'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'nullable|string|max:255',
            'unique_number' => 'nullable|string|max:255',
            'currency' => 'required|string|max:10',
            'price_duration' => 'nullable|string|max:50',
            'price' => 'required|numeric',
            'fee' => 'nullable|numeric',
            'minimum_rent_duration' => 'nullable|integer',
            'booking_price' => 'nullable|numeric',
            'number_of_rooms' => 'nullable|integer',
            'is_active' => 'boolean',
            'is_visible' => 'boolean',
            'is_booked' => 'boolean',
            'is_approved' => 'boolean',
            'is_featured' => 'boolean',
            'has_water' => 'boolean',
            'has_electricity' => 'boolean',
            'has_fence' => 'boolean',
            'has_public_transport' => 'boolean',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'customer_id' => 'nullable|exists:customers,id',
            'booked_by' => 'nullable|exists:customers,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'country_id' => 'nullable|exists:countries,id',
            'province_id' => 'nullable|exists:provinces,id',
            'district_id' => 'nullable|exists:districts,id',
            'street_id' => 'nullable|exists:streets,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('uploads/houses'), $filename);
            $validated['photo'] = 'uploads/houses/' . $filename;
        }

        House::create($validated);
        return redirect()->route('admin.houses.index')->with('success', 'House created successfully.');
    }

    public function edit(House $house) {
        $countries = Country::all();
        $provinces = Province::all();
        $districts = District::all();
        $streets = Street::all();
        $customers = Customer::all();
        $categories = ['Apartment', 'House', 'Villa', 'Cottage', 'Studio', 'Penthouse', 'Townhouse', 'Duplex'];
        return view('admin.houses.edit', compact('house', 'countries', 'provinces', 'districts', 'streets', 'customers', 'categories'));
    }

    public function update(Request $request, House $house) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'nullable|string|max:255',
            'unique_number' => 'nullable|string|max:255',
            'currency' => 'required|string|max:10',
            'price_duration' => 'nullable|string|max:50',
            'price' => 'required|numeric',
            'fee' => 'nullable|numeric',
            'minimum_rent_duration' => 'nullable|integer',
            'booking_price' => 'nullable|numeric',
            'number_of_rooms' => 'nullable|integer',
            'is_active' => 'boolean',
            'is_visible' => 'boolean',
            'is_booked' => 'boolean',
            'is_approved' => 'boolean',
            'is_featured' => 'boolean',
            'has_water' => 'boolean',
            'has_electricity' => 'boolean',
            'has_fence' => 'boolean',
            'has_public_transport' => 'boolean',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'customer_id' => 'nullable|exists:customers,id',
            'booked_by' => 'nullable|exists:customers,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'country_id' => 'nullable|exists:countries,id',
            'province_id' => 'nullable|exists:provinces,id',
            'district_id' => 'nullable|exists:districts,id',
            'street_id' => 'nullable|exists:streets,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($house->photo && file_exists(public_path($house->photo))) {
                unlink(public_path($house->photo));
            }
            
            $photo = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('uploads/houses'), $filename);
            $validated['photo'] = 'uploads/houses/' . $filename;
        }

        $house->update($validated);
        return redirect()->route('admin.houses.index')->with('success', 'House updated successfully.');
    }

    public function destroy(House $house) {
        DB::beginTransaction();

        try {
            // Delete photo from public directory
            if ($house->photo && file_exists(public_path($house->photo))) {
                unlink(public_path($house->photo));
            }

            // Delete the house (this will cascade delete related records)
            $house->delete();

            DB::commit();

            return redirect()->route('admin.houses.index')
                ->with('success', 'House deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting house: ' . $e->getMessage());
        }
    }

    public function toggleStatus(House $house)
    {
        $house->update([
            'is_active' => !$house->is_active
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $house->is_active,
            'message' => 'Status updated successfully'
        ]);
    }

    public function toggleVisibility(House $house)
    {
        $house->update([
            'is_visible' => !$house->is_visible
        ]);

        return response()->json([
            'success' => true,
            'is_visible' => $house->is_visible,
            'message' => 'Visibility updated successfully'
        ]);
    }

    public function toggleFeatured(House $house)
    {
        $house->update([
            'is_featured' => !$house->is_featured
        ]);

        return response()->json([
            'success' => true,
            'is_featured' => $house->is_featured,
            'message' => 'Featured status updated successfully'
        ]);
    }

    public function approve(House $house)
    {
        $house->update([
            'is_approved' => true
        ]);

        return redirect()->route('admin.houses.index')
            ->with('success', 'House approved successfully.');
    }
} 