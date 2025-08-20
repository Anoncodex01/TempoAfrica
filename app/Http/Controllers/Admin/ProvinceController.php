<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\Country;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function index()
    {
        $provinces = Province::with('country')->get();
        return view('admin.provinces.index', compact('provinces'));
    }

    public function create()
    {
        $countries = Country::all();
        return view('admin.provinces.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'zip_code' => 'nullable|string|max:20',
            'is_established' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['is_established'] = $request->has('is_established') ? 1 : 0;

        Province::create($data);

        return redirect()->route('admin.provinces.index')
            ->with('success', 'Province created successfully.');
    }

    public function edit(Province $province)
    {
        $countries = Country::all();
        return view('admin.provinces.edit', compact('province', 'countries'));
    }

    public function update(Request $request, Province $province)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'zip_code' => 'nullable|string|max:20',
            'is_established' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['is_established'] = $request->has('is_established') ? 1 : 0;

        $province->update($data);

        return redirect()->route('admin.provinces.index')
            ->with('success', 'Province updated successfully.');
    }

    public function destroy(Province $province)
    {
        $province->delete();

        return redirect()->route('admin.provinces.index')
            ->with('success', 'Province deleted successfully.');
    }
} 