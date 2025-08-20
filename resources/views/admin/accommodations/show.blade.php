@extends('layouts.app')

@section('content')
<main class="flex-1 p-4 md:p-6 bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">Accommodation Details</h1>
                <p class="text-sm text-gray-600">View detailed information about this accommodation</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.accommodations.edit', $accommodation->id) }}" class="inline-flex items-center px-4 py-2 bg-[#d71418] text-white text-sm font-semibold rounded-lg hover:bg-[#b31216] transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Accommodation
                </a>
                <a href="{{ route('admin.accommodations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-semibold rounded-lg hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Accommodations
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Accommodation Information Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-building text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">{{ $accommodation->name }}</h2>
                            <p class="text-xs text-gray-600">Accommodation Information</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-semibold text-gray-900 border-b border-gray-200 pb-2">Basic Information</h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Name:</span>
                                    <span class="text-sm text-gray-900">{{ $accommodation->name }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Category:</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $accommodation->category }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Registration Number:</span>
                                    <span class="text-sm text-gray-900">{{ $accommodation->registration_number ?? 'N/A' }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Unique Number:</span>
                                    <span class="text-sm text-gray-900">{{ $accommodation->unique_number ?? 'N/A' }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Owner:</span>
                                    <span class="text-sm text-gray-900">{{ $accommodation->customer->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Location Information -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-semibold text-gray-900 border-b border-gray-200 pb-2">Location Information</h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Country:</span>
                                    <span class="text-sm text-gray-900">{{ $accommodation->country->name ?? 'N/A' }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Province:</span>
                                    <span class="text-sm text-gray-900">{{ $accommodation->province->name ?? 'N/A' }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Street:</span>
                                    <span class="text-sm text-gray-900">{{ $accommodation->street->name ?? 'N/A' }}</span>
                                </div>
                                
                                @if($accommodation->latitude && $accommodation->longitude)
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Coordinates:</span>
                                    <span class="text-sm text-gray-900">{{ $accommodation->latitude }}, {{ $accommodation->longitude }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Information -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-dollar-sign text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Pricing Information</h2>
                            <p class="text-xs text-gray-600">Accommodation pricing details</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-gray-600">Minimum Price:</span>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($accommodation->minimum_price) }} {{ $accommodation->currency }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-gray-600">Price Duration:</span>
                            <span class="text-sm text-gray-900">{{ str_replace('_', ' ', $accommodation->minimum_price_duration) }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-gray-600">Currency:</span>
                            <span class="text-sm text-gray-900">{{ $accommodation->currency }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rooms Section -->
            @if($accommodation->rooms->count() > 0)
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-bed text-white text-sm"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Rooms</h2>
                                <p class="text-xs text-gray-600">{{ $accommodation->rooms->count() }} room(s) available</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.accommodation-rooms.create') }}?accommodation_id={{ $accommodation->id }}" class="inline-flex items-center px-3 py-1 bg-[#d71418] text-white text-xs font-semibold rounded-lg hover:bg-[#b31216] transition-all duration-200">
                            <i class="fas fa-plus mr-1"></i>
                            Add Room
                        </a>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($accommodation->rooms as $accommodation_room)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <!-- Room Image -->
                            <div class="mb-3">
                                @if($accommodation_room->photo_url)
                                <img src="{{ asset($accommodation_room->photo_url) }}" alt="{{ $accommodation_room->name }}" class="w-full h-32 object-cover rounded-lg">
                                @else
                                <div class="w-full h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-500 text-xs">No Photo</span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="flex items-start justify-between mb-2">
                                <h4 class="font-semibold text-gray-900 text-sm">{{ $accommodation_room->name }}</h4>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $accommodation_room->category }}
                                </span>
                            </div>
                            
                            <div class="space-y-1 mb-3">
                                <div class="flex items-center text-xs text-gray-600">
                                    <i class="fas fa-dollar-sign mr-1 text-[#d71418]"></i>
                                    <span>{{ number_format($accommodation_room->price) }} {{ $accommodation_room->currency }}</span>
                                </div>
                                <div class="flex items-center text-xs text-gray-600">
                                    <i class="fas fa-clock mr-1 text-[#d71418]"></i>
                                    <span>{{ str_replace('_', ' ', $accommodation_room->price_duration) }}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.accommodation-rooms.show', $accommodation_room->id) }}" class="flex-1 inline-flex items-center justify-center px-2 py-1 bg-blue-500 text-white text-xs font-semibold rounded hover:bg-blue-600 transition-all duration-200">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                                <a href="{{ route('admin.accommodation-rooms.edit', $accommodation_room->id) }}" class="flex-1 inline-flex items-center justify-center px-2 py-1 bg-[#d71418] text-white text-xs font-semibold rounded hover:bg-[#b31216] transition-all duration-200">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Photo Gallery -->
            @if($accommodation->photo || $accommodation->photos->count() > 0)
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-images text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Photo Gallery</h2>
                            <p class="text-xs text-gray-600">Accommodation photos and images</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @if($accommodation->photo_url)
                        <div class="relative group">
                            <img src="{{ asset($accommodation->photo_url) }}" alt="Main Accommodation Photo" class="w-full h-48 object-cover rounded-lg">
                        </div>
                        @else
                        <div class="relative group">
                            <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-gray-500 text-sm">No Photo Available</span>
                            </div>
                        </div>
                        @endif
                        
                        @foreach($accommodation->photos as $photo)
                        <div class="relative group">
                            <img src="{{ asset($photo->photo_url) }}" alt="{{ $photo->description ?? 'Accommodation Photo' }}" class="w-full h-48 object-cover rounded-lg">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <h3 class="text-sm font-semibold text-gray-900">Status Information</h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-600">Active:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $accommodation->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas fa-circle mr-1 text-xs"></i>
                            {{ $accommodation->is_active ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-600">Visible:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $accommodation->is_visible ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas fa-circle mr-1 text-xs"></i>
                            {{ $accommodation->is_visible ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-600">Featured:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $accommodation->is_featured ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas fa-circle mr-1 text-xs"></i>
                            {{ $accommodation->is_featured ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-600">Approved:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $accommodation->is_approved ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas fa-circle mr-1 text-xs"></i>
                            {{ $accommodation->is_approved ? 'Yes' : 'No' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <h3 class="text-sm font-semibold text-gray-900">Quick Actions</h3>
                </div>
                
                <div class="p-6 space-y-3">
                    <button id="toggle-status-btn" onclick="toggleStatus({{ $accommodation->id }})" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-power-off mr-2" id="toggle-status-icon"></i>
                        <span id="toggle-status-text">Toggle Active Status</span>
                        <i class="fas fa-spinner fa-spin ml-2 hidden" id="toggle-status-spinner"></i>
                    </button>
                    
                    <button id="toggle-visibility-btn" onclick="toggleVisibility({{ $accommodation->id }})" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-eye mr-2" id="toggle-visibility-icon"></i>
                        <span id="toggle-visibility-text">Toggle Visibility</span>
                        <i class="fas fa-spinner fa-spin ml-2 hidden" id="toggle-visibility-spinner"></i>
                    </button>
                    
                    <button id="toggle-featured-btn" onclick="toggleFeatured({{ $accommodation->id }})" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-star mr-2" id="toggle-featured-icon"></i>
                        <span id="toggle-featured-text">Toggle Featured</span>
                        <i class="fas fa-spinner fa-spin ml-2 hidden" id="toggle-featured-spinner"></i>
                    </button>
                    
                    @if(!$accommodation->is_approved)
                    <a href="{{ route('admin.accommodations.approve', $accommodation->id) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition-all duration-200">
                        <i class="fas fa-check mr-2"></i>
                        Approve Accommodation
                    </a>
                    @endif
                </div>
            </div>

            <!-- Timestamps -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <h3 class="text-sm font-semibold text-gray-900">Timestamps</h3>
                </div>
                
                <div class="p-6 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-600">Created:</span>
                        <span class="text-xs text-gray-900">{{ $accommodation->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-600">Updated:</span>
                        <span class="text-xs text-gray-900">{{ $accommodation->updated_at->format('M d, Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Toggle functions for quick actions
function toggleStatus(id) {
    const statusBtn = document.getElementById('toggle-status-btn');
    const statusIcon = document.getElementById('toggle-status-icon');
    const statusText = document.getElementById('toggle-status-text');
    const statusSpinner = document.getElementById('toggle-status-spinner');

    statusBtn.disabled = true;
    statusIcon.classList.add('hidden');
    statusText.classList.add('hidden');
    statusSpinner.classList.remove('hidden');

    fetch(`/admin/accommodations/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to toggle status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while toggling status.');
    })
    .finally(() => {
        statusBtn.disabled = false;
        statusIcon.classList.remove('hidden');
        statusText.classList.remove('hidden');
        statusSpinner.classList.add('hidden');
    });
}

function toggleVisibility(id) {
    const visibilityBtn = document.getElementById('toggle-visibility-btn');
    const visibilityIcon = document.getElementById('toggle-visibility-icon');
    const visibilityText = document.getElementById('toggle-visibility-text');
    const visibilitySpinner = document.getElementById('toggle-visibility-spinner');

    visibilityBtn.disabled = true;
    visibilityIcon.classList.add('hidden');
    visibilityText.classList.add('hidden');
    visibilitySpinner.classList.remove('hidden');

    fetch(`/admin/accommodations/${id}/toggle-visibility`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to toggle visibility: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while toggling visibility.');
    })
    .finally(() => {
        visibilityBtn.disabled = false;
        visibilityIcon.classList.remove('hidden');
        visibilityText.classList.remove('hidden');
        visibilitySpinner.classList.add('hidden');
    });
}

function toggleFeatured(id) {
    const featuredBtn = document.getElementById('toggle-featured-btn');
    const featuredIcon = document.getElementById('toggle-featured-icon');
    const featuredText = document.getElementById('toggle-featured-text');
    const featuredSpinner = document.getElementById('toggle-featured-spinner');

    featuredBtn.disabled = true;
    featuredIcon.classList.add('hidden');
    featuredText.classList.add('hidden');
    featuredSpinner.classList.remove('hidden');

    fetch(`/admin/accommodations/${id}/toggle-featured`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to toggle featured: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while toggling featured.');
    })
    .finally(() => {
        featuredBtn.disabled = false;
        featuredIcon.classList.remove('hidden');
        featuredText.classList.remove('hidden');
        featuredSpinner.classList.add('hidden');
    });
}
</script>
@endsection 