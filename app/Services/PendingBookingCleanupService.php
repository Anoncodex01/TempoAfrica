<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\HouseBooking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PendingBookingCleanupService
{
    /**
     * Clean up expired pending bookings
     *
     * @param int $minutes Minutes after which pending bookings expire (default: 15)
     * @return array
     */
    public static function cleanupExpiredPendingBookings(int $minutes = 15): array
    {
        $cutoffTime = Carbon::now()->subMinutes($minutes);
        
        // Clean up accommodation bookings
        $expiredAccommodationBookings = Booking::where('is_paid', false)
            ->where('created_at', '<', $cutoffTime)
            ->get();

        $accommodationDeletedCount = 0;
        foreach ($expiredAccommodationBookings as $booking) {
            // Log the deletion for audit purposes
            Log::info('Deleting expired pending accommodation booking', [
                'booking_id' => $booking->id,
                'reference' => $booking->reference,
                'customer_id' => $booking->customer_id,
                'room_id' => $booking->accommodation_room_id,
                'created_at' => $booking->created_at,
                'expired_at' => Carbon::now(),
                'expiration_minutes' => $minutes,
            ]);

            $booking->delete();
            $accommodationDeletedCount++;
        }

        // Clean up house bookings
        $expiredHouseBookings = HouseBooking::where('is_paid', false)
            ->where('created_at', '<', $cutoffTime)
            ->get();

        $houseDeletedCount = 0;
        foreach ($expiredHouseBookings as $booking) {
            // Log the deletion for audit purposes
            Log::info('Deleting expired pending house booking', [
                'booking_id' => $booking->id,
                'reference' => $booking->reference,
                'customer_id' => $booking->customer_id,
                'house_id' => $booking->house_id,
                'created_at' => $booking->created_at,
                'expired_at' => Carbon::now(),
                'expiration_minutes' => $minutes,
            ]);

            $booking->delete();
            $houseDeletedCount++;
        }

        $totalDeleted = $accommodationDeletedCount + $houseDeletedCount;

        // Log summary
        Log::info('Pending booking cleanup completed', [
            'total_deleted' => $totalDeleted,
            'accommodation_deleted' => $accommodationDeletedCount,
            'house_deleted' => $houseDeletedCount,
            'expiration_minutes' => $minutes,
            'cutoff_time' => $cutoffTime->format('Y-m-d H:i:s'),
        ]);

        return [
            'total_deleted' => $totalDeleted,
            'accommodation_deleted' => $accommodationDeletedCount,
            'house_deleted' => $houseDeletedCount,
            'cutoff_time' => $cutoffTime,
        ];
    }

    /**
     * Get count of expired pending bookings
     *
     * @param int $minutes Minutes after which pending bookings expire (default: 15)
     * @return array
     */
    public static function getExpiredPendingBookingsCount(int $minutes = 15): array
    {
        $cutoffTime = Carbon::now()->subMinutes($minutes);

        $expiredAccommodationCount = Booking::where('is_paid', false)
            ->where('created_at', '<', $cutoffTime)
            ->count();

        $expiredHouseCount = HouseBooking::where('is_paid', false)
            ->where('created_at', '<', $cutoffTime)
            ->count();

        return [
            'accommodation_count' => $expiredAccommodationCount,
            'house_count' => $expiredHouseCount,
            'total_count' => $expiredAccommodationCount + $expiredHouseCount,
            'cutoff_time' => $cutoffTime,
        ];
    }

    /**
     * Check if a specific booking has expired
     *
     * @param Booking|HouseBooking $booking
     * @param int $minutes Minutes after which pending bookings expire (default: 15)
     * @return bool
     */
    public static function isBookingExpired($booking, int $minutes = 15): bool
    {
        if ($booking->is_paid) {
            return false; // Paid bookings don't expire
        }

        $cutoffTime = Carbon::now()->subMinutes($minutes);
        return $booking->created_at < $cutoffTime;
    }
}
