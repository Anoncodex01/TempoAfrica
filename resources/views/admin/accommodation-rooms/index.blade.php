@extends('layouts.app')

@section('content')
<main class="flex-1 p-4 md:p-6 bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">Accommodation Rooms</h1>
                <p class="text-sm text-gray-600">Manage and track all accommodation rooms</p>
            </div>
            <a href="{{ route('admin.accommodation-rooms.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white text-sm font-semibold rounded-lg hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                New Room
            </a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center text-sm">
            <i class="fas fa-check-circle mr-2 text-green-500"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center text-sm">
            <i class="fas fa-exclamation-circle mr-2 text-red-500"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-bed text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600">Total Rooms</p>
                    <p class="text-lg font-bold text-gray-900">{{ $rooms->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-check-circle text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600">Active</p>
                    <p class="text-lg font-bold text-gray-900">{{ $rooms->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-eye text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600">Visible</p>
                    <p class="text-lg font-bold text-gray-900">{{ $rooms->where('is_visible', true)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-key text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600">Available</p>
                    <p class="text-lg font-bold text-gray-900">{{ $rooms->where('is_available', true)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 mb-6 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-search text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Search & Filters</h2>
                    <p class="text-xs text-gray-600">Find specific rooms using the filters below</p>
                </div>
            </div>
        </div>
        <form method="GET" action="{{ route('admin.accommodation-rooms.index') }}" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, unique number..." class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                        <option value="">All</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Category</label>
                    <input type="text" name="category" value="{{ request('category') }}" placeholder="Category..." class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Visible</label>
                    <select name="is_visible" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                        <option value="">All</option>
                        <option value="1" {{ request('is_visible') == '1' ? 'selected' : '' }}>Visible</option>
                        <option value="0" {{ request('is_visible') == '0' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Available</label>
                    <select name="is_available" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                        <option value="">All</option>
                        <option value="1" {{ request('is_available') == '1' ? 'selected' : '' }}>Available</option>
                        <option value="0" {{ request('is_available') == '0' ? 'selected' : '' }}>Unavailable</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center space-x-3 mt-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white text-sm font-semibold rounded-lg hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-search mr-2"></i>
                    Apply Filters
                </button>
                <a href="{{ route('admin.accommodation-rooms.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Clear All
                </a>
            </div>
        </form>
    </div>

    <!-- Rooms Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($rooms as $room)
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-200">
            <!-- Room Image -->
            <div class="relative h-48 bg-gray-200">
                @if($room->photo)
                                                    <img src="{{ asset($room->photo_url) }}" alt="{{ $room->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fas fa-bed text-gray-400 text-3xl"></i>
                    </div>
                @endif
                
                <!-- Status Badges -->
                <div class="absolute top-3 left-3 flex flex-col space-y-1">
                    @if(!$room->is_available)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1"></i> Unavailable
                        </span>
                    @endif
                    @if(!$room->is_visible)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-eye-slash mr-1"></i> Hidden
                        </span>
                    @endif
                </div>
                
                <!-- Quick Actions -->
                <div class="absolute top-3 right-3 flex space-x-1">
                    <button id="toggle-status-{{ $room->id }}" onclick="toggleStatus({{ $room->id }})" class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-md hover:shadow-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-power-off text-sm {{ $room->is_active ? 'text-green-500' : 'text-gray-400' }}" id="toggle-status-icon-{{ $room->id }}"></i>
                    </button>
                    <button id="toggle-visibility-{{ $room->id }}" onclick="toggleVisibility({{ $room->id }})" class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-md hover:shadow-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-eye text-sm {{ $room->is_visible ? 'text-blue-500' : 'text-gray-400' }}" id="toggle-visibility-icon-{{ $room->id }}"></i>
                    </button>
                    <button id="toggle-availability-{{ $room->id }}" onclick="toggleAvailability({{ $room->id }})" class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-md hover:shadow-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-key text-sm {{ $room->is_available ? 'text-purple-500' : 'text-gray-400' }}" id="toggle-availability-icon-{{ $room->id }}"></i>
                    </button>
                </div>
            </div>
            
            <!-- Room Details -->
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <h3 class="font-semibold text-gray-900 text-sm truncate">{{ $room->name }}</h3>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        {{ $room->category }}
                    </span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-xs text-gray-600">
                        <i class="fas fa-building mr-2 text-[#d71418]"></i>
                        <span class="truncate">{{ $room->accommodation->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center text-xs text-gray-600">
                        <i class="fas fa-dollar-sign mr-2 text-[#d71418]"></i>
                        <span>{{ number_format($room->price) }} {{ $room->currency }}</span>
                    </div>
                    <div class="flex items-center text-xs text-gray-600">
                        <i class="fas fa-clock mr-2 text-[#d71418]"></i>
                        <span>{{ str_replace('_', ' ', $room->price_duration) }}</span>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.accommodation-rooms.show', $room->id) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-500 text-white text-xs font-semibold rounded-lg hover:bg-blue-600 transition-all duration-200">
                        <i class="fas fa-eye mr-1"></i> View
                    </a>
                    <a href="{{ route('admin.accommodation-rooms.edit', $room->id) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-[#d71418] text-white text-xs font-semibold rounded-lg hover:bg-[#b31216] transition-all duration-200">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('admin.accommodation-rooms.destroy', $room->id) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this room?')" class="w-full inline-flex items-center justify-center px-3 py-2 bg-gray-500 text-white text-xs font-semibold rounded-lg hover:bg-gray-600 transition-all duration-200">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bed text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500 font-medium text-sm">No rooms found</p>
                <p class="text-gray-400 text-xs mt-1">Try adjusting your search criteria or create a new room</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($rooms->hasPages())
        <div class="mt-6">
            {{ $rooms->appends(request()->query())->links() }}
        </div>
    @endif
</main>

<script>
// Toggle functions for quick actions
function toggleStatus(id) {
    const statusButton = document.getElementById(`toggle-status-${id}`);
    const statusIcon = document.getElementById(`toggle-status-icon-${id}`);

    statusButton.disabled = true;
    statusIcon.classList.add('hidden');

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
            statusIcon.classList.remove('hidden');
            statusButton.disabled = false;
            location.reload();
        } else {
            statusIcon.classList.remove('hidden');
            statusButton.disabled = false;
            alert('Failed to toggle status: ' + data.message);
        }
    })
    .catch(error => {
        statusIcon.classList.remove('hidden');
        statusButton.disabled = false;
        console.error('Error toggling status:', error);
        alert('An error occurred while toggling status.');
    });
}

function toggleVisibility(id) {
    const visibilityButton = document.getElementById(`toggle-visibility-${id}`);
    const visibilityIcon = document.getElementById(`toggle-visibility-icon-${id}`);

    visibilityButton.disabled = true;
    visibilityIcon.classList.add('hidden');

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
            visibilityIcon.classList.remove('hidden');
            visibilityButton.disabled = false;
            location.reload();
        } else {
            visibilityIcon.classList.remove('hidden');
            visibilityButton.disabled = false;
            alert('Failed to toggle visibility: ' + data.message);
        }
    })
    .catch(error => {
        visibilityIcon.classList.remove('hidden');
        visibilityButton.disabled = false;
        console.error('Error toggling visibility:', error);
        alert('An error occurred while toggling visibility.');
    });
}

function toggleAvailability(id) {
    const availabilityButton = document.getElementById(`toggle-availability-${id}`);
    const availabilityIcon = document.getElementById(`toggle-availability-icon-${id}`);

    availabilityButton.disabled = true;
    availabilityIcon.classList.add('hidden');

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
            availabilityIcon.classList.remove('hidden');
            availabilityButton.disabled = false;
            location.reload();
        } else {
            availabilityIcon.classList.remove('hidden');
            availabilityButton.disabled = false;
            alert('Failed to toggle availability: ' + data.message);
        }
    })
    .catch(error => {
        availabilityIcon.classList.remove('hidden');
        availabilityButton.disabled = false;
        console.error('Error toggling availability:', error);
        alert('An error occurred while toggling availability.');
    });
}

// Auto-submit form when certain filters change
document.addEventListener('DOMContentLoaded', function() {
    const autoSubmitSelects = document.querySelectorAll('select[name="status"], select[name="category"], select[name="accommodation_id"], select[name="sort_by"], select[name="sort_order"]');
    autoSubmitSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });

    // Search input with debouncing
    const searchInput = document.querySelector('input[name="search"]');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            this.closest('form').submit();
        }, 500);
    });
});
</script>
@endsection 