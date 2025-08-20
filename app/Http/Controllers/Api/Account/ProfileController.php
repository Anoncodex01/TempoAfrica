<?php

namespace App\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Profile retrieved successfully',
            'customer' => $request->user(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $customer = $request->user(); // Authenticated customer

        $validation = Validator::make($request->all(), [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'country_id' => 'nullable|integer|exists:countries,id',
            'province_id' => 'nullable|integer|exists:provinces,id',
            'district_id' => 'nullable|integer|exists:districts,id',
            'street_id' => 'nullable|integer|exists:streets,id',
            'gender' => 'nullable|in:Male,Female,Other',
            'email' => 'nullable|email',
            'dob' => 'nullable|date|before:today',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'errors' => $validation->errors()], 422);
        }

        $data = $validation->validated();

        // Exclude phone
        unset($data['phone']);

        // Handle password hash
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if (isset($data['dob'])) {
            $data['dob'] = Carbon::parse($data['dob']);
        }

        // Handle photo upload (if any)
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
            $data['photo'] = $photoPath;
        }

        $customer->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'customer' => $customer,
        ]);
    }
}
