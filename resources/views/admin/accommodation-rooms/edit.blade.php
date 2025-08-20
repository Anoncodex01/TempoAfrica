@extends('layouts.app')

@section('content')
<main class="flex-1 p-4 md:p-6 bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">Edit Room</h1>
                <p class="text-sm text-gray-600">Update room information</p>
            </div>
            <a href="{{ route('admin.accommodation-rooms.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-semibold rounded-lg hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Rooms
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center text-sm">
            <i class="fas fa-exclamation-circle mr-2 text-red-500"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Edit Form -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-bed text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Edit Room Information</h2>
                    <p class="text-xs text-gray-600">Update the details below to modify this room</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.accommodation-rooms.update', $accommodation_room->id) }}" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-900 border-b border-gray-200 pb-2">Basic Information</h3>
                    
                    <!-- Room Name -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-gray-700 mb-1">Room Name *</label>
                        <input 
                            type="text" 
                            id="name"
                            name="name" 
                            value="{{ old('name', $accommodation_room->name) }}"
                            placeholder="e.g., Deluxe Suite, Standard Room"
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
                                <option value="{{ $category }}" {{ old('category', $accommodation_room->category) == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
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
                            value="{{ old('unique_number', $accommodation_room->unique_number) }}"
                            placeholder="e.g., RM001, SUITE101"
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200 @error('unique_number') border-red-300 @enderror"
                        >
                        @error('unique_number')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Accommodation -->
                    <div>
                        <label for="accommodation_id" class="block text-xs font-semibold text-gray-700 mb-1">Accommodation *</label>
                        <select 
                            id="accommodation_id"
                            name="accommodation_id" 
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200 @error('accommodation_id') border-red-300 @enderror"
                            required
                        >
                            <option value="">Select Accommodation</option>
                            @foreach($accommodations as $accommodation)
                                <option value="{{ $accommodation->id }}" {{ old('accommodation_id', $accommodation_room->accommodation_id) == $accommodation->id ? 'selected' : '' }}>
                                    {{ $accommodation->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('accommodation_id')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Pricing Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-900 border-b border-gray-200 pb-2">Pricing Information</h3>
                    
                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-xs font-semibold text-gray-700 mb-1">Price *</label>
                        <input 
                            type="number" 
                            id="price"
                            name="price" 
                            value="{{ old('price', $accommodation_room->price) }}"
                            placeholder="0.00"
                            step="0.01"
                            min="0"
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200 @error('price') border-red-300 @enderror"
                            required
                        >
                        @error('price')
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
                            <option value="USD" {{ old('currency', $accommodation_room->currency) == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                            <option value="EUR" {{ old('currency', $accommodation_room->currency) == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                            <option value="GBP" {{ old('currency', $accommodation_room->currency) == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                            <option value="KES" {{ old('currency', $accommodation_room->currency) == 'KES' ? 'selected' : '' }}>KES - Kenyan Shilling</option>
                            <option value="UGX" {{ old('currency', $accommodation_room->currency) == 'UGX' ? 'selected' : '' }}>UGX - Ugandan Shilling</option>
                            <option value="TZS" {{ old('currency', $accommodation_room->currency) == 'TZS' ? 'selected' : '' }}>TZS - Tanzanian Shilling</option>
                        </select>
                        @error('currency')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price Duration -->
                    <div>
                        <label for="price_duration" class="block text-xs font-semibold text-gray-700 mb-1">Price Duration *</label>
                        <select 
                            id="price_duration"
                            name="price_duration" 
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200 @error('price_duration') border-red-300 @enderror"
                            required
                        >
                            <option value="">Select Duration</option>
                            <option value="per_night" {{ old('price_duration', $accommodation_room->price_duration) == 'per_night' ? 'selected' : '' }}>Per Night</option>
                            <option value="per_week" {{ old('price_duration', $accommodation_room->price_duration) == 'per_week' ? 'selected' : '' }}>Per Week</option>
                            <option value="per_month" {{ old('price_duration', $accommodation_room->price_duration) == 'per_month' ? 'selected' : '' }}>Per Month</option>
                        </select>
                        @error('price_duration')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label for="description" class="block text-xs font-semibold text-gray-700 mb-1">Description</label>
                <textarea 
                    id="description"
                    name="description" 
                    rows="4"
                    placeholder="Describe the room features, amenities, and any special details..."
                    class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200 @error('description') border-red-300 @enderror"
                >{{ old('description', $accommodation_room->description) }}</textarea>
                @error('description')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Options -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="is_active"
                        name="is_active" 
                        value="1"
                        {{ old('is_active', $accommodation_room->is_active) ? 'checked' : '' }}
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
                        {{ old('is_visible', $accommodation_room->is_visible) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#d71418] border-gray-300 rounded focus:ring-[#d71418] focus:ring-2"
                    >
                    <label for="is_visible" class="ml-2 text-sm text-gray-700">Visible to Customers</label>
                </div>
                
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="is_available"
                        name="is_available" 
                        value="1"
                        {{ old('is_available', $accommodation_room->is_available) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#d71418] border-gray-300 rounded focus:ring-[#d71418] focus:ring-2"
                    >
                    <label for="is_available" class="ml-2 text-sm text-gray-700">Available for Booking</label>
                </div>
            </div>

            <!-- Facilities -->
            <div class="mt-6">
                <h3 class="text-sm font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Room Facilities</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($facilities as $facility)
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="facility_{{ $facility->id }}"
                            name="facility_ids[]" 
                            value="{{ $facility->id }}"
                            {{ $accommodation_room->facilities->contains($facility->id) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#d71418] border-gray-300 rounded focus:ring-[#d71418] focus:ring-2"
                        >
                        <label for="facility_{{ $facility->id }}" class="ml-2 text-sm text-gray-700">{{ $facility->name }}</label>
                    </div>
                    @endforeach
                </div>
                
                @if($facilities->count() == 0)
                <p class="text-xs text-gray-500 mt-2">No facilities available</p>
                @endif
            </div>

            <!-- Current Photos -->
            @if($accommodation_room->photo_url || $accommodation_room->photos->count() > 0)
            <div class="mt-6">
                <h3 class="text-sm font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Current Photos</h3>
                

                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @if($accommodation_room->photo_url)
                    <div class="relative group bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <img src="{{ asset($accommodation_room->photo_url) }}" alt="Main Room Photo" class="w-full h-48 object-cover" style="background: white;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div class="absolute inset-0 bg-gray-200 flex items-center justify-center" style="display: none;">
                            <span class="text-gray-500 text-xs">No Photo Available</span>
                        </div>
                    </div>
                    @endif
                    
                    @foreach($accommodation_room->photos as $photo)
                    <div class="relative group bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <img src="{{ asset($photo->photo_url) }}" alt="{{ $photo->description ?? 'Room Photo' }}" class="w-full h-48 object-cover" style="background: white;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div class="absolute inset-0 bg-gray-200 flex items-center justify-center" style="display: none;">
                            <span class="text-gray-500 text-xs">No Photo Available</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Photo Upload -->
            <div class="mt-6">
                <h3 class="text-sm font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Update Photos</h3>
                
                <!-- Main Photo -->
                <div class="mb-4">
                    <label for="photo" class="block text-xs font-semibold text-gray-700 mb-1">Update Main Photo</label>
                    <input 
                        type="file" 
                        id="photo"
                        name="photo" 
                        accept="image/*"
                        class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200 @error('photo') border-red-300 @enderror"
                    >
                    <p class="text-xs text-gray-500 mt-1">Upload a new main photo for the room (max 2MB)</p>
                    @error('photo')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Additional Photos -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Add More Photos</label>
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
                                    placeholder="Photo description"
                                    class="flex-1 px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200"
                                >
                            </div>
                        </template>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Upload up to 3 additional photos with descriptions (max 2MB each)</p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.accommodation-rooms.show', $accommodation_room->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white text-sm font-semibold rounded-lg hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i>
                    Update Room
                </button>
            </div>
        </form>
    </div>
</main>
@endsection 