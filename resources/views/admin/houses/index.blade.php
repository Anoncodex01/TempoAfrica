@extends('layouts.app')

@section('content')
<main class="flex-1 p-4 md:p-6 bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">Houses</h1>
                <p class="text-sm text-gray-600">Manage and track all house properties</p>
            </div>
            <a href="{{ route('admin.houses.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white text-sm font-semibold rounded-lg hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                New House
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-home text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600">Total Houses</p>
                    <p class="text-lg font-bold text-gray-900">{{ $houses->total() }}</p>
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
                    <p class="text-lg font-bold text-gray-900">{{ $houses->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-700 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-eye text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600">Visible</p>
                    <p class="text-lg font-bold text-gray-900">{{ $houses->where('is_visible', true)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-star text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600">Featured</p>
                    <p class="text-lg font-bold text-gray-900">{{ $houses->where('is_featured', true)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-700 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-check text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600">Approved</p>
                    <p class="text-lg font-bold text-gray-900">{{ $houses->where('is_approved', true)->count() }}</p>
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
                    <p class="text-xs text-gray-600">Find specific houses using the filters below</p>
                </div>
            </div>
        </div>
        <form method="GET" action="{{ route('admin.houses.index') }}" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, reg. number, unique number..." class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                        <option value="">All</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="visible" {{ request('status') == 'visible' ? 'selected' : '' }}>Visible</option>
                        <option value="featured" {{ request('status') == 'featured' ? 'selected' : '' }}>Featured</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Category</label>
                    <select name="category" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
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
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Featured</label>
                    <select name="is_featured" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                        <option value="">All</option>
                        <option value="1" {{ request('is_featured') == '1' ? 'selected' : '' }}>Featured</option>
                        <option value="0" {{ request('is_featured') == '0' ? 'selected' : '' }}>Not Featured</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center space-x-3 mt-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white text-sm font-semibold rounded-lg hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-search mr-2"></i>
                    Apply Filters
                </button>
                <a href="{{ route('admin.houses.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Clear All
                </a>
            </div>
        </form>
    </div>

    <!-- Houses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($houses as $house)
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-200">
            <!-- House Image -->
            <div class="relative h-48 bg-gray-200">
                @if($house->photo)
                    <img src="{{ asset($house->photo) }}" alt="{{ $house->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fas fa-home text-gray-400 text-3xl"></i>
                    </div>
                @endif
                
                <!-- Status Badges -->
                <div class="absolute top-3 left-3 flex flex-col space-y-1">
                    @if($house->is_featured)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-star mr-1"></i> Featured
                        </span>
                    @endif
                    @if(!$house->is_approved)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            <i class="fas fa-clock mr-1"></i> Pending
                        </span>
                    @endif
                </div>
                
                <!-- Quick Actions -->
                <div class="absolute top-3 right-3 flex space-x-1">
                    @if(!$house->is_approved)
                        <button id="approve-{{ $house->id }}" onclick="approveHouse({{ $house->id }})" class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-md hover:shadow-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed" title="Approve">
                            <i class="fas fa-check text-sm text-green-500" id="approve-icon-{{ $house->id }}"></i>
                        </button>
                    @endif
                    <button id="toggle-status-{{ $house->id }}" onclick="toggleStatus({{ $house->id }})" class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-md hover:shadow-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-power-off text-sm {{ $house->is_active ? 'text-green-500' : 'text-gray-400' }}" id="toggle-status-icon-{{ $house->id }}"></i>
                    </button>
                    <button id="toggle-visibility-{{ $house->id }}" onclick="toggleVisibility({{ $house->id }})" class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-md hover:shadow-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-eye text-sm {{ $house->is_visible ? 'text-blue-500' : 'text-gray-400' }}" id="toggle-visibility-icon-{{ $house->id }}"></i>
                    </button>
                </div>
            </div>
            
            <!-- House Details -->
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <h3 class="font-semibold text-gray-900 text-sm truncate">{{ $house->name }}</h3>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        {{ $house->category }}
                    </span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-xs text-gray-600">
                        <i class="fas fa-map-marker-alt mr-2 text-[#d71418]"></i>
                        <span class="truncate">{{ $house->country->name ?? 'N/A' }}, {{ $house->province->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center text-xs text-gray-600">
                        <i class="fas fa-user mr-2 text-[#d71418]"></i>
                        <span class="truncate">{{ $house->customer->first_name ?? 'N/A' }} {{ $house->customer->last_name ?? '' }}</span>
                    </div>
                    <div class="flex items-center text-xs text-gray-600">
                        <i class="fas fa-dollar-sign mr-2 text-[#d71418]"></i>
                        <span>{{ number_format($house->price) }} {{ $house->currency }}</span>
                    </div>
                    @if($house->number_of_rooms)
                    <div class="flex items-center text-xs text-gray-600">
                        <i class="fas fa-door-open mr-2 text-[#d71418]"></i>
                        <span>{{ $house->number_of_rooms }} rooms</span>
                    </div>
                    @endif
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.houses.show', $house->id) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-500 text-white text-xs font-semibold rounded-lg hover:bg-blue-600 transition-all duration-200">
                        <i class="fas fa-eye mr-1"></i> View
                    </a>
                    <a href="{{ route('admin.houses.edit', $house->id) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-[#d71418] text-white text-xs font-semibold rounded-lg hover:bg-[#b31216] transition-all duration-200">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    @if(!$house->is_approved)
                        <form method="POST" action="{{ route('admin.houses.approve', $house->id) }}" class="inline">
                            @csrf
                            <button type="submit" onclick="return confirm('Are you sure you want to approve this house?')" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-green-500 text-white text-xs font-semibold rounded-lg hover:bg-green-600 transition-all duration-200">
                                <i class="fas fa-check mr-1"></i> Approve
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.houses.destroy', $house->id) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this house?')" class="w-full inline-flex items-center justify-center px-3 py-2 bg-gray-500 text-white text-xs font-semibold rounded-lg hover:bg-gray-600 transition-all duration-200">
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
                    <i class="fas fa-home text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500 font-medium text-sm">No houses found</p>
                <p class="text-gray-400 text-xs mt-1">Try adjusting your search criteria or create a new house</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($houses->hasPages())
        <div class="mt-6">
            {{ $houses->appends(request()->query())->links() }}
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

    fetch(`/admin/houses/${id}/toggle-status`, {
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
            alert('Failed to toggle status: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        statusIcon.classList.remove('hidden');
        statusButton.disabled = false;
        console.error('Error toggling status:', error);
        alert('Error toggling status: ' + error.message);
    });
}

function toggleVisibility(id) {
    const visibilityButton = document.getElementById(`toggle-visibility-${id}`);
    const visibilityIcon = document.getElementById(`toggle-visibility-icon-${id}`);

    visibilityButton.disabled = true;
    visibilityIcon.classList.add('hidden');

    fetch(`/admin/houses/${id}/toggle-visibility`, {
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
            alert('Failed to toggle visibility: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        visibilityIcon.classList.remove('hidden');
        visibilityButton.disabled = false;
        console.error('Error toggling visibility:', error);
        alert('Error toggling visibility: ' + error.message);
    });
}

function approveHouse(id) {
    if (!confirm('Are you sure you want to approve this house?')) {
        return;
    }

    const approveButton = document.getElementById(`approve-${id}`);
    const approveIcon = document.getElementById(`approve-icon-${id}`);

    if (!approveButton || !approveIcon) {
        console.error('Approve button elements not found');
        alert('Error: Approve button elements not found');
        return;
    }

    approveButton.disabled = true;
    approveIcon.classList.add('fa-spinner', 'fa-spin');

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        console.error('CSRF token not found');
        alert('Error: CSRF token not found');
        approveButton.disabled = false;
        approveIcon.classList.remove('fa-spinner', 'fa-spin');
        return;
    }

    fetch(`/admin/houses/${id}/approve`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (response.ok) {
            location.reload();
        } else {
            return response.text().then(text => {
                throw new Error(`HTTP ${response.status}: ${text}`);
            });
        }
    })
    .catch(error => {
        approveButton.disabled = false;
        approveIcon.classList.remove('fa-spinner', 'fa-spin');
        console.error('Error approving house:', error);
        alert('Error approving house: ' + error.message);
    });
}

// Auto-submit form when certain filters change
document.addEventListener('DOMContentLoaded', function() {
    const autoSubmitSelects = document.querySelectorAll('select[name="status"], select[name="category"], select[name="is_visible"], select[name="is_featured"]');
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

    // Hide all spinners on page load (safety)
    document.querySelectorAll('.fa-spinner').forEach(function(spinner) {
        spinner.classList.add('hidden');
    });
});
</script>
@endsection 