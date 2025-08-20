@extends('layouts.app')

@section('content')
<main class="flex-1 p-6 md:p-10 bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Edit Province</h1>
                <p class="text-gray-600">Update province information and settings</p>
            </div>
            <a href="{{ route('admin.provinces.index') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Provinces
            </a>
        </div>
    </div>

    <!-- Main Form Card - Centered -->
    <div class="flex justify-center">
        <div class="w-full max-w-2xl">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <!-- Card Header -->
                <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-map text-white text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Province Details</h2>
                            <p class="text-gray-600 text-sm">Modify the province information below</p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <form action="{{ route('admin.provinces.update', $province) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')
                    
                    <!-- Province Name Field -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-3" for="name">
                            <i class="fas fa-map mr-2 text-[#d71418]"></i>
                            Province Name
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                value="{{ $province->name }}" 
                                required 
                                class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white"
                                placeholder="Enter province name"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <i class="fas fa-check-circle text-green-500 opacity-0 transition-opacity duration-200" id="name-check"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Country Field -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-3" for="country_id">
                            <i class="fas fa-flag mr-2 text-[#d71418]"></i>
                            Country
                        </label>
                        <div class="relative">
                            <select 
                                name="country_id" 
                                id="country_id" 
                                required 
                                class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white"
                            >
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ $province->country_id == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <i class="fas fa-check-circle text-green-500 opacity-0 transition-opacity duration-200" id="country-check"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Zip Code Field -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-3" for="zip_code">
                            <i class="fas fa-mail-bulk mr-2 text-[#d71418]"></i>
                            Zip Code
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="zip_code" 
                                id="zip_code" 
                                value="{{ $province->zip_code }}" 
                                class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200 bg-white"
                                placeholder="Enter zip code (optional)"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <i class="fas fa-info-circle text-blue-500 text-sm"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">This field is optional and can be left empty</p>
                    </div>
                    
                    <!-- Is Established Field -->
                    <div class="mb-8">
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl hover:border-[#d71418]/30 transition-all duration-200 cursor-pointer group">
                            <div class="relative">
                                <input 
                                    type="checkbox" 
                                    name="is_established" 
                                    id="is_established" 
                                    value="1" 
                                    {{ $province->is_established ? 'checked' : '' }} 
                                    class="sr-only"
                                >
                                <div class="w-6 h-6 border-2 border-gray-300 rounded-lg flex items-center justify-center transition-all duration-200 group-hover:border-[#d71418]">
                                    <i class="fas fa-check text-white text-xs opacity-0 transition-opacity duration-200" id="checkbox-icon"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <span class="text-sm font-semibold text-gray-700">Is Established</span>
                                <p class="text-xs text-gray-500 mt-1">Mark this province as active and established</p>
                            </div>
                            <div class="ml-auto">
                                <div class="w-12 h-6 bg-gray-200 rounded-full relative transition-all duration-200" id="toggle-bg">
                                    <div class="w-5 h-5 bg-white rounded-full absolute top-0.5 left-0.5 transition-all duration-200 shadow-sm" id="toggle-knob"></div>
                                </div>
                            </div>
                        </label>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                        <a href="{{ route('provinces.index') }}" class="px-8 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white font-semibold rounded-xl hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i>
                            Update Province
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
// Interactive form enhancements
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const countrySelect = document.getElementById('country_id');
    const nameCheck = document.getElementById('name-check');
    const countryCheck = document.getElementById('country-check');
    const checkbox = document.getElementById('is_established');
    const checkboxIcon = document.getElementById('checkbox-icon');
    const toggleBg = document.getElementById('toggle-bg');
    const toggleKnob = document.getElementById('toggle-knob');

    // Name input validation
    nameInput.addEventListener('input', function() {
        if (this.value.length > 0) {
            nameCheck.style.opacity = '1';
        } else {
            nameCheck.style.opacity = '0';
        }
    });

    // Country select validation
    countrySelect.addEventListener('change', function() {
        if (this.value !== '') {
            countryCheck.style.opacity = '1';
        } else {
            countryCheck.style.opacity = '0';
        }
    });

    // Checkbox toggle functionality
    checkbox.addEventListener('change', function() {
        if (this.checked) {
            checkboxIcon.style.opacity = '1';
            toggleBg.classList.add('bg-[#d71418]');
            toggleKnob.style.transform = 'translateX(1.5rem)';
        } else {
            checkboxIcon.style.opacity = '0';
            toggleBg.classList.remove('bg-[#d71418]');
            toggleKnob.style.transform = 'translateX(0)';
        }
    });

    // Initialize checkbox state
    if (checkbox.checked) {
        checkboxIcon.style.opacity = '1';
        toggleBg.classList.add('bg-[#d71418]');
        toggleKnob.style.transform = 'translateX(1.5rem)';
    }

    // Initialize input states
    if (nameInput.value.length > 0) {
        nameCheck.style.opacity = '1';
    }
    if (countrySelect.value !== '') {
        countryCheck.style.opacity = '1';
    }
});
</script>
@endsection 