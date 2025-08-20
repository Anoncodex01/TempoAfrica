@extends('layouts.app')

@section('content')
<main class="flex-1 p-4 md:p-6 bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">House Bookings</h1>
                <p class="text-sm text-gray-600">Manage and track all house information access bookings</p>
            </div>
            <a href="{{ route('admin.house-bookings.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white text-sm font-semibold rounded-lg hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                New House Booking
            </a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center text-sm">
            <i class="fas fa-check-circle mr-2 text-green-500"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-calendar-check text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600">Total Bookings</p>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($totalBookings) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-credit-card text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600">Paid Bookings</p>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($paidBookings) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-clock text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600">Pending</p>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($pendingBookings) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-money-bill-wave text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600">Total Revenue</p>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($totalRevenue) }} TZS</p>
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
                    <p class="text-xs text-gray-600">Find specific house bookings using the filters below</p>
                </div>
            </div>
        </div>
        <form method="GET" action="{{ route('admin.house-bookings.index') }}" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by reference, house, or customer..." class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                        <option value="">All Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">House</label>
                    <select name="house_id" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                        <option value="">All Houses</option>
                        @foreach($houses as $house)
                            <option value="{{ $house->id }}" {{ request('house_id') == $house->id ? 'selected' : '' }}>
                                {{ $house->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                </div>
            </div>
            <div class="flex items-center space-x-3 mt-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white text-sm font-semibold rounded-lg hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-search mr-2"></i>
                    Apply Filters
                </button>
                <a href="{{ route('admin.house-bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Clear All
                </a>
            </div>
        </form>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-home text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">House Booking List</h2>
                    <p class="text-xs text-gray-600">View and manage all house information access bookings</p>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">House</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($houseBookings as $booking)
                        <tr class="hover:bg-gray-50 transition-all duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-md flex items-center justify-center mr-2">
                                        <i class="fas fa-hashtag text-white text-xs"></i>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900 text-sm">{{ $booking->reference }}</span>
                                        <p class="text-xs text-gray-500">{{ $booking->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center mr-2">
                                        <i class="fas fa-user text-gray-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900 text-sm">
                                            {{ $booking->customer->first_name ?? 'N/A' }} {{ $booking->customer->last_name ?? '' }}
                                        </span>
                                        <p class="text-xs text-gray-500">{{ $booking->customer->email ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">{{ $booking->house->name ?? 'N/A' }}</div>
                                    <div class="text-gray-500">{{ $booking->house->registration_number ?? 'N/A' }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    <div class="font-semibold text-gray-900">
                                        {{ number_format($booking->amount) }} {{ $booking->currency }}
                                    </div>
                                    @if($booking->is_paid)
                                        <div class="text-xs text-green-600">
                                            Paid: {{ number_format($booking->amount_paid) }}
                                        </div>
                                    @else
                                        <div class="text-xs text-red-600">
                                            Unpaid
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($booking->is_paid)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-receipt mr-1"></i>Paid
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $booking->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-1">
                                    <a href="{{ route('admin.house-bookings.show', $booking) }}" class="inline-flex items-center px-2 py-1 bg-blue-500 text-white text-xs font-semibold rounded-md hover:bg-blue-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                    <a href="{{ route('admin.house-bookings.edit', $booking) }}" class="inline-flex items-center px-2 py-1 bg-[#d71418] text-white text-xs font-semibold rounded-md hover:bg-[#b31216] transition-all duration-200 shadow-sm hover:shadow-md">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    @if(!$booking->is_paid)
                                        <button onclick="markAsPaid({{ $booking->id }})" class="inline-flex items-center px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded-md hover:bg-green-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                            <i class="fas fa-check mr-1"></i> Mark Paid
                                        </button>
                                    @endif
                                    <button onclick="deleteBooking({{ $booking->id }})" class="inline-flex items-center px-2 py-1 bg-gray-500 text-white text-xs font-semibold rounded-md hover:bg-gray-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                        <i class="fas fa-home text-gray-400 text-lg"></i>
                                    </div>
                                    <p class="text-gray-500 font-medium text-sm">No house bookings found</p>
                                    <p class="text-gray-400 text-xs mt-1">Try adjusting your search criteria or create a new house booking</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($houseBookings->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $houseBookings->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</main>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Delete House Booking</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">Are you sure you want to delete this house booking? This action cannot be undone.</p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="confirmDelete" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600">
                    Delete
                </button>
                <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function markAsPaid(bookingId) {
    if (confirm('Mark this booking as paid?')) {
        window.location.href = `/admin/house-bookings/${bookingId}/mark-paid`;
    }
}

function deleteBooking(bookingId) {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('confirmDelete').onclick = function() {
        fetch(`/admin/house-bookings/${bookingId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            }
        });
    };
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endsection
