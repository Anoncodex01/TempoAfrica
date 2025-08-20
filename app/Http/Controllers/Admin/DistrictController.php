<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index()
    {
        $districts = District::with('province')->get();
        return view('admin.districts.index', compact('districts'));
    }

    public function create()
    {
        $provinces = Province::all();
        return view('admin.districts.create', compact('provinces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'province_id' => 'required|exists:provinces,id',
            'zip_code' => 'nullable|string|max:20',
            'is_established' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['is_established'] = $request->has('is_established') ? 1 : 0;

        District::create($data);

        return redirect()->route('admin.districts.index')
            ->with('success', 'District created successfully.');
    }

    public function edit(District $district)
    {
        $provinces = Province::all();
        return view('admin.districts.edit', compact('district', 'provinces'));
    }

    public function update(Request $request, District $district)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'province_id' => 'required|exists:provinces,id',
            'zip_code' => 'nullable|string|max:20',
            'is_established' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['is_established'] = $request->has('is_established') ? 1 : 0;

        $district->update($data);

        return redirect()->route('admin.districts.index')
            ->with('success', 'District updated successfully.');
    }

    public function destroy(District $district)
    {
        $district->delete();

        return redirect()->route('admin.districts.index')
            ->with('success', 'District deleted successfully.');
    }
} 