@extends('layouts.app')

@section('content')
<main class="flex-1 p-4 md:p-6 bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">House Booking Details</h1>
                <p class="text-sm text-gray-600">Reference: {{ $houseBooking->reference }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.house-bookings.edit', $houseBooking) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white text-sm font-semibold rounded-lg hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('admin.house-bookings.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Information -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-check text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Booking Information</h2>
                            <p class="text-xs text-gray-600">Details about this house information access booking</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Reference</label>
                            <p class="mt-1 text-sm text-gray-900 font-mono bg-gray-50 px-3 py-2 rounded-lg">{{ $houseBooking->reference }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                            <div class="mt-1">
                                @if($houseBooking->is_paid)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-receipt mr-1"></i>Paid
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Amount</label>
                            <p class="mt-1 text-sm text-gray-900 font-semibold bg-gray-50 px-3 py-2 rounded-lg">{{ number_format($houseBooking->amount) }} {{ $houseBooking->currency }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Amount Paid</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">
                                @if($houseBooking->amount_paid)
                                    {{ number_format($houseBooking->amount_paid) }} {{ $houseBooking->currency }}
                                @else
                                    <span class="text-red-600">Not paid</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Created Date</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $houseBooking->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Paid Date</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">
                                @if($houseBooking->paid_at)
                                    {{ $houseBooking->paid_at->format('M d, Y H:i') }}
                                @else
                                    <span class="text-gray-500">Not paid yet</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- House Information -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-home text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">House Information</h2>
                            <p class="text-xs text-gray-600">Details about the house being accessed</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">House Name</label>
                            <p class="mt-1 text-sm text-gray-900 font-semibold bg-gray-50 px-3 py-2 rounded-lg">{{ $houseBooking->house->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Registration Number</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $houseBooking->house->registration_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Unique Number</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $houseBooking->house->unique_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Category</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $houseBooking->house->category ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Location</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">
                                @if($houseBooking->house->location)
                                    {{ $houseBooking->house->location->name ?? 'N/A' }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Price</label>
                            <p class="mt-1 text-sm text-gray-900 font-semibold bg-gray-50 px-3 py-2 rounded-lg">{{ number_format($houseBooking->house->price ?? 0) }} TZS</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Customer Information</h2>
                            <p class="text-xs text-gray-600">Details about the customer accessing house information</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Full Name</label>
                            <p class="mt-1 text-sm text-gray-900 font-semibold bg-gray-50 px-3 py-2 rounded-lg">
                                {{ $houseBooking->customer->first_name ?? 'N/A' }} {{ $houseBooking->customer->last_name ?? '' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $houseBooking->customer->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Phone</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $houseBooking->customer->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Address</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $houseBooking->customer->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            @if($houseBooking->payment_token || $houseBooking->payment_url)
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-credit-card text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Payment Details</h2>
                            <p class="text-xs text-gray-600">Payment gateway and receipt information</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($houseBooking->payment_token)
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Payment Token</label>
                            <p class="mt-1 text-sm text-gray-900 font-mono bg-gray-50 px-3 py-2 rounded-lg break-all">{{ $houseBooking->payment_token }}</p>
                        </div>
                        @endif
                        @if($houseBooking->payment_url)
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Payment URL</label>
                            <a href="{{ $houseBooking->payment_url }}" target="_blank" 
                               class="mt-1 text-sm text-blue-600 hover:text-blue-800 bg-gray-50 px-3 py-2 rounded-lg break-all block">
                                {{ $houseBooking->payment_url }}
                            </a>
                        </div>
                        @endif
                        @if($houseBooking->receipt_url)
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Receipt URL</label>
                            <a href="{{ $houseBooking->receipt_url }}" target="_blank" 
                               class="mt-1 text-sm text-blue-600 hover:text-blue-800 bg-gray-50 px-3 py-2 rounded-lg break-all block">
                                {{ $houseBooking->receipt_url }}
                            </a>
                        </div>
                        @endif
                        @if($houseBooking->receipt_filename)
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Receipt Filename</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $houseBooking->receipt_filename }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-bolt text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                            <p class="text-xs text-gray-600">Common actions for this booking</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @if(!$houseBooking->is_paid)
                            <a href="{{ route('admin.house-bookings.mark-paid', $houseBooking) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-check mr-2"></i>Mark as Paid
                            </a>
                        @endif
                        <a href="{{ route('admin.house-bookings.edit', $houseBooking) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-[#d71418] hover:bg-[#b31216] text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-edit mr-2"></i>Edit Booking
                        </a>
                        <button onclick="deleteBooking({{ $houseBooking->id }})" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-trash mr-2"></i>Delete Booking
                        </button>
                    </div>
                </div>
            </div>

            <!-- Related Actions -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-link text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Related Actions</h3>
                            <p class="text-xs text-gray-600">Navigate to related pages</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @if($houseBooking->house)
                            <a href="{{ route('admin.houses.show', $houseBooking->house) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-home mr-2"></i>View House
                            </a>
                        @endif
                        @if($houseBooking->customer)
                            <a href="{{ route('admin.customers.show', $houseBooking->customer) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-user mr-2"></i>View Customer
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Booking Timeline -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-clock text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Timeline</h3>
                            <p class="text-xs text-gray-600">Booking activity history</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-calendar-plus text-blue-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Booking Created</p>
                                <p class="text-xs text-gray-500">{{ $houseBooking->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @if($houseBooking->paid_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-green-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Payment Completed</p>
                                <p class="text-xs text-gray-500">{{ $houseBooking->paid_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
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
                window.location.href = '{{ route("admin.house-bookings.index") }}';
            }
        });
    };
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endsection
