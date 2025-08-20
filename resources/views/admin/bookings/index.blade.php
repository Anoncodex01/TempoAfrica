@extends('layouts.app')

@section('content')
<main class="flex-1 p-4 md:p-6 bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">Bookings</h1>
                <p class="text-sm text-gray-600">Manage and track all accommodation bookings</p>
            </div>
            <a href="{{ route('admin.bookings.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white text-sm font-semibold rounded-lg hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                New Booking
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
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
                <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-times-circle text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600">Cancelled</p>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($cancelledBookings) }}</p>
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
                    <p class="text-xs text-gray-600">Find specific bookings using the filters below</p>
                </div>
            </div>
        </div>
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by reference, customer, or accommodation..." class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                        <option value="">All</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="checked_in" {{ request('status') == 'checked_in' ? 'selected' : '' }}>Checked In</option>
                        <option value="checked_out" {{ request('status') == 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Accommodation</label>
                    <select name="accommodation_id" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                        <option value="">All Accommodations</option>
                        @foreach($accommodations as $accommodation)
                            <option value="{{ $accommodation->id }}" {{ request('accommodation_id') == $accommodation->id ? 'selected' : '' }}>
                                {{ $accommodation->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Sort By</label>
                    <select name="sort_by" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                        <option value="from_date" {{ request('sort_by') == 'from_date' ? 'selected' : '' }}>Check-in Date</option>
                        <option value="amount" {{ request('sort_by') == 'amount' ? 'selected' : '' }}>Amount</option>
                        <option value="reference" {{ request('sort_by') == 'reference' ? 'selected' : '' }}>Reference</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Sort Order</label>
                    <select name="sort_order" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center space-x-3 mt-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white text-sm font-semibold rounded-lg hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-search mr-2"></i>
                    Apply Filters
                </button>
                <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200">
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
                    <i class="fas fa-calendar-alt text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Booking List</h2>
                    <p class="text-xs text-gray-600">View and manage all bookings in the system</p>
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Accommodation</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Room</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($bookings as $booking)
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
                                    <span class="font-semibold text-gray-900 text-sm">{{ $booking->customer->name ?? 'N/A' }}</span>
                                    <p class="text-xs text-gray-500">{{ $booking->customer->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $booking->accommodation->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $booking->accommodationRoom->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">
                                    {{ $booking->from_date ? $booking->from_date->format('M d, Y') : 'N/A' }}
                                </div>
                                <div class="text-gray-500">
                                    to {{ $booking->to_date ? $booking->to_date->format('M d, Y') : 'N/A' }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $booking->duration }} nights
                                </div>
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
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'paid' => 'bg-blue-100 text-blue-800',
                                    'checked_in' => 'bg-green-100 text-green-800',
                                    'checked_out' => 'bg-gray-100 text-gray-800',
                                    'cancelled' => 'bg-red-100 text-red-800'
                                ];
                                $statusIcons = [
                                    'pending' => 'fas fa-clock',
                                    'paid' => 'fas fa-credit-card',
                                    'checked_in' => 'fas fa-sign-in-alt',
                                    'checked_out' => 'fas fa-sign-out-alt',
                                    'cancelled' => 'fas fa-times-circle'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$booking->status] }}">
                                <i class="{{ $statusIcons[$booking->status] }} mr-1"></i>
                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-1">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="inline-flex items-center px-2 py-1 bg-blue-500 text-white text-xs font-semibold rounded-md hover:bg-blue-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                                <a href="{{ route('admin.bookings.edit', $booking) }}" class="inline-flex items-center px-2 py-1 bg-[#d71418] text-white text-xs font-semibold rounded-md hover:bg-[#b31216] transition-all duration-200 shadow-sm hover:shadow-md">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this booking?')" class="inline-flex items-center px-2 py-1 bg-gray-500 text-white text-xs font-semibold rounded-md hover:bg-gray-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                    <i class="fas fa-calendar-alt text-gray-400 text-lg"></i>
                                </div>
                                <p class="text-gray-500 font-medium text-sm">No bookings found</p>
                                <p class="text-gray-400 text-xs mt-1">Try adjusting your search criteria or create a new booking</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6 mt-6 rounded-xl shadow-lg border border-gray-100">
            {{ $bookings->appends(request()->query())->links() }}
        </div>
    @endif
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when certain filters change
    const autoSubmitSelects = document.querySelectorAll('select[name="status"], select[name="accommodation_id"], select[name="sort_by"], select[name="sort_order"]');
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

    // Date range validation
    const dateFrom = document.querySelector('input[name="date_from"]');
    const dateTo = document.querySelector('input[name="date_to"]');
    
    if (dateFrom && dateTo) {
        dateFrom.addEventListener('change', function() {
            if (dateTo.value && this.value > dateTo.value) {
                dateTo.value = this.value;
            }
        });
        
        dateTo.addEventListener('change', function() {
            if (dateFrom.value && this.value < dateFrom.value) {
                dateFrom.value = this.value;
            }
        });
    }

    // Amount range validation
    const amountMin = document.querySelector('input[name="amount_min"]');
    const amountMax = document.querySelector('input[name="amount_max"]');
    
    if (amountMin && amountMax) {
        amountMin.addEventListener('input', function() {
            if (amountMax.value && parseFloat(this.value) > parseFloat(amountMax.value)) {
                amountMax.value = this.value;
            }
        });
        
        amountMax.addEventListener('input', function() {
            if (amountMin.value && parseFloat(this.value) < parseFloat(amountMin.value)) {
                amountMin.value = this.value;
            }
        });
    }

    // Highlight active filters
    const activeFilters = document.querySelectorAll('input[value], select option:checked');
    activeFilters.forEach(filter => {
        if (filter.value && filter.value !== '') {
            const container = filter.closest('div');
            if (container) {
                container.classList.add('ring-2', 'ring-[#d71418]/20');
            }
        }
    });

    // Show loading state on form submit
    const form = document.querySelector('form');
    form.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
            submitBtn.disabled = true;
        }
    });
});
</script>
@endsection 