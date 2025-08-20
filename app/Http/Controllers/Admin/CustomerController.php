<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Country;
use App\Models\Province;
use App\Models\Street;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with(['country', 'province', 'street']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Verification status filter
        if ($request->filled('is_verified')) {
            $query->where('is_verified', $request->is_verified);
        }

        // Country filter
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        // Gender filter
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $customers = $query->paginate(15)->withQueryString();
        $countries = \App\Models\Country::all();

        // Stats
        $total = Customer::count();
        $verified = Customer::where('is_verified', true)->count();
        $unverified = Customer::where('is_verified', false)->count();

        return view('admin.customers.index', compact('customers', 'countries', 'total', 'verified', 'unverified'));
    }

    public function show(Customer $customer)
    {
        $customer->load(['country', 'province', 'street', 'accommodations', 'houses']);
        return view('admin.customers.show', compact('customer'));
    }

    public function create()
    {
        $countries = Country::all();
        $provinces = Province::all();
        $streets = Street::all();
        return view('admin.customers.create', compact('countries', 'provinces', 'streets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:customers,email',
            'country_id' => 'nullable|exists:countries,id',
            'province_id' => 'nullable|exists:provinces,id',
            'street_id' => 'nullable|exists:streets,id',
            'gender' => 'nullable|string|max:10',
            'dob' => 'nullable|date',
            'photo' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('customers', 'public');
        }
        Customer::create($validated);
        return redirect()->route('admin.customers.index')->with('success', 'Customer created successfully.');
    }

    public function edit(Customer $customer)
    {
        $countries = Country::all();
        $provinces = Province::all();
        $streets = Street::all();
        return view('admin.customers.edit', compact('customer', 'countries', 'provinces', 'streets'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'country_id' => 'nullable|exists:countries,id',
            'province_id' => 'nullable|exists:provinces,id',
            'street_id' => 'nullable|exists:streets,id',
            'gender' => 'nullable|string|max:10',
            'dob' => 'nullable|date',
            'photo' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('customers', 'public');
        }
        $customer->update($validated);
        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully.');
    }
} 