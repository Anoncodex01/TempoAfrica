<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Accommodation;
use App\Models\AccommodationRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OwnerBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Test endpoint to verify authentication
     */
    public function testAuth(Request $request)
    {
        try {
            $user = Auth::user();
            \Log::info('Test auth called - User ID: ' . $user->id);
            return response()->json([
                'success' => true,
                'message' => 'Authentication working',
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);
        } catch (\Exception $e) {
            \Log::error('Test auth error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Authentication failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all bookings for properties owned by the current user
     */
    public function getOwnerBookings(Request $request)
    {
        try {
            $user = Auth::user();
            \Log::info('Owner bookings request - User ID: ' . $user->id);
            
            // Simple test first - just return user info
            return response()->json([
                'success' => true,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'bookings' => [],
                'message' => 'Basic test successful'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching owner bookings: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching bookings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get booking statistics for the owner
     */
    public function getOwnerBookingStats(Request $request)
    {
        try {
            $user = Auth::user();
            \Log::info('Owner booking stats request - User ID: ' . $user->id);
            
            // Simple test - return default stats
            $stats = [
                'total_bookings' => 0,
                'pending_bookings' => 0,
                'paid_bookings' => 0,
                'checked_in_bookings' => 0,
                'checked_out_bookings' => 0,
                'cancelled_bookings' => 0,
                'total_revenue' => 0,
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'message' => 'Statistics retrieved successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching owner booking stats: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detailed booking information
     */
    public function getBookingDetails(Request $request, $bookingId)
    {
        try {
            $user = Auth::user();
            
            // Get all properties owned by this user
            $ownedProperties = Accommodation::where('customer_id', $user->id)->pluck('id');
            
            // Get the booking with customer information
            $booking = Booking::with(['accommodation', 'accommodationRoom'])
                ->where('id', $bookingId)
                ->whereIn('accommodation_id', $ownedProperties)
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'booking' => $booking,
                'message' => 'Booking details retrieved successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching booking details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching booking details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update booking status (check-in, check-out, cancel)
     */
    public function updateBookingStatus(Request $request, $bookingId, $action)
    {
        try {
            $user = Auth::user();
            
            // Get all properties owned by this user
            $ownedProperties = Accommodation::where('customer_id', $user->id)->pluck('id');
            
            // Get the booking
            $booking = Booking::where('id', $bookingId)
                ->whereIn('accommodation_id', $ownedProperties)
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            // Update based on action
            switch ($action) {
                case 'check_in':
                    if ($booking->is_checked_in) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Booking is already checked in'
                        ], 400);
                    }
                    
                    $booking->update([
                        'is_checked_in' => true,
                        'checked_in_at' => now()
                    ]);
                    
                    $message = 'Guest checked in successfully';
                    break;

                case 'check_out':
                    if (!$booking->is_checked_in) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Guest must be checked in first'
                        ], 400);
                    }
                    
                    if ($booking->is_checked_out) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Guest is already checked out'
                        ], 400);
                    }
                    
                    $booking->update([
                        'is_checked_out' => true,
                        'checked_out_at' => now()
                    ]);
                    
                    $message = 'Guest checked out successfully';
                    break;

                case 'cancel':
                    if ($booking->is_checked_in) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Cannot cancel checked-in booking'
                        ], 400);
                    }
                    
                    if ($booking->is_cancelled) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Booking is already cancelled'
                        ], 400);
                    }
                    
                    $booking->update([
                        'is_cancelled' => true
                    ]);
                    
                    $message = 'Booking cancelled successfully';
                    break;

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid action'
                    ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'booking' => $booking->fresh()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating booking status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating booking status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 