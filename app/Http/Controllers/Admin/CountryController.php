<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        return view('admin.countries.index', compact('countries'));
    }

    public function create()
    {
        return view('admin.countries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country_code' => 'required|string|max:10',
            'zip_code' => 'nullable|string|max:20',
            'is_established' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['is_established'] = $request->has('is_established') ? 1 : 0;

        Country::create($data);

        return redirect()->route('admin.countries.index')
            ->with('success', 'Country created successfully.');
    }

    public function edit(Country $country)
    {
        return view('admin.countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country_code' => 'required|string|max:10',
            'zip_code' => 'nullable|string|max:20',
            'is_established' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['is_established'] = $request->has('is_established') ? 1 : 0;

        $country->update($data);

        return redirect()->route('admin.countries.index')
            ->with('success', 'Country updated successfully.');
    }

    public function destroy(Country $country)
    {
        $country->delete();

        return redirect()->route('admin.countries.index')
            ->with('success', 'Country deleted successfully.');
    }
} 