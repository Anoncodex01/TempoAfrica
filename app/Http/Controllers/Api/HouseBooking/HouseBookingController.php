<?php

namespace App\Http\Controllers\Api\HouseBooking;

use App\Http\Controllers\Controller;
use App\Models\House;
use App\Models\HouseBooking;
use App\Services\DPOPaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HouseBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Book a house (pay for information access)
     */
    public function bookHouse(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'house_id' => 'required|integer|exists:houses,id',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors(),
            ], 422);
        }

        $data = $validation->validated();
        $customer = $request->user();

        // Check if customer profile is complete
        $missingFields = [];
        if (empty($customer->country_id)) {
            $missingFields[] = 'country';
        }
        if (empty($customer->province_id)) {
            $missingFields[] = 'province';
        }
        if (empty($customer->email)) {
            $missingFields[] = 'email';
        }
        if (empty($customer->dob)) {
            $missingFields[] = 'date of birth';
        }

        if (!empty($missingFields)) {
            $missingText = implode(', ', $missingFields);
            return response()->json([
                'success' => false,
                'message' => 'Please complete your profile. Missing: ' . $missingText . '.',
            ], 422);
        }

        // Check if user already has an unpaid booking less than 30 minutes old
        $hasPendingBooking = HouseBooking::where('customer_id', $customer->id)
            ->where('is_paid', false)
            ->where('created_at', '>', now()->subMinutes(30))
            ->exists();

        if ($hasPendingBooking) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a pending house booking. Please complete the payment or wait for it to expire.',
            ], 409);
        }

        // Get the house
        $house = House::findOrFail($data['house_id']);
        
        // Check if house is active and available
        if (!$house->is_active || $house->is_booked) {
            return response()->json([
                'success' => false,
                'message' => 'This house is not available for information access.',
            ], 409);
        }

        // Create house booking
        $houseBooking = new HouseBooking();
        $houseBooking->house_id = $house->id;
        $houseBooking->customer_id = $customer->id;
        $houseBooking->reference = $this->uniqueReference();
        $houseBooking->currency = 'TZS';
        $houseBooking->price = $house->booking_price ?? 1000; // Default fee for information access
        $houseBooking->amount = $house->booking_price ?? 1000;
        $houseBooking->is_paid = false;
        $houseBooking->save();

        // Generate payment URL using DPO service
        $dpoService = new DPOPaymentService();
        $paymentResult = $dpoService->prepareHousePaymentPayload($houseBooking, $customer);

        if ($paymentResult['success']) {
            return response()->json([
                'success' => true,
                'message' => 'House booking created successfully. Please complete the payment to access landlord information.',
                'house_booking' => $houseBooking->load('house'),
                'paymentUrl' => $paymentResult['url'],
            ]);
        } else {
            // Delete the booking if payment creation failed
            $houseBooking->delete();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment. Please try again.',
            ], 500);
        }
    }

    /**
     * Get user's house bookings
     */
    public function getUserHouseBookings(Request $request)
    {
        $customer = $request->user();
        
        $houseBookings = HouseBooking::where('customer_id', $customer->id)
            ->with(['house' => function ($query) {
                $query->select('id', 'name', 'photo', 'customer_id');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'house_bookings' => $houseBookings,
        ]);
    }

    /**
     * Get house owner information after payment
     */
    public function getHouseOwnerInfo(Request $request, $houseId)
    {
        $customer = $request->user();
        
        // Check if customer has paid for this house
        $houseBooking = HouseBooking::where('customer_id', $customer->id)
            ->where('house_id', $houseId)
            ->where('is_paid', true)
            ->first();

        if (!$houseBooking) {
            return response()->json([
                'success' => false,
                'message' => 'Payment required to access house owner information.',
            ], 403);
        }

        $house = House::with('customer')->findOrFail($houseId);
        
        if (!$house) {
            return response()->json([
                'success' => false,
                'message' => 'House not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'owner' => [
                'id' => $house->customer->id,
                'first_name' => $house->customer->first_name,
                'last_name' => $house->customer->last_name,
                'email' => $house->customer->email,
                'phone' => $house->customer->phone,
                'display_name' => $house->customer->first_name . ' ' . $house->customer->last_name,
            ],
            'message' => 'House owner information retrieved successfully.',
        ]);
    }

    /**
     * Update house booking
     */
    public function updateHouseBooking(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'house_booking_id' => 'required|integer|exists:house_bookings,id',
            'type' => 'required|string|in:payment_success,payment_failed',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors(),
            ], 422);
        }

        $data = $validation->validated();
        $customer = $request->user();

        $houseBooking = HouseBooking::where('id', $data['house_booking_id'])
            ->where('customer_id', $customer->id)
            ->first();

        if (!$houseBooking) {
            return response()->json([
                'success' => false,
                'message' => 'House booking not found.',
            ], 404);
        }

        if ($data['type'] === 'payment_success') {
            $houseBooking->is_paid = true;
            $houseBooking->paid_at = now();
            $houseBooking->save();

            return response()->json([
                'success' => true,
                'message' => 'Payment successful. You can now access house owner information.',
                'house_booking' => $houseBooking->load('house'),
            ]);
        } else {
            // Payment failed - delete the booking
            $houseBooking->delete();

            return response()->json([
                'success' => true,
                'message' => 'Payment failed. House booking has been cancelled.',
            ]);
        }
    }

    /**
     * Clear pending house bookings
     */
    public function clearPendingHouseBookings(Request $request)
    {
        $customer = $request->user();

        // Delete pending bookings older than 30 minutes
        $deletedCount = HouseBooking::where('customer_id', $customer->id)
            ->where('is_paid', false)
            ->where('created_at', '<', now()->subMinutes(30))
            ->delete();

        return response()->json([
            'success' => true,
            'message' => "Cleared $deletedCount expired pending house bookings.",
        ]);
    }

    /**
     * Generate unique reference
     */
    private function uniqueReference(): string
    {
        do {
            $reference = 'HB' . strtoupper(Str::random(8));
        } while (HouseBooking::where('reference', $reference)->exists());

        return $reference;
    }

    /**
     * Get real owner information directly from customers table using customer_id
     */
    public function getRealOwnerInfo($customerId)
    {
        try {
            $customer = request()->user();
            
            // Import Customer model
            $owner = \App\Models\Customer::find($customerId);
            
            if (!$owner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Owner information not found.',
                ], 404);
            }

            // Check if the requesting user has paid for any house owned by this customer
            $hasPaidForOwnerHouse = HouseBooking::where('customer_id', $customer->id)
                ->whereHas('house', function($query) use ($customerId) {
                    $query->where('customer_id', $customerId);
                })
                ->where('is_paid', true)
                ->exists();

            if (!$hasPaidForOwnerHouse) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment required to view owner details.',
                ], 403);
            }

            return response()->json([
                'success' => true,
                'owner' => [
                    'id' => $owner->id,
                    'first_name' => $owner->first_name,
                    'last_name' => $owner->last_name,
                    'email' => $owner->email,
                    'phone' => $owner->phone,
                    'is_verified' => $owner->is_verified,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting real owner info: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load owner information.',
            ], 500);
        }
    }
}
