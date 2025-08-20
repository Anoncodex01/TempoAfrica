@extends('layouts.app')

@section('content')
<main class="flex-1 p-6 md:p-10 bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Booking Details</h1>
                <p class="text-gray-600">View comprehensive booking information</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.bookings.edit', $booking) }}" class="inline-flex items-center px-3 py-2 bg-[#d71418] text-white text-sm font-semibold rounded-lg hover:bg-[#b31216] transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Booking
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Bookings
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Overview Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-calendar-alt text-white text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">Booking Overview</h2>
                                <p class="text-gray-600 text-sm">Reference: {{ $booking->reference }}</p>
                            </div>
                        </div>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'paid' => 'bg-blue-100 text-blue-800',
                                'checked_in' => 'bg-green-100 text-green-800',
                                'checked_out' => 'bg-gray-100 text-gray-800',
                                'cancelled' => 'bg-red-100 text-red-800'
                            ];
                            $statusIcons = [
                                'pending' => 'fas fa-clock',
                                'paid' => 'fas fa-credit-card',
                                'checked_in' => 'fas fa-sign-in-alt',
                                'checked_out' => 'fas fa-sign-out-alt',
                                'cancelled' => 'fas fa-times-circle'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $statusColors[$booking->status] }}">
                            <i class="{{ $statusIcons[$booking->status] }} mr-2"></i>
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </div>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Stay Information</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Check-in Date:</span>
                                    <span class="font-semibold text-gray-900">{{ $booking->from_date ? $booking->from_date->format('M d, Y') : 'N/A' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Check-out Date:</span>
                                    <span class="font-semibold text-gray-900">{{ $booking->to_date ? $booking->to_date->format('M d, Y') : 'N/A' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Duration:</span>
                                    <span class="font-semibold text-gray-900">{{ $booking->duration }} nights</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Number of Guests:</span>
                                    <span class="font-semibold text-gray-900">{{ $booking->pacs }} person(s)</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Price per Night:</span>
                                    <span class="font-semibold text-gray-900">{{ number_format($booking->price) }} {{ $booking->currency }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Total Amount:</span>
                                    <span class="font-semibold text-gray-900">{{ number_format($booking->amount) }} {{ $booking->currency }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Amount Paid:</span>
                                    <span class="font-semibold {{ $booking->is_paid ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($booking->amount_paid) }} {{ $booking->currency }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Payment Status:</span>
                                    <span class="font-semibold {{ $booking->is_paid ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $booking->is_paid ? 'Paid' : 'Unpaid' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accommodation Details Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-building text-white text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Accommodation Details</h2>
                            <p class="text-gray-600 text-sm">Property and room information</p>
                        </div>
                    </div>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Property Information</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Accommodation:</span>
                                    <span class="font-semibold text-gray-900">{{ $booking->accommodation->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Room:</span>
                                    <span class="font-semibold text-gray-900">{{ $booking->accommodationRoom->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Category:</span>
                                    <span class="font-semibold text-gray-900">{{ $booking->accommodation->category ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Room Details</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Room Price:</span>
                                    <span class="font-semibold text-gray-900">{{ number_format($booking->accommodationRoom->price ?? 0) }} {{ $booking->accommodationRoom->currency ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Room Category:</span>
                                    <span class="font-semibold text-gray-900">{{ $booking->accommodationRoom->category ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Room Status:</span>
                                    <span class="font-semibold {{ $booking->accommodationRoom->is_active ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $booking->accommodationRoom->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Customer & Timeline -->
        <div class="space-y-6">
            <!-- Customer Information Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-user text-gray-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $booking->customer->name ?? 'N/A' }}</h4>
                                <p class="text-sm text-gray-600">{{ $booking->customer->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 text-sm">Phone:</span>
                                <span class="font-medium text-gray-900 text-sm">{{ $booking->customer->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 text-sm">Country:</span>
                                <span class="font-medium text-gray-900 text-sm">{{ $booking->customer->country->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 text-sm">Province:</span>
                                <span class="font-medium text-gray-900 text-sm">{{ $booking->customer->province->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Timeline Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Booking Timeline</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-3 h-3 bg-green-500 rounded-full mt-2 mr-3"></div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 text-sm">Booking Created</p>
                                <p class="text-xs text-gray-500">{{ $booking->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($booking->is_paid && $booking->paid_at)
                        <div class="flex items-start">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mt-2 mr-3"></div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 text-sm">Payment Completed</p>
                                <p class="text-xs text-gray-500">{{ $booking->paid_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($booking->is_checked_in && $booking->checked_in_at)
                        <div class="flex items-start">
                            <div class="w-3 h-3 bg-green-500 rounded-full mt-2 mr-3"></div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 text-sm">Guest Checked In</p>
                                <p class="text-xs text-gray-500">{{ $booking->checked_in_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($booking->is_checked_out && $booking->checked_out_at)
                        <div class="flex items-start">
                            <div class="w-3 h-3 bg-gray-500 rounded-full mt-2 mr-3"></div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 text-sm">Guest Checked Out</p>
                                <p class="text-xs text-gray-500">{{ $booking->checked_out_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($booking->is_cancelled)
                        <div class="flex items-start">
                            <div class="w-3 h-3 bg-red-500 rounded-full mt-2 mr-3"></div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 text-sm">Booking Cancelled</p>
                                <p class="text-xs text-gray-500">{{ $booking->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-cogs text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('admin.bookings.edit', $booking) }}" class="w-full flex items-center justify-center px-4 py-3 bg-[#d71418] text-white font-semibold rounded-xl hover:bg-[#b31216] transition-all duration-200">
                            <i class="fas fa-edit mr-2"></i> Edit Booking
                        </a>
                        <a href="{{ route('admin.bookings.export', $booking) }}" class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-blue-800 transition-all duration-200 mb-2 shadow-md">
                            <i class="fas fa-file-pdf mr-2"></i> Export as PDF
                        </a>
                        <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this booking?')" class="w-full flex items-center justify-center px-4 py-3 bg-gray-500 text-white font-semibold rounded-xl hover:bg-gray-600 transition-all duration-200">
                                <i class="fas fa-trash mr-2"></i> Delete Booking
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection 