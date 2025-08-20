@extends('layouts.app')

@section('content')
<main class="flex-1 p-4 md:p-6 bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">House Details</h1>
                <p class="text-sm text-gray-600">View comprehensive house information and manage settings</p>
            </div>
            <div class="flex items-center space-x-3">
                @if(!$house->is_approved)
                    <form method="POST" action="{{ route('admin.houses.approve', $house->id) }}" class="inline">
                        @csrf
                        <button type="submit" onclick="return confirm('Are you sure you want to approve this house?')" class="inline-flex items-center px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition-all duration-200 shadow-md">
                            <i class="fas fa-check mr-2"></i> Approve
                        </button>
                    </form>
                @endif
                <a href="{{ route('admin.houses.edit', $house->id) }}" class="inline-flex items-center px-4 py-2 bg-[#d71418] text-white text-sm font-semibold rounded-lg hover:bg-[#b31216] transition-all duration-200 shadow-md">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <a href="{{ route('admin.houses.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- House Image and Basic Info -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="relative h-64 bg-gray-200">
                                    @if($house->photo)
                    <img src="{{ asset($house->photo) }}" alt="{{ $house->name }}" class="w-full h-full object-cover">
                @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-home text-gray-400 text-6xl"></i>
                        </div>
                    @endif
                    
                    <!-- Status Badges -->
                    <div class="absolute top-4 left-4 flex flex-col space-y-2">
                        @if($house->is_featured)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-star mr-1"></i> Featured
                            </span>
                        @endif
                        @if(!$house->is_approved)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                <i class="fas fa-clock mr-1"></i> Pending Approval
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $house->name }}</h2>
                            <p class="text-gray-600">{{ $house->category ?? 'No category' }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-[#d71418]">{{ number_format($house->price) }} {{ $house->currency }}</div>
                            <div class="text-sm text-gray-500">{{ $house->price_duration ?? 'per period' }}</div>
                        </div>
                    </div>
                    
                    <!-- Status Indicators -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $house->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            <i class="fas fa-power-off mr-1"></i> {{ $house->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $house->is_visible ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                            <i class="fas fa-eye mr-1"></i> {{ $house->is_visible ? 'Visible' : 'Hidden' }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $house->is_approved ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            <i class="fas fa-check mr-1"></i> {{ $house->is_approved ? 'Approved' : 'Not Approved' }}
                        </span>
                        @if($house->is_booked)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                <i class="fas fa-calendar-check mr-1"></i> Booked
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detailed Information -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-[#d71418]"></i>
                    Property Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Registration Number</label>
                            <p class="text-sm font-medium text-gray-900">{{ $house->registration_number ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Unique Number</label>
                            <p class="text-sm font-medium text-gray-900">{{ $house->unique_number ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Number of Rooms</label>
                            <p class="text-sm font-medium text-gray-900">{{ $house->number_of_rooms ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Minimum Rent Duration</label>
                            <p class="text-sm font-medium text-gray-900">{{ $house->minimum_rent_duration ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Booking Price</label>
                            <p class="text-sm font-medium text-gray-900">{{ $house->booking_price ? number_format($house->booking_price) . ' ' . $house->currency : 'Not set' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Fee</label>
                            <p class="text-sm font-medium text-gray-900">{{ $house->fee ? number_format($house->fee) . ' ' . $house->currency : 'Not set' }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Location</label>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $house->street->name ?? 'Street not specified' }}, {{ $house->district->name ?? 'District not specified' }}, {{ $house->province->name ?? 'Province not specified' }}, {{ $house->country->name ?? 'Country not specified' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Coordinates</label>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $house->latitude ?? 'Not set' }} / {{ $house->longitude ?? 'Not set' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Available From</label>
                            <p class="text-sm font-medium text-gray-900">{{ $house->from_date ? \Carbon\Carbon::parse($house->from_date)->format('M d, Y') : 'Not specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Available Until</label>
                            <p class="text-sm font-medium text-gray-900">{{ $house->to_date ? \Carbon\Carbon::parse($house->to_date)->format('M d, Y') : 'Not specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Created</label>
                            <p class="text-sm font-medium text-gray-900">{{ $house->created_at ? $house->created_at->format('M d, Y \a\t H:i') : 'Unknown' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Last Updated</label>
                            <p class="text-sm font-medium text-gray-900">{{ $house->updated_at ? $house->updated_at->format('M d, Y \a\t H:i') : 'Unknown' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Amenities -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-tools mr-2 text-[#d71418]"></i>
                    Amenities & Features
                </h3>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="flex items-center space-x-3 p-3 rounded-lg {{ $house->has_water ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200' }}">
                        <i class="fas fa-tint text-lg {{ $house->has_water ? 'text-green-500' : 'text-gray-400' }}"></i>
                        <div>
                            <p class="text-sm font-medium {{ $house->has_water ? 'text-green-700' : 'text-gray-500' }}">Water</p>
                            <p class="text-xs {{ $house->has_water ? 'text-green-600' : 'text-gray-400' }}">{{ $house->has_water ? 'Available' : 'Not available' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3 p-3 rounded-lg {{ $house->has_electricity ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200' }}">
                        <i class="fas fa-bolt text-lg {{ $house->has_electricity ? 'text-green-500' : 'text-gray-400' }}"></i>
                        <div>
                            <p class="text-sm font-medium {{ $house->has_electricity ? 'text-green-700' : 'text-gray-500' }}">Electricity</p>
                            <p class="text-xs {{ $house->has_electricity ? 'text-green-600' : 'text-gray-400' }}">{{ $house->has_electricity ? 'Available' : 'Not available' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3 p-3 rounded-lg {{ $house->has_fence ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200' }}">
                        <i class="fas fa-shield-alt text-lg {{ $house->has_fence ? 'text-green-500' : 'text-gray-400' }}"></i>
                        <div>
                            <p class="text-sm font-medium {{ $house->has_fence ? 'text-green-700' : 'text-gray-500' }}">Fence</p>
                            <p class="text-xs {{ $house->has_fence ? 'text-green-600' : 'text-gray-400' }}">{{ $house->has_fence ? 'Available' : 'Not available' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3 p-3 rounded-lg {{ $house->has_public_transport ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200' }}">
                        <i class="fas fa-bus text-lg {{ $house->has_public_transport ? 'text-green-500' : 'text-gray-400' }}"></i>
                        <div>
                            <p class="text-sm font-medium {{ $house->has_public_transport ? 'text-green-700' : 'text-gray-500' }}">Public Transport</p>
                            <p class="text-xs {{ $house->has_public_transport ? 'text-green-600' : 'text-gray-400' }}">{{ $house->has_public_transport ? 'Available' : 'Not available' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($house->description)
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-align-left mr-2 text-[#d71418]"></i>
                    Description
                </h3>
                <p class="text-gray-700 leading-relaxed">{{ $house->description }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Owner Information -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user mr-2 text-[#d71418]"></i>
                    Property Owner
                </h3>
                
                @if($house->customer)
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900">{{ $house->customer->first_name }} {{ $house->customer->last_name }}</h4>
                    <p class="text-sm text-gray-600">{{ $house->customer->email ?? 'No email' }}</p>
                    <p class="text-sm text-gray-600">{{ $house->customer->phone ?? 'No phone' }}</p>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-user-slash text-gray-400 text-3xl mb-2"></i>
                    <p class="text-gray-500 text-sm">No owner assigned</p>
                </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-cogs mr-2 text-[#d71418]"></i>
                    Quick Actions
                </h3>
                
                <div class="space-y-3">
                    <button onclick="toggleHouseStatus({{ $house->id }}, 'status')" class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <div class="flex items-center">
                            <i class="fas fa-power-off mr-3 {{ $house->is_active ? 'text-green-500' : 'text-gray-400' }}"></i>
                            <span class="text-sm font-medium">Toggle Active</span>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full {{ $house->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $house->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </button>
                    
                    <button onclick="toggleHouseStatus({{ $house->id }}, 'visibility')" class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <div class="flex items-center">
                            <i class="fas fa-eye mr-3 {{ $house->is_visible ? 'text-blue-500' : 'text-gray-400' }}"></i>
                            <span class="text-sm font-medium">Toggle Visibility</span>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full {{ $house->is_visible ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $house->is_visible ? 'Visible' : 'Hidden' }}
                        </span>
                    </button>
                    
                    <button onclick="toggleHouseStatus({{ $house->id }}, 'featured')" class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <div class="flex items-center">
                            <i class="fas fa-star mr-3 {{ $house->is_featured ? 'text-yellow-500' : 'text-gray-400' }}"></i>
                            <span class="text-sm font-medium">Toggle Featured</span>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full {{ $house->is_featured ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $house->is_featured ? 'Featured' : 'Not Featured' }}
                        </span>
                    </button>
                    
                    @if(!$house->is_approved)
                    <button onclick="approveHouse({{ $house->id }})" class="w-full flex items-center justify-between px-4 py-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors duration-200">
                        <div class="flex items-center">
                            <i class="fas fa-check mr-3 text-green-500"></i>
                            <span class="text-sm font-medium">Approve House</span>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700">Pending</span>
                    </button>
                    @endif
                </div>
            </div>

            <!-- Related Actions -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-link mr-2 text-[#d71418]"></i>
                    Related Actions
                </h3>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.houses.photos.index', $house->id) }}" class="flex items-center px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors duration-200">
                        <i class="fas fa-images mr-3 text-blue-500"></i>
                        <span class="text-sm font-medium">Manage Photos</span>
                    </a>
                    
                    <a href="{{ route('admin.houses.rooms.index', $house->id) }}" class="flex items-center px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors duration-200">
                        <i class="fas fa-door-open mr-3 text-purple-500"></i>
                        <span class="text-sm font-medium">Manage Rooms</span>
                    </a>
                    
                    <form method="POST" action="{{ route('admin.houses.destroy', $house->id) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this house? This action cannot be undone.')" class="w-full flex items-center px-4 py-3 bg-red-50 hover:bg-red-100 rounded-lg transition-colors duration-200 text-left">
                            <i class="fas fa-trash mr-3 text-red-500"></i>
                            <span class="text-sm font-medium">Delete House</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function toggleHouseStatus(id, type) {
    const endpoint = `/admin/houses/${id}/toggle-${type}`;
    
    fetch(endpoint, {
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
            alert('Failed to toggle ' + type + ': ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error toggling ' + type + ':', error);
        alert('Error toggling ' + type + ': ' + error.message);
    });
}

function approveHouse(id) {
    if (!confirm('Are you sure you want to approve this house?')) {
        return;
    }

    fetch(`/admin/houses/${id}/approve`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        if (response.ok) {
            location.reload();
        } else {
            return response.text().then(text => {
                throw new Error(`HTTP ${response.status}: ${text}`);
            });
        }
    })
    .catch(error => {
        console.error('Error approving house:', error);
        alert('Error approving house: ' + error.message);
    });
}
</script>
@endsection 