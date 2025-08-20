@extends('layouts.app')

@section('content')
<main class="flex-1 p-6 md:p-10 bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Edit Booking</h1>
                <p class="text-gray-600">Update accommodation booking details</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Bookings
            </a>
        </div>
    </div>

    <!-- Main Form Card - Centered -->
    <div class="flex justify-center">
        <div class="w-full max-w-4xl">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <!-- Card Header -->
                <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-calendar-edit text-white text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Booking Details</h2>
                            <p class="text-gray-600 text-sm">Edit the booking information below</p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Customer Selection -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3" for="customer_id">
                                    <i class="fas fa-user mr-2 text-[#d71418]"></i>
                                    Customer
                                </label>
                                <div class="relative">
                                    <select 
                                        name="customer_id" 
                                        id="customer_id" 
                                        required 
                                        class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white"
                                    >
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ $booking->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }} ({{ $customer->email }})</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                        <i class="fas fa-check-circle text-green-500 opacity-0 transition-opacity duration-200" id="customer-check"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Accommodation Selection -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3" for="accommodation_id">
                                    <i class="fas fa-building mr-2 text-[#d71418]"></i>
                                    Accommodation
                                </label>
                                <div class="relative">
                                    <select 
                                        name="accommodation_id" 
                                        id="accommodation_id" 
                                        required 
                                        class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white"
                                    >
                                        <option value="">Select Accommodation</option>
                                        @foreach($accommodations as $accommodation)
                                            <option value="{{ $accommodation->id }}" {{ $booking->accommodation_id == $accommodation->id ? 'selected' : '' }}>{{ $accommodation->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                        <i class="fas fa-check-circle text-green-500 opacity-0 transition-opacity duration-200" id="accommodation-check"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Room Selection -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3" for="accommodation_room_id">
                                    <i class="fas fa-bed mr-2 text-[#d71418]"></i>
                                    Room
                                </label>
                                <div class="relative">
                                    <select 
                                        name="accommodation_room_id" 
                                        id="accommodation_room_id" 
                                        required 
                                        class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white"
                                    >
                                        <option value="">Select Room</option>
                                        @foreach($rooms as $room)
                                            <option value="{{ $room->id }}" {{ $booking->accommodation_room_id == $room->id ? 'selected' : '' }}>{{ $room->name }} - {{ $room->price }} {{ $room->currency }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                        <i class="fas fa-check-circle text-green-500 opacity-0 transition-opacity duration-200" id="room-check"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Number of Guests -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3" for="pacs">
                                    <i class="fas fa-users mr-2 text-[#d71418]"></i>
                                    Number of Guests
                                </label>
                                <div class="relative">
                                    <input 
                                        type="number" 
                                        name="pacs" 
                                        id="pacs" 
                                        value="{{ $booking->pacs }}" 
                                        min="1" 
                                        max="10" 
                                        required 
                                        class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white"
                                        placeholder="Enter number of guests"
                                    >
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                        <i class="fas fa-check-circle text-green-500 opacity-0 transition-opacity duration-200" id="pacs-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Check-in Date -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3" for="from_date">
                                    <i class="fas fa-calendar-day mr-2 text-[#d71418]"></i>
                                    Check-in Date
                                </label>
                                <div class="relative">
                                    <input 
                                        type="date" 
                                        name="from_date" 
                                        id="from_date" 
                                        required 
                                        min="{{ date('Y-m-d') }}"
                                        value="{{ $booking->from_date ? $booking->from_date->format('Y-m-d') : '' }}"
                                        class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white"
                                    >
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                        <i class="fas fa-check-circle text-green-500 opacity-0 transition-opacity duration-200" id="from-date-check"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Check-out Date -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3" for="to_date">
                                    <i class="fas fa-calendar-day mr-2 text-[#d71418]"></i>
                                    Check-out Date
                                </label>
                                <div class="relative">
                                    <input 
                                        type="date" 
                                        name="to_date" 
                                        id="to_date" 
                                        required 
                                        value="{{ $booking->to_date ? $booking->to_date->format('Y-m-d') : '' }}"
                                        class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white"
                                    >
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                        <i class="fas fa-check-circle text-green-500 opacity-0 transition-opacity duration-200" id="to-date-check"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Price per Night -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3" for="price">
                                    <i class="fas fa-dollar-sign mr-2 text-[#d71418]"></i>
                                    Price per Night
                                </label>
                                <div class="relative">
                                    <input 
                                        type="number" 
                                        name="price" 
                                        id="price" 
                                        step="0.01" 
                                        required 
                                        value="{{ $booking->price }}"
                                        class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white"
                                        placeholder="Enter price per night"
                                    >
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                        <i class="fas fa-check-circle text-green-500 opacity-0 transition-opacity duration-200" id="price-check"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Currency -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3" for="currency">
                                    <i class="fas fa-money-bill mr-2 text-[#d71418]"></i>
                                    Currency
                                </label>
                                <div class="relative">
                                    <select 
                                        name="currency" 
                                        id="currency" 
                                        required 
                                        class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white"
                                    >
                                        <option value="">Select Currency</option>
                                        <option value="USD" {{ $booking->currency == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                        <option value="EUR" {{ $booking->currency == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                        <option value="GBP" {{ $booking->currency == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                        <option value="TZS" {{ $booking->currency == 'TZS' ? 'selected' : '' }}>TZS - Tanzanian Shilling</option>
                                        <option value="KES" {{ $booking->currency == 'KES' ? 'selected' : '' }}>KES - Kenyan Shilling</option>
                                        <option value="UGX" {{ $booking->currency == 'UGX' ? 'selected' : '' }}>UGX - Ugandan Shilling</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                        <i class="fas fa-check-circle text-green-500 opacity-0 transition-opacity duration-200" id="currency-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Status Options -->
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Status</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl hover:border-[#d71418]/30 transition-all duration-200 cursor-pointer group">
                                <div class="relative">
                                    <input type="checkbox" name="is_paid" id="is_paid" value="1" class="sr-only" {{ $booking->is_paid ? 'checked' : '' }}>
                                    <div class="w-6 h-6 border-2 border-gray-300 rounded-lg flex items-center justify-center transition-all duration-200 group-hover:border-[#d71418]">
                                        <i class="fas fa-check text-white text-xs opacity-0 transition-opacity duration-200"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <span class="text-sm font-semibold text-gray-700">Paid</span>
                                    <p class="text-xs text-gray-500 mt-1">Mark as paid</p>
                                </div>
                            </label>

                            <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl hover:border-[#d71418]/30 transition-all duration-200 cursor-pointer group">
                                <div class="relative">
                                    <input type="checkbox" name="is_checked_in" id="is_checked_in" value="1" class="sr-only" {{ $booking->is_checked_in ? 'checked' : '' }}>
                                    <div class="w-6 h-6 border-2 border-gray-300 rounded-lg flex items-center justify-center transition-all duration-200 group-hover:border-[#d71418]">
                                        <i class="fas fa-check text-white text-xs opacity-0 transition-opacity duration-200"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <span class="text-sm font-semibold text-gray-700">Checked In</span>
                                    <p class="text-xs text-gray-500 mt-1">Guest has checked in</p>
                                </div>
                            </label>

                            <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl hover:border-[#d71418]/30 transition-all duration-200 cursor-pointer group">
                                <div class="relative">
                                    <input type="checkbox" name="is_checked_out" id="is_checked_out" value="1" class="sr-only" {{ $booking->is_checked_out ? 'checked' : '' }}>
                                    <div class="w-6 h-6 border-2 border-gray-300 rounded-lg flex items-center justify-center transition-all duration-200 group-hover:border-[#d71418]">
                                        <i class="fas fa-check text-white text-xs opacity-0 transition-opacity duration-200"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <span class="text-sm font-semibold text-gray-700">Checked Out</span>
                                    <p class="text-xs text-gray-500 mt-1">Guest has checked out</p>
                                </div>
                            </label>

                            <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl hover:border-[#d71418]/30 transition-all duration-200 cursor-pointer group">
                                <div class="relative">
                                    <input type="checkbox" name="is_cancelled" id="is_cancelled" value="1" class="sr-only" {{ $booking->is_cancelled ? 'checked' : '' }}>
                                    <div class="w-6 h-6 border-2 border-gray-300 rounded-lg flex items-center justify-center transition-all duration-200 group-hover:border-[#d71418]">
                                        <i class="fas fa-check text-white text-xs opacity-0 transition-opacity duration-200"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <span class="text-sm font-semibold text-gray-700">Cancelled</span>
                                    <p class="text-xs text-gray-500 mt-1">Booking is cancelled</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.bookings.index') }}" class="px-8 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white font-semibold rounded-xl hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i>
                            Update Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection 