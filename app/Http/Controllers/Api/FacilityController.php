<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /**
     * Get all active facilities
     */
    public function index()
    {
        try {
            $facilities = Facility::active()->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Facilities retrieved successfully',
                'data' => $facilities,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve facilities',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get facilities for a specific accommodation
     */
    public function getAccommodationFacilities($accommodationId)
    {
        try {
            $facilities = Facility::whereHas('accommodations', function ($query) use ($accommodationId) {
                $query->where('accommodation_id', $accommodationId);
            })->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Accommodation facilities retrieved successfully',
                'data' => $facilities,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve accommodation facilities',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get facilities for a specific room
     */
    public function getRoomFacilities($roomId)
    {
        try {
            $facilities = Facility::whereHas('accommodationRooms', function ($query) use ($roomId) {
                $query->where('accommodation_room_id', $roomId);
            })->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Room facilities retrieved successfully',
                'data' => $facilities,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve room facilities',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
} 