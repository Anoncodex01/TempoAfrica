@extends('layouts.app')

@section('content')
<main class="flex-1 p-4 md:p-6 bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">Create New Accommodation</h1>
                <p class="text-sm text-gray-600">Add a new accommodation property</p>
            </div>
            <a href="{{ route('admin.accommodations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-semibold rounded-lg hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Accommodations
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center text-sm">
            <i class="fas fa-exclamation-circle mr-2 text-red-500"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Create Form -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-building text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Accommodation Information</h2>
                    <p class="text-xs text-gray-600">Fill in the details below to create a new accommodation</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.accommodations.store') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-900 border-b border-gray-200 pb-2">Basic Information</h3>
                    
                    <!-- Accommodation Name -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-gray-700 mb-1">Accommodation Name *</label>
                        <input 
                            type="text" 
                            id="name"
                            name="name" 
                            value="{{ old('name') }}"
                            placeholder="e.g., Grand Hotel, Beach Resort"
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200 @error('name') border-red-300 @enderror"
                            required
                        >
                        @error('name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-xs font-semibold text-gray-700 mb-1">Category *</label>
                        <select 
                            id="category"
                            name="category" 
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200 @error('category') border-red-300 @enderror"
                            required
                        >
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Registration Number -->
                    <div>
                        <label for="registration_number" class="block text-xs font-semibold text-gray-700 mb-1">Registration Number</label>
                        <input 
                            type="text" 
                            id="registration_number"
                            name="registration_number" 
                            value="{{ old('registration_number') }}"
                            placeholder="e.g., REG123456"
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200 @error('registration_number') border-red-300 @enderror"
                        >
                        @error('registration_number')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unique Number -->
                    <div>
                        <label for="unique_number" class="block text-xs font-semibold text-gray-700 mb-1">Unique Number</label>
                        <input 
                            type="text" 
                            id="unique_number"
                            name="unique_number" 
                            value="{{ old('unique_number') }}"
                            placeholder="e.g., ACC001"
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200 @error('unique_number') border-red-300 @enderror"
                        >
                        @error('unique_number')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Customer -->
                    <div>
                        <label for="customer_id" class="block text-xs font-semibold text-gray-700 mb-1">Owner/Customer *</label>
                        <select 
                            id="customer_id"
                            name="customer_id" 
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200 @error('customer_id') border-red-300 @enderror"
                            required
                        >
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Location Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-900 border-b border-gray-200 pb-2">Location Information</h3>
                    
                    <!-- Country -->
                    <div>
                        <label for="country_id" class="block text-xs font-semibold text-gray-700 mb-1">Country *</label>
                        <select 
                            id="country_id"
                            name="country_id" 
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200 @error('country_id') border-red-300 @enderror"
                            required
                        >
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Province -->
                    <div>
                        <label for="province_id" class="block text-xs font-semibold text-gray-700 mb-1">Province *</label>
                        <select 
                            id="province_id"
                            name="province_id" 
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200 @error('province_id') border-red-300 @enderror"
                            required
                        >
                            <option value="">Select Province</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                    {{ $province->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('province_id')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Street -->
                    <div>
                        <label for="street_id" class="block text-xs font-semibold text-gray-700 mb-1">Street *</label>
                        <select 
                            id="street_id"
                            name="street_id" 
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200 @error('street_id') border-red-300 @enderror"
                            required
                        >
                            <option value="">Select Street</option>
                            @foreach($streets as $street)
                                <option value="{{ $street->id }}" {{ old('street_id') == $street->id ? 'selected' : '' }}>
                                    {{ $street->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('street_id')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Coordinates -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="latitude" class="block text-xs font-semibold text-gray-700 mb-1">Latitude</label>
                            <input 
                                type="number" 
                                id="latitude"
                                name="latitude" 
                                value="{{ old('latitude') }}"
                                placeholder="0.000000"
                                step="0.000001"
                                class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200 @error('latitude') border-red-300 @enderror"
                            >
                            @error('latitude')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="longitude" class="block text-xs font-semibold text-gray-700 mb-1">Longitude</label>
                            <input 
                                type="number" 
                                id="longitude"
                                name="longitude" 
                                value="{{ old('longitude') }}"
                                placeholder="0.000000"
                                step="0.000001"
                                class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200 @error('longitude') border-red-300 @enderror"
                            >
                            @error('longitude')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Information -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-900 border-b border-gray-200 pb-2">Pricing Information</h3>
                    
                    <!-- Minimum Price -->
                    <div>
                        <label for="minimum_price" class="block text-xs font-semibold text-gray-700 mb-1">Minimum Price *</label>
                        <input 
                            type="number" 
                            id="minimum_price"
                            name="minimum_price" 
                            value="{{ old('minimum_price') }}"
                            placeholder="0.00"
                            step="0.01"
                            min="0"
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200 @error('minimum_price') border-red-300 @enderror"
                            required
                        >
                        @error('minimum_price')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Currency -->
                    <div>
                        <label for="currency" class="block text-xs font-semibold text-gray-700 mb-1">Currency *</label>
                        <select 
                            id="currency"
                            name="currency" 
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200 @error('currency') border-red-300 @enderror"
                            required
                        >
                            <option value="">Select Currency</option>
                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                            <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                            <option value="KES" {{ old('currency') == 'KES' ? 'selected' : '' }}>KES - Kenyan Shilling</option>
                            <option value="UGX" {{ old('currency') == 'UGX' ? 'selected' : '' }}>UGX - Ugandan Shilling</option>
                            <option value="TZS" {{ old('currency') == 'TZS' ? 'selected' : '' }}>TZS - Tanzanian Shilling</option>
                        </select>
                        @error('currency')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price Duration -->
                    <div>
                        <label for="minimum_price_duration" class="block text-xs font-semibold text-gray-700 mb-1">Price Duration *</label>
                        <select 
                            id="minimum_price_duration"
                            name="minimum_price_duration" 
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200 @error('minimum_price_duration') border-red-300 @enderror"
                            required
                        >
                            <option value="">Select Duration</option>
                            <option value="per_night" {{ old('minimum_price_duration') == 'per_night' ? 'selected' : '' }}>Per Night</option>
                            <option value="per_week" {{ old('minimum_price_duration') == 'per_week' ? 'selected' : '' }}>Per Week</option>
                            <option value="per_month" {{ old('minimum_price_duration') == 'per_month' ? 'selected' : '' }}>Per Month</option>
                        </select>
                        @error('minimum_price_duration')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status Options -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-900 border-b border-gray-200 pb-2">Status Options</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                id="is_active"
                                name="is_active" 
                                value="1"
                                {{ old('is_active') ? 'checked' : '' }}
                                class="w-4 h-4 text-[#d71418] border-gray-300 rounded focus:ring-[#d71418] focus:ring-2"
                            >
                            <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                id="is_visible"
                                name="is_visible" 
                                value="1"
                                {{ old('is_visible') ? 'checked' : '' }}
                                class="w-4 h-4 text-[#d71418] border-gray-300 rounded focus:ring-[#d71418] focus:ring-2"
                            >
                            <label for="is_visible" class="ml-2 text-sm text-gray-700">Visible to Customers</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                id="is_featured"
                                name="is_featured" 
                                value="1"
                                {{ old('is_featured') ? 'checked' : '' }}
                                class="w-4 h-4 text-[#d71418] border-gray-300 rounded focus:ring-[#d71418] focus:ring-2"
                            >
                            <label for="is_featured" class="ml-2 text-sm text-gray-700">Featured</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                id="is_approved"
                                name="is_approved" 
                                value="1"
                                {{ old('is_approved') ? 'checked' : '' }}
                                class="w-4 h-4 text-[#d71418] border-gray-300 rounded focus:ring-[#d71418] focus:ring-2"
                            >
                            <label for="is_approved" class="ml-2 text-sm text-gray-700">Approved</label>
                        </div>
                    </div>
                </div>

                <!-- Photo Upload -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-900 border-b border-gray-200 pb-2">Accommodation Photos</h3>
                    
                    <!-- Main Photo -->
                    <div>
                        <label for="photo" class="block text-xs font-semibold text-gray-700 mb-1">Main Photo</label>
                        <input 
                            type="file" 
                            id="photo"
                            name="photo" 
                            accept="image/*"
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200 @error('photo') border-red-300 @enderror"
                        >
                        <p class="text-xs text-gray-500 mt-1">Upload a main photo (max 2MB)</p>
                        @error('photo')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Additional Photos -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Additional Photos</label>
                        <div class="space-y-3">
                            <template x-for="i in 3" :key="i">
                                <div class="flex items-center space-x-3">
                                    <input 
                                        type="file" 
                                        :name="`photos[]`"
                                        accept="image/*"
                                        class="flex-1 px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200"
                                    >
                                    <input 
                                        type="text" 
                                        :name="`photo_descriptions[]`"
                                        placeholder="Description"
                                        class="flex-1 px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200"
                                    >
                                </div>
                            </template>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Upload up to 3 additional photos (max 2MB each)</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.accommodations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white text-sm font-semibold rounded-lg hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i>
                    Create Accommodation
                </button>
            </div>
        </form>
    </div>
</main>
@endsection 