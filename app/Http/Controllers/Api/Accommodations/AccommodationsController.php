<?php

namespace App\Http\Controllers\Api\Accommodations;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\AccommodationPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AccommodationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except([
            'publicAccommodations',
            'publicAccommodation', 
            'featuredAccommodations',
            'recommendedAccommodations',
            'testAccommodation'
        ]);
    }

    public function storeOrUpdate(Request $request)
    {
        $customer = $request->user();
        
        $validation = Validator::make($request->all(), [
            'id' => 'nullable|exists:accommodations,id', // only required for update
            'name' => 'required|string|max:255',
            'registration_number' => 'required|unique:accommodations,registration_number,' . ($request->input('id') ?? '') . ',id,customer_id,' . $customer->id,
            'currency' => 'required|string|max:10',
            'minimum_price_duration' => 'required|string|max:255',
            'minimum_price' => 'required|numeric|min:0',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'category' => 'required|string|max:100',
            'country_id' => 'required|exists:countries,id',
            'province_id' => 'required|exists:provinces,id',
            'street_id' => 'required|exists:streets,id',
            'photo' => $request->has('id') ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048' : 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors(),
            ], 422);
        }

        $data = $validation->validated();
        $data['customer_id'] = $customer->id;

        $unique_number = $this->uniqueReference($data);

        $data['unique_number'] = $unique_number;

        // Handle file upload if provided
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $fileName = time().'_'.uniqid().'.'.$photo->getClientOriginalExtension();
            $destination = public_path('uploads/accommodations');
            $photo->move($destination, $fileName);
            $data['photo'] = 'uploads/accommodations/'.$fileName;
        } elseif (!empty($data['id'])) {
            // For updates, if no new photo is provided, keep the existing photo
            $existingAccommodation = Accommodation::find($data['id']);
            if ($existingAccommodation && $existingAccommodation->photo) {
                $data['photo'] = $existingAccommodation->photo;
            }
        }

        // Determine whether to create or update
        if (! empty($data['id'])) {
            $accommodation = Accommodation::find($data['id']);
            
            // Security check: ensure user can only update their own properties
            if (!$accommodation || $accommodation->customer_id != $customer->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Property not found or not authorized to update',
                ], 403);
            }
            
            $accommodation->update($data);
            $message = 'Accommodation updated successfully';
        } else {
            $accommodation = Accommodation::create($data);
            $message = 'Accommodation created successfully';
        }

        // Handle facilities
        if ($request->has('facility_ids') && is_array($request->input('facility_ids'))) {
            $facilityIds = $request->input('facility_ids');
            $accommodation->facilities()->sync($facilityIds);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'accommodation' => $accommodation,
        ], 200);
    }

    public function accommodations(Request $request)
    {
        $customer = $request->user();

        $accommodations = Accommodation::where('customer_id', $customer->id)
            ->with(['rooms.photos', 'rooms.facilities', 'photos', 'facilities', 'country', 'province', 'street.district', 'customer'])
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Accommodations',
            'accommodations' => $accommodations,
        ]);
    }

    public function countrySortAccommodations($countryId)
    {
        $accommodations = Accommodation::where('country_id', $countryId)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Accommodations',
            'accommodations' => $accommodations,
        ]);
    }

    public function categorySortAccommodations($category)
    {
        $accommodations = Accommodation::where('category', $category)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Accommodations',
            'accommodations' => $accommodations,
        ]);
    }

    public function testAccommodation($accommodationId)
    {
        $accommodation = Accommodation::with(['rooms.photos', 'photos', 'country', 'province', 'street.district', 'customer'])->findOrFail($accommodationId);

        // Debug logging
        \Log::info('TEST - Accommodation details requested for ID: ' . $accommodationId);
        \Log::info('TEST - Accommodation data: ' . json_encode($accommodation->toArray()));
        \Log::info('TEST - Country ID: ' . $accommodation->country_id);
        \Log::info('TEST - Province ID: ' . $accommodation->province_id);
        \Log::info('TEST - Street ID: ' . $accommodation->street_id);
        \Log::info('TEST - Country: ' . ($accommodation->country ? $accommodation->country->name : 'null'));
        \Log::info('TEST - Province: ' . ($accommodation->province ? $accommodation->province->name : 'null'));
        \Log::info('TEST - Street: ' . ($accommodation->street ? $accommodation->street->name : 'null'));
        \Log::info('TEST - District: ' . ($accommodation->street && $accommodation->street->district ? $accommodation->street->district->name : 'null'));

        return response()->json([
            'success' => true,
            'message' => 'Test Accommodation',
            'accommodation' => $accommodation,
            'debug' => [
                'country_id' => $accommodation->country_id,
                'province_id' => $accommodation->province_id,
                'street_id' => $accommodation->street_id,
                'country_name' => $accommodation->country ? $accommodation->country->name : null,
                'province_name' => $accommodation->province ? $accommodation->province->name : null,
                'street_name' => $accommodation->street ? $accommodation->street->name : null,
                'district_name' => $accommodation->street && $accommodation->street->district ? $accommodation->street->district->name : null,
            ]
        ]);
    }

    public function accommodation(Request $request, $accommodationId)
    {
        $customer = $request->user();
        
        $accommodation = Accommodation::where('customer_id', $customer->id)
            ->with(['rooms.photos', 'rooms.facilities', 'photos', 'facilities', 'country', 'province', 'street.district', 'customer'])
            ->findOrFail($accommodationId);

        // Debug logging
        \Log::info('Accommodation details requested for ID: ' . $accommodationId);
        \Log::info('Accommodation data: ' . json_encode($accommodation->toArray()));
        \Log::info('Country ID: ' . $accommodation->country_id);
        \Log::info('Province ID: ' . $accommodation->province_id);
        \Log::info('Street ID: ' . $accommodation->street_id);
        \Log::info('Country: ' . ($accommodation->country ? $accommodation->country->name : 'null'));
        \Log::info('Province: ' . ($accommodation->province ? $accommodation->province->name : 'null'));
        \Log::info('Street: ' . ($accommodation->street ? $accommodation->street->name : 'null'));
        \Log::info('District: ' . ($accommodation->street && $accommodation->street->district ? $accommodation->street->district->name : 'null'));

        return response()->json([
            'success' => true,
            'message' => 'Accommodations',
            'accommodation' => $accommodation,
        ]);
    }

    public function addUpdatePhoto(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'nullable|exists:accommodation_photos,id',
            'accommodation_id' => 'required|exists:accommodations,id',
            'description' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors(),
            ], 422);
        }

        $data = $validation->validated();

        $customer = $request->user();

        if (! empty($data['id'])) {
            $accommodationPhoto = AccommodationPhoto::with('accommodation')->find($data['id']);
            if (! $accommodationPhoto) {
                $message = 'Accommodation photo found!';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 404);
            }

            if ($accommodationPhoto->accommodation->customer_id != $customer->id) {
                $message = 'Not related to your account found!';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 404);
            }

            if ($accommodationPhoto->photo && file_exists(public_path($accommodationPhoto->photo))) {
                unlink(public_path($accommodationPhoto->photo));
            }

            $accommodationPhoto->delete();

            return response()->json([
                'success' => true,
                'message' => 'Photo deleted successfully.',
            ]);

        }

        $accommodation = Accommodation::find($data['accommodation_id']);

        if (! $accommodation) {
            $message = 'Accommodation not found!';

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 404);
        }

        if ($accommodation->customer_id != $customer->id) {
            $message = 'Not associated to your account!';

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 504);
        }

        $data = $validation->validated();

        // Handle file upload if provided
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $fileName = time().'_'.uniqid().'.'.$photo->getClientOriginalExtension();
            $destination = public_path('uploads/accommodations-photos');
            $photo->move($destination, $fileName);
            $data['photo'] = 'uploads/accommodations-photos/'.$fileName;
        }

        $accommodationPhoto = AccommodationPhoto::create($data);
        $message = 'Photo uploaded successfully';

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $accommodationPhoto,
        ], 200);
    }

    public function uniqueReference($data)
    {
        $name = Str::upper(Str::slug($data['name'], '')); // sanitize name
        $nameLetters = str_split(Str::padRight(Str::substr($name, 0, 4), 4, 'X')); // ensure 4 letters
        $unique_number =
            $data['customer_id'].$nameLetters[0].'-'.
            $data['country_id'].$nameLetters[1].'-'.
            $data['province_id'].$nameLetters[2].'-'.
            $data['street_id'].$nameLetters[3].'-'.
            now()->format('Ymd');

        return $unique_number;
    }

    // Public methods for browsing accommodations (no auth required)
    public function publicAccommodations(Request $request)
    {
        $accommodations = Accommodation::where('is_visible', true)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->with(['rooms.photos', 'rooms.facilities', 'photos', 'facilities', 'country', 'province', 'street.district', 'customer'])
            ->get();

        // Debug logging for location data
        \Log::info('PUBLIC - Fetching public accommodations. Count: ' . $accommodations->count());
        foreach ($accommodations as $index => $accommodation) {
            \Log::info("PUBLIC - Accommodation {$index}: {$accommodation->name}");
            \Log::info("PUBLIC - Accommodation {$index} - Country: " . ($accommodation->country ? $accommodation->country->name : 'null'));
            \Log::info("PUBLIC - Accommodation {$index} - Province: " . ($accommodation->province ? $accommodation->province->name : 'null'));
            \Log::info("PUBLIC - Accommodation {$index} - Street: " . ($accommodation->street ? $accommodation->street->name : 'null'));
            \Log::info("PUBLIC - Accommodation {$index} - District: " . ($accommodation->street && $accommodation->street->district ? $accommodation->street->district->name : 'null'));
        }

        return response()->json([
            'success' => true,
            'message' => 'Public Accommodations',
            'accommodations' => $accommodations,
        ]);
    }

    public function publicAccommodation($accommodationId)
    {
        $accommodation = Accommodation::where('id', $accommodationId)
            ->where('is_visible', true)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->with(['rooms.photos', 'rooms.facilities', 'photos', 'facilities', 'country', 'province', 'street.district', 'customer'])
            ->first();

        if (!$accommodation) {
            return response()->json([
                'success' => false,
                'message' => 'Accommodation not found',
            ], 404);
        }

        // Debug logging for location data
        \Log::info('PUBLIC - Accommodation details requested for ID: ' . $accommodationId);
        \Log::info('PUBLIC - Country ID: ' . $accommodation->country_id);
        \Log::info('PUBLIC - Province ID: ' . $accommodation->province_id);
        \Log::info('PUBLIC - Street ID: ' . $accommodation->street_id);
        \Log::info('PUBLIC - Country: ' . ($accommodation->country ? $accommodation->country->name : 'null'));
        \Log::info('PUBLIC - Province: ' . ($accommodation->province ? $accommodation->province->name : 'null'));
        \Log::info('PUBLIC - Street: ' . ($accommodation->street ? $accommodation->street->name : 'null'));
        \Log::info('PUBLIC - District: ' . ($accommodation->street && $accommodation->street->district ? $accommodation->street->district->name : 'null'));

        return response()->json([
            'success' => true,
            'message' => 'Public Accommodation',
            'accommodation' => $accommodation,
            'debug' => [
                'country_id' => $accommodation->country_id,
                'province_id' => $accommodation->province_id,
                'street_id' => $accommodation->street_id,
                'country_name' => $accommodation->country ? $accommodation->country->name : null,
                'province_name' => $accommodation->province ? $accommodation->province->name : null,
                'street_name' => $accommodation->street ? $accommodation->street->name : null,
                'district_name' => $accommodation->street && $accommodation->street->district ? $accommodation->street->district->name : null,
            ]
        ]);
    }

    public function featuredAccommodations(Request $request)
    {
        $accommodations = Accommodation::where('is_visible', true)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->where('is_featured', true)
            ->with(['rooms.photos', 'rooms.facilities', 'photos', 'facilities', 'country', 'province', 'street.district', 'customer'])
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Featured Accommodations',
            'accommodations' => $accommodations,
        ]);
    }

    public function recommendedAccommodations(Request $request)
    {
        $accommodations = Accommodation::where('is_active', true)
            ->where('is_visible', true)
            ->where('is_approved', true)
            ->with(['rooms.photos', 'rooms.facilities', 'photos', 'facilities', 'country', 'province', 'street.district', 'customer'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Recommended Accommodations',
            'accommodations' => $accommodations,
        ]);
    }
}
