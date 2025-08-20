<?php

use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Dashboard\AccommodationController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\ProvinceController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\StreetController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\AccommodationController as AdminAccommodationController;
use App\Http\Controllers\Admin\AccommodationRoomController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\HouseController;
use App\Http\Controllers\Admin\HousePhotoController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RoomPhotoController;
use App\Http\Controllers\Admin\HouseBookingController;

// Serve uploaded images
Route::get('uploads/{folder}/{filename}', function ($folder, $filename) {
    $path = public_path("uploads/$folder/$filename");
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    $file = file_get_contents($path);
    $type = mime_content_type($path);
    
    return response($file, 200)
        ->header('Content-Type', $type)
        ->header('Cache-Control', 'public, max-age=31536000');
})->where('filename', '.*');

// Serve storage images
Route::get('storage/{folder}/{subfolder}/{filename}', function ($folder, $subfolder, $filename) {
    $path = storage_path("app/public/$folder/$subfolder/$filename");
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    $file = file_get_contents($path);
    $type = mime_content_type($path);
    
    return response($file, 200)
        ->header('Content-Type', $type)
        ->header('Cache-Control', 'public, max-age=31536000');
})->where('filename', '.*');

Route::controller(SignInController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'signIn');
});

Route::post('/logout', function () {
    Auth::guard('user')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::middleware(['auth:user'])->group(function () {

    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
    });

    Route::get('/accommodations', [AccommodationController::class, 'index'])->name('accommodations.index');
    
    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        // Location Management
        Route::resource('countries', CountryController::class);
        Route::resource('provinces', ProvinceController::class);
        Route::resource('districts', DistrictController::class);
        Route::resource('streets', StreetController::class);
        
        // Booking Management
        Route::resource('bookings', BookingController::class);
        Route::get('bookings/{accommodation}/rooms', [BookingController::class, 'getRooms'])->name('bookings.rooms');
        Route::get('bookings/{booking}/export', [BookingController::class, 'export'])->name('bookings.export');
        
        // House Booking Management
        Route::resource('house-bookings', HouseBookingController::class);
        Route::post('house-bookings/{houseBooking}/toggle-payment', [HouseBookingController::class, 'togglePaymentStatus'])->name('house-bookings.toggle-payment');
        Route::post('house-bookings/{houseBooking}/mark-paid', [HouseBookingController::class, 'markAsPaid'])->name('house-bookings.mark-paid');
        Route::get('house-bookings/{houseBooking}/mark-paid', [HouseBookingController::class, 'markAsPaid'])->name('house-bookings.mark-paid.get');
        
        // Accommodation Management
        Route::resource('accommodations', AdminAccommodationController::class);
        Route::post('accommodations/{accommodation}/toggle-status', [AdminAccommodationController::class, 'toggleStatus'])->name('accommodations.toggle-status');
        Route::post('accommodations/{accommodation}/toggle-visibility', [AdminAccommodationController::class, 'toggleVisibility'])->name('accommodations.toggle-visibility');
        Route::post('accommodations/{accommodation}/toggle-featured', [AdminAccommodationController::class, 'toggleFeatured'])->name('accommodations.toggle-featured');
        Route::post('accommodations/{accommodation}/approve', [AdminAccommodationController::class, 'approve'])->name('accommodations.approve');
        Route::get('accommodations/{accommodation}/approve', [AdminAccommodationController::class, 'approve'])->name('accommodations.approve.get');
        Route::delete('accommodation-photos/{photo}', [AdminAccommodationController::class, 'deletePhoto'])->name('accommodation-photos.destroy');
        
        // Accommodation Room Management
        Route::resource('accommodation-rooms', AccommodationRoomController::class);
        Route::post('accommodation-rooms/{room}/toggle-status', [AccommodationRoomController::class, 'toggleStatus'])->name('accommodation-rooms.toggle-status');
        Route::post('accommodation-rooms/{room}/toggle-visibility', [AccommodationRoomController::class, 'toggleVisibility'])->name('accommodation-rooms.toggle-visibility');
        Route::post('accommodation-rooms/{room}/toggle-availability', [AccommodationRoomController::class, 'toggleAvailability'])->name('accommodation-rooms.toggle-availability');
        Route::delete('accommodation-room-photos/{photo}', [AccommodationRoomController::class, 'deletePhoto'])->name('accommodation-room-photos.destroy');
        Route::get('accommodations/{accommodation}/rooms', [AccommodationRoomController::class, 'getRoomsByAccommodation'])->name('accommodations.rooms');

        // Customer Management
        Route::resource('customers', CustomerController::class);

        // House Management
        Route::resource('houses', HouseController::class);
        Route::post('houses/{house}/toggle-status', [HouseController::class, 'toggleStatus'])->name('houses.toggle-status');
        Route::post('houses/{house}/toggle-visibility', [HouseController::class, 'toggleVisibility'])->name('houses.toggle-visibility');
        Route::post('houses/{house}/toggle-featured', [HouseController::class, 'toggleFeatured'])->name('houses.toggle-featured');
        Route::post('houses/{house}/approve', [HouseController::class, 'approve'])->name('houses.approve');
        Route::get('houses/{house}/approve', [HouseController::class, 'approve'])->name('houses.approve.get');
        Route::get('houses/{house}/photos', [\App\Http\Controllers\Admin\HousePhotoController::class, 'index'])->name('houses.photos.index');
        Route::post('houses/{house}/photos', [\App\Http\Controllers\Admin\HousePhotoController::class, 'store'])->name('houses.photos.store');
        Route::delete('houses/{house}/photos/{photo}', [\App\Http\Controllers\Admin\HousePhotoController::class, 'destroy'])->name('houses.photos.destroy');
        Route::post('houses/{house}/photos/{photo}/toggle', [\App\Http\Controllers\Admin\HousePhotoController::class, 'toggleVisibility'])->name('houses.photos.toggle');
        Route::resource('houses.rooms', \App\Http\Controllers\Admin\RoomController::class);
        Route::get('houses-rooms', [\App\Http\Controllers\Admin\RoomController::class, 'indexGlobal'])->name('houses-rooms.index');

        // Room Management
        Route::get('rooms/{room}/photos', [\App\Http\Controllers\Admin\RoomPhotoController::class, 'index'])->name('rooms.photos.index');
        Route::post('rooms/{room}/photos', [\App\Http\Controllers\Admin\RoomPhotoController::class, 'store'])->name('rooms.photos.store');
        Route::delete('rooms/{room}/photos/{photo}', [\App\Http\Controllers\Admin\RoomPhotoController::class, 'destroy'])->name('rooms.photos.destroy');
        Route::post('rooms/{room}/photos/{photo}/toggle', [\App\Http\Controllers\Admin\RoomPhotoController::class, 'toggleVisibility'])->name('rooms.photos.toggle');
    });
});
