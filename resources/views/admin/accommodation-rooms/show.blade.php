@extends('layouts.app')

@section('content')
<main class="flex-1 p-4 md:p-6 bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">Room Details</h1>
                <p class="text-sm text-gray-600">View detailed information about this room</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.accommodation-rooms.edit', $accommodation_room->id) }}" class="inline-flex items-center px-4 py-2 bg-[#d71418] text-white text-sm font-semibold rounded-lg hover:bg-[#b31216] transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Room
                </a>
                <a href="{{ route('admin.accommodation-rooms.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-semibold rounded-lg hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Rooms
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Room Information Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-bed text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">{{ $accommodation_room->name }}</h2>
                            <p class="text-xs text-gray-600">Room Information</p>
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
                                    <span class="text-xs font-medium text-gray-600">Room Name:</span>
                                    <span class="text-sm text-gray-900">{{ $accommodation_room->name }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Category:</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $accommodation_room->category }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Unique Number:</span>
                                    <span class="text-sm text-gray-900">{{ $accommodation_room->unique_number ?? 'N/A' }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Accommodation:</span>
                                    <span class="text-sm text-gray-900">{{ $accommodation_room->accommodation->name ?? 'N/A' }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Description:</span>
                                    <span class="text-sm text-gray-900">{{ $accommodation_room->description ?? 'No description available' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pricing Information -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-semibold text-gray-900 border-b border-gray-200 pb-2">Pricing Information</h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Price:</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ number_format($accommodation_room->price) }} {{ $accommodation_room->currency }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Price Duration:</span>
                                    <span class="text-sm text-gray-900">{{ str_replace('_', ' ', $accommodation_room->price_duration) }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Currency:</span>
                                    <span class="text-sm text-gray-900">{{ $accommodation_room->currency }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Photo Gallery -->
            @if($accommodation_room->photo || $accommodation_room->photos->count() > 0)
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-images text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Photo Gallery</h2>
                            <p class="text-xs text-gray-600">Room photos and images</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @if($accommodation_room->photo)
                        <div class="relative group">
                            <img src="{{ asset($accommodation_room->photo_url) }}" alt="Main Room Photo" class="w-full h-48 object-cover rounded-lg">
                        </div>
                        @else
                        <div class="relative group">
                            <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-gray-500 text-sm">No Photo Available</span>
                            </div>
                        </div>
                        @endif
                        
                        @foreach($accommodation_room->photos as $photo)
                        <div class="relative group">
                            <img src="{{ asset($photo->photo_url) }}" alt="{{ $photo->description ?? 'Room Photo' }}" class="w-full h-48 object-cover rounded-lg">
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
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $accommodation_room->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas fa-circle mr-1 text-xs"></i>
                            {{ $accommodation_room->is_active ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-600">Visible:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $accommodation_room->is_visible ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas fa-circle mr-1 text-xs"></i>
                            {{ $accommodation_room->is_visible ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-600">Available:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $accommodation_room->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas fa-circle mr-1 text-xs"></i>
                            {{ $accommodation_room->is_available ? 'Yes' : 'No' }}
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
                    <button id="toggle-status-btn" onclick="toggleStatus({{ $accommodation_room->id }})" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-power-off mr-2" id="toggle-status-icon"></i>
                        <span id="toggle-status-text">Toggle Active Status</span>
                        <i class="fas fa-spinner fa-spin ml-2 hidden" id="toggle-status-spinner"></i>
                    </button>
                    
                    <button id="toggle-visibility-btn" onclick="toggleVisibility({{ $accommodation_room->id }})" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-eye mr-2" id="toggle-visibility-icon"></i>
                        <span id="toggle-visibility-text">Toggle Visibility</span>
                        <i class="fas fa-spinner fa-spin ml-2 hidden" id="toggle-visibility-spinner"></i>
                    </button>
                    
                    <button id="toggle-availability-btn" onclick="toggleAvailability({{ $accommodation_room->id }})" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-key mr-2" id="toggle-availability-icon"></i>
                        <span id="toggle-availability-text">Toggle Availability</span>
                        <i class="fas fa-spinner fa-spin ml-2 hidden" id="toggle-availability-spinner"></i>
                    </button>
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
                        <span class="text-xs text-gray-900">{{ $accommodation_room->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-600">Updated:</span>
                        <span class="text-xs text-gray-900">{{ $accommodation_room->updated_at->format('M d, Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Toggle functions for quick actions
function toggleStatus(id) {
    const btn = document.getElementById('toggle-status-btn');
    const icon = document.getElementById('toggle-status-icon');
    const text = document.getElementById('toggle-status-text');
    const spinner = document.getElementById('toggle-status-spinner');

    btn.disabled = true;
    icon.classList.add('hidden');
    text.classList.add('hidden');
    spinner.classList.remove('hidden');

    fetch(`/admin/accommodation-rooms/${id}/toggle-status`, {
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
            alert('Failed to toggle status: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error toggling status:', error);
        alert('An error occurred while toggling status.');
    })
    .finally(() => {
        btn.disabled = false;
        icon.classList.remove('hidden');
        text.classList.remove('hidden');
        spinner.classList.add('hidden');
    });
}

function toggleVisibility(id) {
    const btn = document.getElementById('toggle-visibility-btn');
    const icon = document.getElementById('toggle-visibility-icon');
    const text = document.getElementById('toggle-visibility-text');
    const spinner = document.getElementById('toggle-visibility-spinner');

    btn.disabled = true;
    icon.classList.add('hidden');
    text.classList.add('hidden');
    spinner.classList.remove('hidden');

    fetch(`/admin/accommodation-rooms/${id}/toggle-visibility`, {
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
            alert('Failed to toggle visibility: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error toggling visibility:', error);
        alert('An error occurred while toggling visibility.');
    })
    .finally(() => {
        btn.disabled = false;
        icon.classList.remove('hidden');
        text.classList.remove('hidden');
        spinner.classList.add('hidden');
    });
}

function toggleAvailability(id) {
    const btn = document.getElementById('toggle-availability-btn');
    const icon = document.getElementById('toggle-availability-icon');
    const text = document.getElementById('toggle-availability-text');
    const spinner = document.getElementById('toggle-availability-spinner');

    btn.disabled = true;
    icon.classList.add('hidden');
    text.classList.add('hidden');
    spinner.classList.remove('hidden');

    fetch(`/admin/accommodation-rooms/${id}/toggle-availability`, {
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
            alert('Failed to toggle availability: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error toggling availability:', error);
        alert('An error occurred while toggling availability.');
    })
    .finally(() => {
        btn.disabled = false;
        icon.classList.remove('hidden');
        text.classList.remove('hidden');
        spinner.classList.add('hidden');
    });
}
</script>
@endsection 