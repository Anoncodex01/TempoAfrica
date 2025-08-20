<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\House;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request, House $house)
    {
        $query = $house->rooms();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('unique_number', 'like', "%{$search}%");
            });
        }
        if ($request->filled('is_active')) $query->where('is_active', $request->is_active);
        if ($request->filled('is_visible')) $query->where('is_visible', $request->is_visible);
        if ($request->filled('category')) $query->where('category', $request->category);
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        $rooms = $query->paginate(15)->withQueryString();
        return view('admin.rooms.index', compact('house', 'rooms'));
    }
    public function indexGlobal(Request $request)
    {
        $query = \App\Models\Room::with('house');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('unique_number', 'like', "%{$search}%");
            });
        }
        if ($request->filled('is_active')) $query->where('is_active', $request->is_active);
        if ($request->filled('is_visible')) $query->where('is_visible', $request->is_visible);
        if ($request->filled('category')) $query->where('category', $request->category);
        if ($request->filled('house_id')) $query->where('house_id', $request->house_id);
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        $rooms = $query->paginate(15)->withQueryString();
        $houses = \App\Models\House::all();
        return view('admin.rooms.global-index', compact('rooms', 'houses'));
    }
    public function create(House $house)
    {
        return view('admin.rooms.create', compact('house'));
    }
    public function store(Request $request, House $house)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unique_number' => 'nullable|string|max:255',
            'currency' => 'required|string|max:10',
            'price_duration' => 'nullable|string|max:50',
            'price' => 'required|numeric',
            'is_active' => 'boolean',
            'is_visible' => 'boolean',
            'category' => 'nullable|string|max:100',
            'photo' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('room_photos', 'public');
        }
        $house->rooms()->create($validated);
        return redirect()->route('admin.rooms.index', $house)->with('success', 'Room created successfully.');
    }
    public function show(House $house, Room $room)
    {
        return view('admin.rooms.show', compact('house', 'room'));
    }
    public function edit(House $house, Room $room)
    {
        return view('admin.rooms.edit', compact('house', 'room'));
    }
    public function update(Request $request, House $house, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unique_number' => 'nullable|string|max:255',
            'currency' => 'required|string|max:10',
            'price_duration' => 'nullable|string|max:50',
            'price' => 'required|numeric',
            'is_active' => 'boolean',
            'is_visible' => 'boolean',
            'category' => 'nullable|string|max:100',
            'photo' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('room_photos', 'public');
        }
        $room->update($validated);
        return redirect()->route('admin.rooms.index', $house)->with('success', 'Room updated successfully.');
    }
    public function destroy(House $house, Room $room)
    {
        $room->delete();
        return redirect()->route('admin.rooms.index', $house)->with('success', 'Room deleted successfully.');
    }
} 