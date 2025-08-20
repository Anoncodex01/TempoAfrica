@extends('layouts.app')

@section('content')
<main class="flex-1 p-6 md:p-10 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Add New House</h1>
            <p class="text-gray-600">Register a new house in the system</p>
        </div>
        <a href="{{ route('admin.houses.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Back to Houses
        </a>
    </div>
    <div class="flex justify-center">
        <div class="w-full max-w-4xl">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-home text-white text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">House Details</h2>
                            <p class="text-gray-600 text-sm">Fill in the house information below</p>
                        </div>
                    </div>
                </div>
                <form action="{{ route('admin.houses.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    @if($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="name">Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white" placeholder="Enter house name">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="registration_number">Registration Number</label>
                                <input type="text" name="registration_number" id="registration_number" value="{{ old('registration_number') }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white" placeholder="Enter registration number">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="unique_number">Unique Number</label>
                                <input type="text" name="unique_number" id="unique_number" value="{{ old('unique_number') }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white" placeholder="Enter unique number">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="currency">Currency</label>
                                <input type="text" name="currency" id="currency" value="{{ old('currency') }}" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white" placeholder="e.g. USD, EUR, TZS">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="price">Price</label>
                                <input type="number" name="price" id="price" value="{{ old('price') }}" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white" placeholder="Enter price">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="price_duration">Price Duration</label>
                                <input type="text" name="price_duration" id="price_duration" value="{{ old('price_duration') }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white" placeholder="e.g. per month, per week">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="fee">Fee</label>
                                <input type="number" name="fee" id="fee" value="{{ old('fee') }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white" placeholder="Enter fee">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="minimum_rent_duration">Minimum Rent Duration</label>
                                <input type="number" name="minimum_rent_duration" id="minimum_rent_duration" value="{{ old('minimum_rent_duration') }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white" placeholder="Enter minimum rent duration">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="booking_price">Booking Price</label>
                                <input type="number" name="booking_price" id="booking_price" value="{{ old('booking_price') }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white" placeholder="Enter booking price">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="number_of_rooms">Number of Rooms</label>
                                <input type="number" name="number_of_rooms" id="number_of_rooms" value="{{ old('number_of_rooms') }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white" placeholder="Enter number of rooms">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="category">Category</label>
                                <input type="text" name="category" id="category" value="{{ old('category') }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white" placeholder="e.g. Apartment, Villa">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="description">Description</label>
                                <textarea name="description" id="description" rows="3" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white" placeholder="Enter description">{{ old('description') }}</textarea>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="photo">Main Photo</label>
                                <input type="file" name="photo" id="photo" accept="image/*" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="customer_id">Customer</label>
                                <select name="customer_id" id="customer_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white">
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->first_name }} {{ $customer->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="country_id">Country</label>
                                <select name="country_id" id="country_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="province_id">Province</label>
                                <select name="province_id" id="province_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white">
                                    <option value="">Select Province</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>{{ $province->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="district_id">District</label>
                                <select name="district_id" id="district_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white">
                                    <option value="">Select District</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="street_id">Street</label>
                                <select name="street_id" id="street_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white">
                                    <option value="">Select Street</option>
                                    @foreach($streets as $street)
                                        <option value="{{ $street->id }}" {{ old('street_id') == $street->id ? 'selected' : '' }}>{{ $street->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="latitude">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white" placeholder="Latitude">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="longitude">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white" placeholder="Longitude">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="from_date">From Date</label>
                                    <input type="date" name="from_date" id="from_date" value="{{ old('from_date') }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="to_date">To Date</label>
                                    <input type="date" name="to_date" id="to_date" value="{{ old('to_date') }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} class="mr-2">
                                    <label for="is_active" class="text-sm text-gray-700">Active</label>
                                </div>
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" name="is_visible" id="is_visible" value="1" {{ old('is_visible') ? 'checked' : '' }} class="mr-2">
                                    <label for="is_visible" class="text-sm text-gray-700">Visible</label>
                                </div>
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" name="is_approved" id="is_approved" value="1" {{ old('is_approved') ? 'checked' : '' }} class="mr-2">
                                    <label for="is_approved" class="text-sm text-gray-700">Approved</label>
                                </div>
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="mr-2">
                                    <label for="is_featured" class="text-sm text-gray-700">Featured</label>
                                </div>
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" name="is_booked" id="is_booked" value="1" {{ old('is_booked') ? 'checked' : '' }} class="mr-2">
                                    <label for="is_booked" class="text-sm text-gray-700">Booked</label>
                                </div>
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" name="has_water" id="has_water" value="1" {{ old('has_water') ? 'checked' : '' }} class="mr-2">
                                    <label for="has_water" class="text-sm text-gray-700">Has Water</label>
                                </div>
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" name="has_electricity" id="has_electricity" value="1" {{ old('has_electricity') ? 'checked' : '' }} class="mr-2">
                                    <label for="has_electricity" class="text-sm text-gray-700">Has Electricity</label>
                                </div>
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" name="has_fence" id="has_fence" value="1" {{ old('has_fence') ? 'checked' : '' }} class="mr-2">
                                    <label for="has_fence" class="text-sm text-gray-700">Has Fence</label>
                                </div>
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" name="has_public_transport" id="has_public_transport" value="1" {{ old('has_public_transport') ? 'checked' : '' }} class="mr-2">
                                    <label for="has_public_transport" class="text-sm text-gray-700">Has Public Transport</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.houses.index') }}" class="px-8 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white font-semibold rounded-xl hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i>
                            Create House
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection 