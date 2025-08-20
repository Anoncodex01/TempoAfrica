@extends('layouts.app')

@section('content')
<main class="flex-1 p-6 md:p-10 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Add New Customer</h1>
            <p class="text-gray-600">Register a new customer in the system</p>
        </div>
        <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Back to Customers
        </a>
    </div>
    <div class="flex justify-center">
        <div class="w-full max-w-3xl">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-user-plus text-white text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Customer Details</h2>
                            <p class="text-gray-600 text-sm">Fill in the customer information below</p>
                        </div>
                    </div>
                </div>
                <form action="{{ route('admin.customers.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
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
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="first_name">First Name</label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white" placeholder="Enter first name">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="last_name">Last Name</label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white" placeholder="Enter last name">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="phone">Phone</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white" placeholder="Enter phone number">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="email">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white" placeholder="Enter email address">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="photo">Photo</label>
                                <input type="file" name="photo" id="photo" accept="image/*" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white">
                            </div>
                        </div>
                        <div class="space-y-6">
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
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="street_id">Street</label>
                                <select name="street_id" id="street_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white">
                                    <option value="">Select Street</option>
                                    @foreach($streets as $street)
                                        <option value="{{ $street->id }}" {{ old('street_id') == $street->id ? 'selected' : '' }}>{{ $street->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="gender">Gender</label>
                                <select name="gender" id="gender" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="dob">Date of Birth</label>
                                <input type="date" name="dob" id="dob" value="{{ old('dob') }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white">
                            </div>
                            <div class="flex items-center mt-2">
                                <input type="checkbox" name="is_verified" id="is_verified" value="1" {{ old('is_verified') ? 'checked' : '' }} class="mr-2">
                                <label for="is_verified" class="text-sm text-gray-700">Verified</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.customers.index') }}" class="px-8 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white font-semibold rounded-xl hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i>
                            Create Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection 