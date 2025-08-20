<?php

namespace App\Http\Controllers\Api\Houses;

use App\Http\Controllers\Controller;
use App\Models\House;
use App\Models\HousePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HousesController extends Controller
{
    public function __construct()
    {
        // Only apply auth middleware to methods that need it
        $this->middleware('auth:api')->except([
            'publicHouses',
            'publicHouse',
            'featuredHouses',
            'recommendedHouses'
        ]);
    }

    public function storeOrUpdate(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'nullable|exists:houses,id', // only required for update
            'name' => 'required|string|max:255',
            'registration_number' => 'required|string|max:100',
            'currency' => 'required|string|max:10',
            'price_duration' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'fee' => 'required|numeric|min:0',
            'minimum_rent_duration' => 'required|string|max:50',
            'booking_price' => 'required|numeric|min:0',
            'number_of_rooms' => 'required|integer|min:1',
            'has_water' => 'boolean',
            'has_electricity' => 'boolean',
            'has_fence' => 'boolean',
            'has_public_transport' => 'boolean',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|max:100',
            'country_id' => 'required|string',
            'province_id' => 'required|string',
            'district_id' => 'required|string',
            'street_id' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'photo' => $request->has('id') ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048' : 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'photo_descriptions.*' => 'nullable|string|max:255',
            'facility_ids' => 'nullable|array',
            'facility_ids.*' => 'exists:facilities,id',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors(),
            ], 422);
        }

        $data = $validation->validated();

        // Debug: Log the received data
        \Log::info('House creation data:', $request->all());

        $customer = $request->user();
        $data['customer_id'] = $customer->id;

        // Generate unique number if not provided
        if (empty($data['unique_number'])) {
            $data['unique_number'] = $this->uniqueReference($data);
        }

        // Handle boolean fields - convert to proper boolean values
        $data['has_water'] = $request->input('has_water', false) ? true : false;
        $data['has_electricity'] = $request->input('has_electricity', false) ? true : false;
        $data['has_fence'] = $request->input('has_fence', false) ? true : false;
        $data['has_public_transport'] = $request->input('has_public_transport', false) ? true : false;

        // Set default values for required fields
        $data['is_active'] = true;
        $data['is_visible'] = true;
        $data['is_booked'] = false;
        $data['is_approved'] = false;
        $data['is_featured'] = false;

        // Handle main photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $fileName = time().'_'.uniqid().'.'.$photo->getClientOriginalExtension();
            $destination = public_path('uploads/houses');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            $photo->move($destination, $fileName);
            $data['photo'] = 'uploads/houses/'.$fileName;
        } elseif (!empty($data['id'])) {
            // Keep existing photo if no new one uploaded during update
            $existingHouse = House::find($data['id']);
            if ($existingHouse && $existingHouse->photo) {
                $data['photo'] = $existingHouse->photo;
            }
        }

        // Determine whether to create or update
        try {
            if (!empty($data['id'])) {
                $house = House::find($data['id']);
                $house->update($data);
                $message = 'House updated successfully';
            } else {
                $house = House::create($data);
                $message = 'House created successfully';
            }
        } catch (\Exception $e) {
            \Log::error('House creation error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save house: ' . $e->getMessage(),
                'data' => $data,
            ], 500);
        }

        // Handle additional photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $fileName = time().'_'.uniqid().'.'.$photo->getClientOriginalExtension();
                $destination = public_path('uploads/houses');
                $photo->move($destination, $fileName);
                
                HousePhoto::create([
                    'house_id' => $house->id,
                    'photo' => 'uploads/houses/'.$fileName,
                    'description' => $request->photo_descriptions[$index] ?? null,
                    'can_show' => true,
                ]);
            }
        }

        // Handle facilities
        if ($request->has('facility_ids')) {
            $house->facilities()->sync($request->facility_ids);
        }

        // Load relationships for response
        $house->load(['photos', 'facilities', 'country', 'province', 'district', 'street']);

        return response()->json([
            'success' => true,
            'message' => $message,
            'house' => $house,
        ], 200);
    }

    // Public methods (no authentication required)
    public function publicHouses()
    {
        $houses = House::where('is_active', true)
            ->where('is_visible', true)
            ->with(['photos', 'facilities', 'country', 'province', 'district', 'street'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $houses,
        ]);
    }

    public function publicHouse($houseId)
    {
        $house = House::where('id', $houseId)
            ->where('is_active', true)
            ->where('is_visible', true)
            ->with(['photos', 'facilities', 'country', 'province', 'district', 'street', 'customer'])
            ->first();

        if (!$house) {
            return response()->json([
                'success' => false,
                'message' => 'House not found',
            ], 404);
        }

        // Debug: Log the house data and relationships
        \Log::info('House data for ID ' . $houseId . ':', [
            'house_id' => $house->id,
            'house_name' => $house->name,
            'country_id' => $house->country_id,
            'province_id' => $house->province_id,
            'district_id' => $house->district_id,
            'street_id' => $house->street_id,
            'country' => $house->country,
            'province' => $house->province,
            'district' => $house->district,
            'street' => $house->street,
        ]);

        return response()->json([
            'success' => true,
            'data' => $house,
        ]);
    }

    public function featuredHouses()
    {
        $houses = House::where('is_active', true)
            ->where('is_visible', true)
            ->where('is_featured', true)
            ->with(['photos', 'facilities', 'country', 'province', 'district', 'street'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $houses,
        ]);
    }

    public function recommendedHouses()
    {
        $houses = House::where('is_active', true)
            ->where('is_visible', true)
            ->where('is_approved', true)
            ->with(['photos', 'facilities', 'country', 'province', 'district', 'street'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $houses,
        ]);
    }

    // Private methods (require authentication)
    public function houses(Request $request)
    {
        $customer = $request->user();

        $houses = House::where('customer_id', $customer->id)
            ->with(['photos', 'facilities', 'country', 'province', 'district', 'street'])
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Houses retrieved successfully',
            'houses' => $houses,
        ]);
    }

    public function provinceSortHouses($provinceId)
    {
        $houses = House::where('province_id', $provinceId)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Houses',
            'houses' => $houses,
        ]);
    }

    public function districtSortHouses($districtId)
    {
        $houses = House::where('district_id', $districtId)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Houses',
            'houses' => $houses,
        ]);
    }

    public function categorySortHouses($category)
    {
        $houses = House::where('category', $category)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Houses',
            'houses' => $houses,
        ]);
    }

    public function house(Request $request, $houseId)
    {
        $customer = $request->user();
        
        $house = House::where('id', $houseId)
            ->where('customer_id', $customer->id)
            ->with(['photos', 'facilities', 'country', 'province', 'district', 'street'])
            ->first();

        if (!$house) {
            return response()->json([
                'success' => false,
                'message' => 'House not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'House details retrieved successfully',
            'house' => $house,
        ]);
    }

    public function deleteHouse(Request $request, $houseId)
    {
        $customer = $request->user();
        
        $house = House::where('id', $houseId)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$house) {
            return response()->json([
                'success' => false,
                'message' => 'House not found',
            ], 404);
        }

        // Delete associated photos
        $housePhotos = HousePhoto::where('house_id', $house->id)->get();
        foreach ($housePhotos as $photo) {
            if ($photo->photo && file_exists(public_path($photo->photo))) {
                unlink(public_path($photo->photo));
            }
            $photo->delete();
        }

        // Delete main photo
        if ($house->photo && file_exists(public_path($house->photo))) {
            unlink(public_path($house->photo));
        }

        // Delete the house
        $house->delete();

        return response()->json([
            'success' => true,
            'message' => 'House deleted successfully',
        ]);
    }

    public function addUpdatePhoto(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'nullable|exists:house_photos,id',
            'house_id' => 'required|exists:houses,id',
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
            $housePhoto = HousePhoto::with('house')->find($data['id']);
            if (! $housePhoto) {
                $message = 'House photo found!';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 404);
            }

            if ($housePhoto->house->customer_id != $customer->id) {
                $message = 'Not related to your account found!';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 404);
            }

            if ($housePhoto->photo && file_exists(public_path($housePhoto->photo))) {
                unlink(public_path($housePhoto->photo));
            }

            $housePhoto->delete();

            return response()->json([
                'success' => true,
                'message' => 'Photo deleted successfully.',
            ]);

        }

        $house = House::find($data['house_id']);

        if (! $house) {
            $message = 'House not found!';

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 404);
        }

        if ($house->customer_id != $customer->id) {
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
            $destination = public_path('uploads/houses-photos');
            $photo->move($destination, $fileName);
            $data['photo'] = 'uploads/houses-photos/'.$fileName;
        }

        $housePhoto = HousePhoto::create($data);
        $message = 'Photo uploaded successfully';

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $housePhoto,
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
}
