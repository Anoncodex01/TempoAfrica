<?php

namespace App\Http\Controllers\Api\Accommodations;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\AccommodationRoom;
use App\Models\AccommodationRoomPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RoomsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function storeOrUpdate(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'nullable|exists:accommodation_rooms,id', // only required for update
            'accommodation_id' => 'required|exists:accommodations,id',
            'name' => 'required|string|max:255',
            'currency' => 'required|string|max:10',
            'price_duration' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
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

        if (! empty($data['accommodation_id'])) {
            $accommodation = Accommodation::find($data['accommodation_id']);
            if (! $accommodation) {
                $message = 'Accommodation found!';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 404);
            }

            if ($accommodation->customer_id != $customer->id) {
                $message = 'Not related to your account found!';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 404);
            }
        }

        $data['customer_id'] = $customer->id;
        $data['accommodation_id'] = $accommodation->id;
        $unique_number = $this->uniqueReference($data);

        $data['unique_number'] = $unique_number;

        // Handle file upload if provided
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $fileName = time().'_'.uniqid().'.'.$photo->getClientOriginalExtension();
            $destination = public_path('uploads/accommodation-rooms');
            $photo->move($destination, $fileName);
            $data['photo'] = 'uploads/accommodation-rooms/'.$fileName;
        }

        // Determine whether to create or update
        if (! empty($data['id'])) {
            $accommodation = AccommodationRoom::find($data['id']);
            $accommodation->update($data);
            $message = 'Room updated successfully';
        } else {
            $accommodation = AccommodationRoom::create($data);
            $message = 'Room created successfully';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $accommodation,
        ], 200);
    }

    public function rooms(Request $request, $accommodationId)
    {
        $customer = $request->user();

        $rooms = AccommodationRoom::where('accommodation_id', $accommodationId)
            ->where('is_visible', true)
            ->where('is_active', true)
            ->with('photos')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Rooms',
            'rooms' => $rooms,
        ]);
    }

    public function room($roomId)
    {
        $room = AccommodationRoom::with('photos')->findOrFail($roomId);

        return response()->json([
            'success' => true,
            'message' => 'Room',
            'room' => $room,
        ]);
    }

    public function addUpdatePhoto(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'nullable|exists:accommodation_photos,id',
            'accommodation_room_id' => 'required|exists:accommodation_rooms,id',
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
            $accommodationPhoto = AccommodationRoomPhoto::with('accommodation')->find($data['id']);
            if (! $accommodationPhoto) {
                $message = 'AccommodationRoom photo found!';

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

        $accommodationRoom = AccommodationRoom::with('accommodation')->find($data['accommodation_room_id']);

        if (! $accommodationRoom) {
            $message = 'AccommodationRoom not found!';
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 404);
        }

        if ($accommodationRoom->accommodation->customer_id != $customer->id) {
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
            $destination = public_path('uploads/accommodation-rooms');
            $photo->move($destination, $fileName);
            $data['photo'] = 'uploads/accommodation-rooms/'.$fileName;
        }

        $accommodationPhoto = AccommodationRoomPhoto::create($data);
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
            $data['accommodation_id'].$nameLetters[0].'-'.
            $data['customer_id'].$nameLetters[1].'-'.
            now()->format('Ymd');

        return $unique_number;
    }
}
