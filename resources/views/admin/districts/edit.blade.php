@extends('layouts.app')

@section('content')
<main class="flex-1 p-6 md:p-10 bg-gray-100">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-[#d71418]">Edit District</h1>
        <a href="{{ route('admin.districts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-semibold rounded-lg hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg">
            <i class="fas fa-arrow-left mr-2"></i>Back to Districts
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-8">
        <form action="{{ route('admin.districts.update', $district) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3" for="name">
                        <i class="fas fa-map-marker mr-2 text-[#d71418]"></i>
                        District Name
                    </label>
                    <input type="text" name="name" id="name" value="{{ $district->name }}" required 
                           class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200" 
                           placeholder="Enter district name">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3" for="province_id">
                        <i class="fas fa-map mr-2 text-[#d71418]"></i>
                        Province
                    </label>
                    <select name="province_id" id="province_id" required 
                            class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200">
                        <option value="">Select Province</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}" {{ $district->province_id == $province->id ? 'selected' : '' }}>
                                {{ $province->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3" for="zip_code">
                        <i class="fas fa-mail-bulk mr-2 text-[#d71418]"></i>
                        Zip Code
                    </label>
                    <input type="text" name="zip_code" id="zip_code" value="{{ $district->zip_code }}" 
                           class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200" 
                           placeholder="Enter zip code (optional)">
                </div>
                
                <div>
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl hover:border-[#d71418]/30 transition-all duration-200 cursor-pointer group">
                        <div class="relative">
                            <input type="checkbox" name="is_established" id="is_established" value="1" {{ $district->is_established ? 'checked' : '' }} class="sr-only">
                            <div class="w-6 h-6 border-2 border-gray-300 rounded-lg flex items-center justify-center transition-all duration-200 group-hover:border-[#d71418]">
                                <i class="fas fa-check text-white text-xs opacity-0 transition-opacity duration-200"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <span class="text-sm font-semibold text-gray-700">Is Established</span>
                            <p class="text-xs text-gray-500 mt-1">Mark this district as active and established</p>
                        </div>
                    </label>
                </div>
            </div>
            
            <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.districts.index') }}" 
                   class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white font-semibold rounded-xl hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i>Update District
                </button>
            </div>
        </form>
    </div>
</main>
@endsection 