<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\AccommodationPhoto;
use App\Models\AccommodationRoom;
use App\Models\Country;
use App\Models\Province;
use App\Models\Street;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AccommodationController extends Controller
{
    public function index(Request $request)
    {
        $query = Accommodation::with(['customer', 'country', 'province', 'street', 'photos']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%")
                  ->orWhere('unique_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
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

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Country filter
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        // Price range filter
        if ($request->filled('price_min')) {
            $query->where('minimum_price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('minimum_price', '<=', $request->price_max);
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $accommodations = $query->paginate(12);

        // Get filter data
        $countries = Country::all();
        $categories = Accommodation::distinct()->pluck('category')->filter();

        return view('admin.accommodations.index', compact('accommodations', 'countries', 'categories'));
    }

    public function create()
    {
        $customers = Customer::all();
        $countries = Country::all();
        $provinces = Province::all();
        $streets = Street::all();
        $categories = ['Hotel', 'Guesthouse', 'Apartment', 'Villa', 'Resort', 'Lodge', 'Hostel', 'Cottage'];

        return view('admin.accommodations.create', compact('customers', 'countries', 'provinces', 'streets', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'registration_number' => 'nullable|string|max:100|unique:accommodations',
            'unique_number' => 'nullable|string|max:100|unique:accommodations',
            'is_visible' => 'boolean',
            'is_featured' => 'boolean',
            'currency' => 'required|string|max:10',
            'minimum_price' => 'required|numeric|min:0',
            'minimum_price_duration' => 'required|string|in:per_night,per_week,per_month',
            'is_approved' => 'boolean',
            'category' => 'required|string|max:100',
            'customer_id' => 'required|exists:customers,id',
            'country_id' => 'required|exists:countries,id',
            'province_id' => 'required|exists:provinces,id',
            'street_id' => 'required|exists:streets,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
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
            $data['is_featured'] = $request->has('is_featured');
            $data['is_approved'] = $request->has('is_approved');

            // Handle main photo
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('accommodations/photos', 'public');
                $data['photo'] = $photoPath;
            }

            $accommodation = Accommodation::create($data);

            // Handle additional photos
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $index => $photo) {
                    $photoPath = $photo->store('accommodations/photos', 'public');
                    
                    AccommodationPhoto::create([
                        'accommodation_id' => $accommodation->id,
                        'photo' => $photoPath,
                        'description' => $request->photo_descriptions[$index] ?? null,
                        'can_show' => true,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.accommodations.index')
                ->with('success', 'Accommodation created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating accommodation: ' . $e->getMessage());
        }
    }

    public function show(Accommodation $accommodation)
    {
        $accommodation->load(['customer', 'country', 'province', 'street', 'photos', 'rooms.photos']);
        
        return view('admin.accommodations.show', compact('accommodation'));
    }

    public function edit(Accommodation $accommodation)
    {
        $customers = Customer::all();
        $countries = Country::all();
        $provinces = Province::all();
        $streets = Street::all();
        $categories = ['Hotel', 'Guesthouse', 'Apartment', 'Villa', 'Resort', 'Lodge', 'Hostel', 'Cottage'];

        $accommodation->load('photos');

        return view('admin.accommodations.edit', compact('accommodation', 'customers', 'countries', 'provinces', 'streets', 'categories'));
    }

    public function update(Request $request, Accommodation $accommodation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'registration_number' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('accommodations')->ignore($accommodation->id)
            ],
            'unique_number' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('accommodations')->ignore($accommodation->id)
            ],
            'is_visible' => 'boolean',
            'is_featured' => 'boolean',
            'currency' => 'required|string|max:10',
            'minimum_price' => 'required|numeric|min:0',
            'minimum_price_duration' => 'required|string|in:per_night,per_week,per_month',
            'is_approved' => 'boolean',
            'category' => 'required|string|max:100',
            'customer_id' => 'required|exists:customers,id',
            'country_id' => 'required|exists:countries,id',
            'province_id' => 'required|exists:provinces,id',
            'street_id' => 'required|exists:streets,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
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
            $data['is_featured'] = $request->has('is_featured');
            $data['is_approved'] = $request->has('is_approved');

            // Handle main photo
            if ($request->hasFile('photo')) {
                // Delete old photo
                if ($accommodation->photo) {
                    Storage::disk('public')->delete($accommodation->photo);
                }
                
                $photoPath = $request->file('photo')->store('accommodations/photos', 'public');
                $data['photo'] = $photoPath;
            }

            $accommodation->update($data);

            // Handle additional photos
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $index => $photo) {
                    $photoPath = $photo->store('accommodations/photos', 'public');
                    
                    AccommodationPhoto::create([
                        'accommodation_id' => $accommodation->id,
                        'photo' => $photoPath,
                        'description' => $request->photo_descriptions[$index] ?? null,
                        'can_show' => true,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.accommodations.index')
                ->with('success', 'Accommodation updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating accommodation: ' . $e->getMessage());
        }
    }

    public function destroy(Accommodation $accommodation)
    {
        DB::beginTransaction();

        try {
            // Delete photos from storage
            if ($accommodation->photo) {
                Storage::disk('public')->delete($accommodation->photo);
            }

            // Delete additional photos
            foreach ($accommodation->photos as $photo) {
                Storage::disk('public')->delete($photo->photo);
            }

            // Delete the accommodation (this will cascade delete related records)
            $accommodation->delete();

            DB::commit();

            return redirect()->route('admin.accommodations.index')
                ->with('success', 'Accommodation deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting accommodation: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Accommodation $accommodation)
    {
        $accommodation->update([
            'is_active' => !$accommodation->is_active
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $accommodation->is_active,
            'message' => 'Status updated successfully'
        ]);
    }

    public function toggleVisibility(Accommodation $accommodation)
    {
        $accommodation->update([
            'is_visible' => !$accommodation->is_visible
        ]);

        return response()->json([
            'success' => true,
            'is_visible' => $accommodation->is_visible,
            'message' => 'Visibility updated successfully'
        ]);
    }

    public function toggleFeatured(Accommodation $accommodation)
    {
        $accommodation->update([
            'is_featured' => !$accommodation->is_featured
        ]);

        return response()->json([
            'success' => true,
            'is_featured' => $accommodation->is_featured,
            'message' => 'Featured status updated successfully'
        ]);
    }

    public function approve(Accommodation $accommodation)
    {
        $accommodation->update([
            'is_approved' => true
        ]);

        return redirect()->route('admin.accommodations.index')
            ->with('success', 'Accommodation approved successfully.');
    }

    public function deletePhoto(AccommodationPhoto $photo)
    {
        try {
            Storage::disk('public')->delete($photo->photo);
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
} 