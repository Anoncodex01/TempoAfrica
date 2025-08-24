<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\PendingBookingCleanupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookingCleanupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Manually trigger cleanup of expired pending bookings
     */
    public function cleanupExpiredPendingBookings(Request $request)
    {
        try {
            $minutes = $request->get('minutes', 15);
            
            Log::info('Manual cleanup of expired pending bookings triggered', [
                'minutes' => $minutes,
                'triggered_by' => $request->user()->id ?? 'unknown',
            ]);

            $result = PendingBookingCleanupService::cleanupExpiredPendingBookings($minutes);

            return response()->json([
                'success' => true,
                'message' => "Successfully cleaned up {$result['total_deleted']} expired pending bookings",
                'data' => $result,
            ]);

        } catch (\Exception $e) {
            Log::error('Error during manual cleanup of expired pending bookings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error during cleanup: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get count of expired pending bookings
     */
    public function getExpiredPendingBookingsCount(Request $request)
    {
        try {
            $minutes = $request->get('minutes', 15);
            
            $result = PendingBookingCleanupService::getExpiredPendingBookingsCount($minutes);

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting expired pending bookings count', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error getting count: ' . $e->getMessage(),
            ], 500);
        }
    }
}
