@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit House Booking</h1>
            <p class="text-gray-600 mt-2">Reference: {{ $houseBooking->reference }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.house-bookings.show', $houseBooking) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200">
                <i class="fas fa-eye mr-2"></i>View Details
            </a>
            <a href="{{ route('admin.house-bookings.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('admin.house-bookings.update', $houseBooking) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- House Selection -->
                <div>
                    <label for="house_id" class="block text-sm font-medium text-gray-700 mb-2">House *</label>
                    <select id="house_id" name="house_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a house</option>
                        @foreach($houses as $house)
                            <option value="{{ $house->id }}" 
                                {{ (old('house_id', $houseBooking->house_id) == $house->id) ? 'selected' : '' }}>
                                {{ $house->name }} - {{ $house->registration_number ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('house_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Customer Selection -->
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">Customer *</label>
                    <select id="customer_id" name="customer_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" 
                                {{ (old('customer_id', $houseBooking->customer_id) == $customer->id) ? 'selected' : '' }}>
                                {{ $customer->first_name }} {{ $customer->last_name }} - {{ $customer->email }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount and Currency -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount (TZS) *</label>
                        <input type="number" id="amount" name="amount" 
                               value="{{ old('amount', $houseBooking->amount) }}" required min="0" step="1000"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="50000">
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Currency *</label>
                        <select id="currency" name="currency" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="TZS" {{ (old('currency', $houseBooking->currency) == 'TZS') ? 'selected' : '' }}>TZS (Tanzanian Shilling)</option>
                            <option value="USD" {{ (old('currency', $houseBooking->currency) == 'USD') ? 'selected' : '' }}>USD (US Dollar)</option>
                            <option value="EUR" {{ (old('currency', $houseBooking->currency) == 'EUR') ? 'selected' : '' }}>EUR (Euro)</option>
                        </select>
                        @error('currency')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Payment Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_paid" value="1" 
                               {{ (old('is_paid', $houseBooking->is_paid) ? 'checked' : '') }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Mark as paid</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-500">Check this if the payment has already been completed</p>
                    @if($houseBooking->paid_at)
                        <p class="mt-1 text-sm text-green-600">
                            <i class="fas fa-check-circle mr-1"></i>Payment completed on {{ $houseBooking->paid_at->format('M d, Y H:i') }}
                        </p>
                    @endif
                </div>

                <!-- Payment Details (Optional) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="payment_token" class="block text-sm font-medium text-gray-700 mb-2">Payment Token</label>
                        <input type="text" id="payment_token" name="payment_token" 
                               value="{{ old('payment_token', $houseBooking->payment_token) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Payment token from payment gateway">
                        @error('payment_token')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_url" class="block text-sm font-medium text-gray-700 mb-2">Payment URL</label>
                        <input type="url" id="payment_url" name="payment_url" 
                               value="{{ old('payment_url', $houseBooking->payment_url) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="https://payment-gateway.com/pay/...">
                        @error('payment_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Receipt Details (Optional) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="receipt_url" class="block text-sm font-medium text-gray-700 mb-2">Receipt URL</label>
                        <input type="url" id="receipt_url" name="receipt_url" 
                               value="{{ old('receipt_url', $houseBooking->receipt_url) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="https://receipt-url.com/...">
                        @error('receipt_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="receipt_filename" class="block text-sm font-medium text-gray-700 mb-2">Receipt Filename</label>
                        <input type="text" id="receipt_filename" name="receipt_filename" 
                               value="{{ old('receipt_filename', $houseBooking->receipt_filename) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="receipt_2025_08_16.pdf">
                        @error('receipt_filename')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Current Status Display -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Current Status</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Reference</label>
                            <p class="mt-1 text-sm text-gray-900 font-mono">{{ $houseBooking->reference }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Created Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $houseBooking->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <div class="mt-1">
                                @if($houseBooking->is_paid)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-receipt mr-1"></i>Paid
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.house-bookings.show', $houseBooking) }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md font-semibold transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-semibold transition duration-200">
                        <i class="fas fa-save mr-2"></i>Update Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
