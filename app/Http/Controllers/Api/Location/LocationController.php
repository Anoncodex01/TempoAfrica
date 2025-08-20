<?php

namespace App\Http\Controllers\Api\Location;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Province;
use App\Models\District;
use App\Models\Street;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function countries()
    {
        try {
            $countries = Country::where('is_established', true)->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Countries retrieved successfully',
                'data' => $countries,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve countries',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function provinces(Request $request)
    {
        try {
            $query = Province::where('is_established', true);
            
            if ($request->has('country_id')) {
                $query->where('country_id', $request->country_id);
            }
            
            $provinces = $query->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Provinces retrieved successfully',
                'data' => $provinces,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve provinces',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function districts(Request $request)
    {
        try {
            $query = District::where('is_established', true);
            
            if ($request->has('province_id')) {
                $query->where('province_id', $request->province_id);
            }
            
            $districts = $query->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Districts retrieved successfully',
                'districts' => $districts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve districts',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function streets(Request $request)
    {
        try {
            $query = Street::where('is_established', true);
            
            if ($request->has('province_id')) {
                $query->where('province_id', $request->province_id);
            }
            
            $streets = $query->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Streets retrieved successfully',
                'streets' => $streets,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve streets',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
} 