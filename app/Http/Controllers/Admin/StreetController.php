<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Street;
use App\Models\Province;
use Illuminate\Http\Request;

class StreetController extends Controller
{
    public function index()
    {
        $streets = Street::with('province')->get();
        return view('admin.streets.index', compact('streets'));
    }

    public function create()
    {
        $provinces = Province::all();
        return view('admin.streets.create', compact('provinces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'province_id' => 'required|exists:provinces,id',
            'address' => 'nullable|string|max:500',
            'is_established' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['is_established'] = $request->has('is_established') ? 1 : 0;

        Street::create($data);

        return redirect()->route('admin.streets.index')
            ->with('success', 'Street created successfully.');
    }

    public function edit(Street $street)
    {
        $provinces = Province::all();
        return view('admin.streets.edit', compact('street', 'provinces'));
    }

    public function update(Request $request, Street $street)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'province_id' => 'required|exists:provinces,id',
            'address' => 'nullable|string|max:500',
            'is_established' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['is_established'] = $request->has('is_established') ? 1 : 0;

        $street->update($data);

        return redirect()->route('admin.streets.index')
            ->with('success', 'Street updated successfully.');
    }

    public function destroy(Street $street)
    {
        $street->delete();

        return redirect()->route('admin.streets.index')
            ->with('success', 'Street deleted successfully.');
    }
} 