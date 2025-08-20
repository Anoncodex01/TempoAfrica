<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\AccommodationRoom;
use App\Models\AccommodationRoomPhoto;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AccommodationRoomController extends Controller
{
    public function index(Request $request)
    {
        $query = AccommodationRoom::with(['accommodation', 'photos']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('unique_number', 'like', "%{$search}%")
                  ->orWhereHas('accommodation', function($accommodationQuery) use ($search) {
                      $accommodationQuery->where('name', 'like', "%{$search}%");
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
                case 'available':
                    $query->where('is_available', true);
                    break;
            }
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Accommodation filter
        if ($request->filled('accommodation_id')) {
            $query->where('accommodation_id', $request->accommodation_id);
        }

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

        $rooms = $query->paginate(12);

        // Get filter data
        $accommodations = Accommodation::where('is_active', true)->get();
        $categories = AccommodationRoom::distinct()->pluck('category')->filter();

        return view('admin.accommodation-rooms.index', compact('rooms', 'accommodations', 'categories'));
    }

    public function create()
    {
        $accommodations = Accommodation::where('is_active', true)->get();
        $categories = ['Standard', 'Deluxe', 'Suite', 'Executive', 'Presidential', 'Family', 'Single', 'Double', 'Twin', 'Triple'];
        $facilities = Facility::where('is_active', true)->get();

        return view('admin.accommodation-rooms.create', compact('accommodations', 'categories', 'facilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'unique_number' => 'nullable|string|max:100|unique:accommodation_rooms',
            'is_active' => 'boolean',
            'currency' => 'required|string|max:10',
            'is_visible' => 'boolean',
            'is_available' => 'boolean',
            'price' => 'required|numeric|min:0',
            'price_duration' => 'required|string|in:per_night,per_week,per_month',
            'description' => 'nullable|string',
            'accommodation_id' => 'required|exists:accommodations,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_descriptions.*' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->all();
            
            // Handle boolean fields
            $data['is_active'] = $request->has('is_active');
            $data['is_visible'] = $request->has('is_visible');
            $data['is_available'] = $request->has('is_available');

            // Handle main photo
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $fileName = time().'_'.uniqid().'.'.$photo->getClientOriginalExtension();
                $destination = public_path('uploads/accommodation-rooms');
                $photo->move($destination, $fileName);
                $data['photo'] = 'uploads/accommodation-rooms/'.$fileName;
            }

            $room = AccommodationRoom::create($data);

            // Handle facilities
            if ($request->has('facility_ids')) {
                $room->facilities()->sync($request->facility_ids);
            }

            // Handle additional photos
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $index => $photo) {
                    $fileName = time().'_'.uniqid().'.'.$photo->getClientOriginalExtension();
                    $destination = public_path('uploads/accommodation-rooms');
                    $photo->move($destination, $fileName);
                    
                    AccommodationRoomPhoto::create([
                        'accommodation_room_id' => $room->id,
                        'photo' => 'uploads/accommodation-rooms/'.$fileName,
                        'description' => $request->photo_descriptions[$index] ?? null,
                        'can_show' => true,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.accommodation-rooms.index')
                ->with('success', 'Room created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating room: ' . $e->getMessage());
        }
    }

    public function show(AccommodationRoom $accommodation_room)
    {
        $accommodation_room->load(['accommodation', 'photos']);
        
        return view('admin.accommodation-rooms.show', compact('accommodation_room'));
    }

    public function edit(AccommodationRoom $accommodation_room)
    {
        $accommodations = Accommodation::where('is_active', true)->get();
        $categories = ['Standard', 'Deluxe', 'Suite', 'Executive', 'Presidential', 'Family', 'Single', 'Double', 'Twin', 'Triple'];
        $facilities = Facility::where('is_active', true)->get();

        $accommodation_room->load(['photos', 'facilities']);

        return view('admin.accommodation-rooms.edit', compact('accommodation_room', 'accommodations', 'categories', 'facilities'));
    }

    public function update(Request $request, AccommodationRoom $accommodation_room)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'unique_number' => [
                'nullable',
                'string',
                'max:100',
                \Illuminate\Validation\Rule::unique('accommodation_rooms')->ignore($accommodation_room->id)
            ],
            'is_active' => 'boolean',
            'currency' => 'required|string|max:10',
            'is_visible' => 'boolean',
            'is_available' => 'boolean',
            'price' => 'required|numeric|min:0',
            'price_duration' => 'required|string|in:per_night,per_week,per_month',
            'description' => 'nullable|string',
            'accommodation_id' => 'required|exists:accommodations,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_descriptions.*' => 'nullable|string|max:255',
            'facility_ids' => 'nullable|array',
            'facility_ids.*' => 'exists:facilities,id',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->all();
            
            // Handle boolean fields
            $data['is_active'] = $request->has('is_active');
            $data['is_visible'] = $request->has('is_visible');
            $data['is_available'] = $request->has('is_available');

            // Handle main photo
            if ($request->hasFile('photo')) {
                // Delete old photo
                if ($accommodation_room->photo) {
                    $oldPhotoPath = public_path($accommodation_room->photo);
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }
                
                $photo = $request->file('photo');
                $fileName = time().'_'.uniqid().'.'.$photo->getClientOriginalExtension();
                $destination = public_path('uploads/accommodation-rooms');
                $photo->move($destination, $fileName);
                $data['photo'] = 'uploads/accommodation-rooms/'.$fileName;
            }

            $accommodation_room->update($data);

            // Handle facilities
            if ($request->has('facility_ids')) {
                $accommodation_room->facilities()->sync($request->facility_ids);
            }

            // Handle additional photos
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $index => $photo) {
                    $fileName = time().'_'.uniqid().'.'.$photo->getClientOriginalExtension();
                    $destination = public_path('uploads/accommodation-rooms');
                    $photo->move($destination, $fileName);
                    
                    AccommodationRoomPhoto::create([
                        'accommodation_room_id' => $accommodation_room->id,
                        'photo' => 'uploads/accommodation-rooms/'.$fileName,
                        'description' => $request->photo_descriptions[$index] ?? null,
                        'can_show' => true,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.accommodation-rooms.index')
                ->with('success', 'Room updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating room: ' . $e->getMessage());
        }
    }

    public function destroy(AccommodationRoom $accommodation_room)
    {
        DB::beginTransaction();

        try {
            // Delete photos from storage
            if ($accommodation_room->photo) {
                $photoPath = public_path($accommodation_room->photo);
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
            }

            // Delete additional photos
            foreach ($accommodation_room->photos as $photo) {
                $photoPath = public_path($photo->photo);
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
                $photo->delete();
            }

            $accommodation_room->delete();

            DB::commit();

            return redirect()->route('admin.accommodation-rooms.index')
                ->with('success', 'Room deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting room: ' . $e->getMessage());
        }
    }

    public function toggleStatus(AccommodationRoom $room)
    {
        $room->update(['is_active' => !$room->is_active]);
        
        return response()->json([
            'success' => true,
            'message' => 'Room status toggled successfully.',
            'is_active' => $room->is_active
        ]);
    }

    public function toggleVisibility(AccommodationRoom $room)
    {
        $room->update(['is_visible' => !$room->is_visible]);
        
        return response()->json([
            'success' => true,
            'message' => 'Room visibility toggled successfully.',
            'is_visible' => $room->is_visible
        ]);
    }

    public function toggleAvailability(AccommodationRoom $room)
    {
        $room->update(['is_available' => !$room->is_available]);
        
        return response()->json([
            'success' => true,
            'message' => 'Room availability toggled successfully.',
            'is_available' => $room->is_available
        ]);
    }

    public function deletePhoto(AccommodationRoomPhoto $photo)
    {
        try {
            $photoPath = public_path($photo->photo);
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
            $photo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Photo deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting photo'
            ], 500);
        }
    }

    public function getRoomsByAccommodation(Request $request)
    {
        $accommodationId = $request->accommodation_id;
        $rooms = AccommodationRoom::where('accommodation_id', $accommodationId)
            ->where('is_active', true)
            ->where('is_available', true)
            ->get();
        
        return response()->json($rooms);
    }
} 