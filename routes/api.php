<?php

use App\Http\Controllers\Api\Accommodations\AccommodationsController;
use App\Http\Controllers\Api\Accommodations\RoomsController;
use App\Http\Controllers\Api\Account\ProfileController;
use App\Http\Controllers\Api\Auth\AuthenticationController;
use App\Http\Controllers\Api\Booking\BookingController;
use App\Http\Controllers\Api\Houses\HousesController;
use App\Http\Controllers\Api\HouseBooking\HouseBookingController;
use App\Http\Controllers\Api\Payments\PaymentCallBackController;
use App\Http\Controllers\Api\Location\LocationController;
use App\Http\Controllers\Api\FacilityController;
use App\Http\Controllers\Api\Owner\OwnerBookingController;

use Illuminate\Support\Facades\Route;

Route::controller(AuthenticationController::class)->group(function () {
    Route::post('customer/register', 'register')->name('register');
    Route::post('customer/opt-verification', 'otpVerification')->name('otpVerification');
    Route::post('customer/login', 'login')->name('login');
});

// Location routes (no auth required)
Route::controller(LocationController::class)->group(function () {
    Route::get('countries', 'countries');
    Route::get('provinces', 'provinces');
    Route::get('districts', 'districts');
    Route::get('streets', 'streets');
});

// Facility routes (no auth required)
Route::controller(FacilityController::class)->group(function () {
    Route::get('facilities', 'index');
    Route::get('accommodation/{id}/facilities', 'getAccommodationFacilities');
    Route::get('room/{id}/facilities', 'getRoomFacilities');
});

// Test route for accommodation data (no auth required)
Route::get('test/accommodation/{id}', [AccommodationsController::class, 'testAccommodation']);

// Public accommodation browsing routes (no auth required)
Route::controller(AccommodationsController::class)->group(function () {
    Route::get('accommodations', 'publicAccommodations');
    Route::get('accommodation/{id}', 'publicAccommodation');
    Route::get('featured-accommodations', 'featuredAccommodations');
    Route::get('recommended-accommodations', 'recommendedAccommodations');
});

// Public room browsing routes (no auth required)
Route::controller(RoomsController::class)->group(function () {
    Route::get('public/rooms/{accommodationId}', 'publicRooms');
    Route::get('public/room/{id}', 'publicRoom');
    Route::post('public/room/{id}/check-availability', 'checkRoomAvailability');
    Route::get('public/room/{id}/availability-calendar', 'getRoomAvailabilityCalendar');
});

// Public house browsing routes (no auth required)
Route::controller(HousesController::class)->group(function () {
    Route::get('houses', 'publicHouses');
    Route::get('house/{id}', 'publicHouse');
    Route::get('featured-houses', 'featuredHouses');
    Route::get('recommended-houses', 'recommendedHouses');
});

Route::middleware('auth:api')->group(function () {
    Route::controller(ProfileController::class)->group(function () {
        Route::get('customer/profile', 'profile');
        Route::post('customer/update-profile', 'updateProfile');
    });

    Route::controller(AccommodationsController::class)->group(function () {
        Route::post('customer/accommodation-add', 'storeOrUpdate');
        Route::post('customer/accommodation-update-photo', 'addUpdatePhoto');
        Route::get('customer/accommodations', 'accommodations');
        Route::get('customer/accommodation/{id}', 'accommodation');
        Route::get('customer/sort-country-accommodation/{country}', 'countrySortAccommodations');
        Route::get('customer/sort-category-accommodation/{category}', 'categorySortAccommodations');

    });

    Route::controller(RoomsController::class)->group(function () {
        Route::post('customer/room-add', 'storeOrUpdate');
        Route::post('customer/room-update-photo', 'addUpdatePhoto');
        Route::get('customer/rooms/{accommodation}', 'rooms');
        Route::get('customer/room/{id}', 'room');
        Route::delete('customer/room/{id}', 'deleteRoom');
        Route::post('customer/room-toggle-status', 'toggleRoomStatus');
        Route::get('customer/book-room', 'bookRoom');

    });

    Route::controller(HousesController::class)->group(function () {
        Route::post('customer/house-add', 'storeOrUpdate');
        Route::post('customer/house-update-photo', 'addUpdatePhoto');
        Route::get('customer/houses', 'houses');
        Route::get('customer/house/{id}', 'house');
        Route::delete('customer/house/{id}', 'deleteHouse');
        Route::get('customer/sort-region-house/{region}', 'provinceSortHouses');
        Route::get('customer/sort-district-house/{district}', 'districtSortHouses');
        Route::get('customer/sort-category-house/{category}', 'categorySortHouses');

    });

    Route::controller(BookingController::class)->group(function () {
    Route::post('customer/booking/book-room', 'bookRoom');
    Route::post('customer/booking/update-booking', 'updateBooking');
    Route::post('customer/booking/clear-pending', 'clearPendingBooking');
    Route::get('customer/booking/{id}/receipt', 'getReceipt');
    Route::get('customer/booking/{id}/payment-url', 'getPaymentUrl');
    Route::get('customer/bookings', 'getUserBookings');
    Route::get('customer/booking/{id}', 'getBookingDetails');
    
});

    // House booking routes
    Route::controller(HouseBookingController::class)->group(function () {
        Route::post('customer/house-booking/book-house', 'bookHouse');
        Route::get('customer/house-bookings', 'getUserHouseBookings');
        Route::get('customer/house-booking/owner-info/{houseId}', 'getHouseOwnerInfo');
        Route::get('customer/house/{houseId}/owner-details', 'getHouseOwnerInfo'); // Add this route for owner details
        Route::get('customer/owner/{customerId}', 'getRealOwnerInfo'); // Add this route for real owner info
        Route::post('customer/house-booking/update', 'updateHouseBooking');
        Route::post('customer/house-booking/clear-pending', 'clearPendingHouseBookings');
    });

    // Owner booking management routes
    Route::controller(OwnerBookingController::class)->group(function () {
        Route::get('owner/test-auth', 'testAuth');
        Route::get('owner/bookings', 'getOwnerBookings');
        Route::get('owner/booking-stats', 'getOwnerBookingStats');
        Route::get('owner/bookings/{id}', 'getBookingDetails');
        Route::post('owner/bookings/{id}/check_in', 'updateBookingStatus'); 
        Route::post('owner/bookings/{id}/check_out', 'updateBookingStatus');
        Route::post('owner/bookings/{id}/cancel', 'updateBookingStatus');
    });

    // Admin booking cleanup routes
    Route::controller(\App\Http\Controllers\Api\Admin\BookingCleanupController::class)->group(function () {
        Route::post('admin/cleanup-expired-pending-bookings', 'cleanupExpiredPendingBookings');
        Route::get('admin/expired-pending-bookings-count', 'getExpiredPendingBookingsCount');
    });

});

Route::controller(PaymentCallBackController::class)->group(function () {
    Route::get('v1/dpo/payment-cancel', 'cancel');
    Route::get('v1/dpo/payment-success', 'success');
    Route::get('v1/dpo/payment-callback', 'callBack');
    Route::post('v1/dpo/payment-callback', 'callBack');
});
