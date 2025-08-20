@extends('layouts.app')

@section('content')
<main class="flex-1 p-6 md:p-10 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Customers</h1>
            <p class="text-gray-600">Manage all registered customers</p>
        </div>
        <a href="{{ route('admin.customers.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white font-semibold rounded-xl shadow-md hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200">
            <i class="fas fa-user-plus mr-2"></i> New Customer
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-users text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600">Total Customers</p>
                    <p class="text-lg font-bold text-gray-900">{{ $total }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-check-circle text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600">Verified</p>
                    <p class="text-lg font-bold text-gray-900">{{ $verified }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-clock text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600">Unverified</p>
                    <p class="text-lg font-bold text-gray-900">{{ $unverified }}</p>
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
                    <p class="text-xs text-gray-600">Find specific customers using the filters below</p>
                </div>
            </div>
        </div>
        <form method="GET" action="{{ route('admin.customers.index') }}" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, phone, email..." class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                    <select name="is_active" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                        <option value="">All</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Country</label>
                    <select name="country_id" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                        <option value="">All Countries</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ request('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Gender</label>
                    <select name="gender" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                        <option value="">All</option>
                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Sort By</label>
                    <select name="sort_by" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-900 focus:border-[#d71418] focus:ring-2 focus:ring-[#d71418]/10 transition-all duration-200">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                        <option value="first_name" {{ request('sort_by') == 'first_name' ? 'selected' : '' }}>First Name</option>
                        <option value="last_name" {{ request('sort_by') == 'last_name' ? 'selected' : '' }}>Last Name</option>
                        <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center space-x-3 mt-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white text-sm font-semibold rounded-lg hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-search mr-2"></i>
                    Apply Filters
                </button>
                <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Clear All
                </a>
            </div>
        </form>
    </div>
    <div id="customer-table-wrapper">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-[#d71418]/10 to-[#f19e00]/10">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Photo</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Country</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Verified</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Last Login</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($customers as $customer)
                    <tr>
                        <td class="px-6 py-4">
                            @if($customer->photo)
                                <img src="{{ asset('storage/' . $customer->photo) }}" alt="Photo" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                            @else
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-500 font-bold">
                                    <i class="fas fa-user"></i>
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $customer->first_name }} {{ $customer->last_name }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $customer->email }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $customer->phone }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $customer->country->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($customer->is_verified)
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold"><i class="fas fa-check-circle mr-1"></i> Verified</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold"><i class="fas fa-clock mr-1"></i> Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            @if($customer->last_login_at)
                                <span class="text-xs">
                                    {{ \Illuminate\Support\Carbon::parse($customer->last_login_at)->format('M d, Y H:i') }}
                                </span>
                            @else
                                <span class="text-xs text-gray-400">Never</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.customers.show', $customer) }}" class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold hover:bg-blue-200 mr-2"><i class="fas fa-eye mr-1"></i> View</a>
                            <a href="{{ route('admin.customers.edit', $customer) }}" class="inline-flex items-center px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-semibold hover:bg-yellow-200 mr-2"><i class="fas fa-edit mr-1"></i> Edit</a>
                            <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this customer?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-100 text-red-700 rounded-lg text-xs font-semibold hover:bg-red-200"><i class="fas fa-trash mr-1"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">No customers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex justify-center">{{ $customers->links() }}</div>
    </div>
</main>
<script>
const form = document.querySelector('form[action="{{ route('admin.customers.index') }}"]');
const tableWrapper = document.getElementById('customer-table-wrapper');
let searchTimeout;

form.addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => ajaxFilter(), 400);
});
form.addEventListener('change', function(e) {
    ajaxFilter();
});

function ajaxFilter() {
    const formData = new FormData(form);
    tableWrapper.innerHTML = '<div class="flex justify-center py-12"><i class="fas fa-spinner fa-spin text-3xl text-[#d71418]"></i></div>';
    fetch(form.action + '?' + new URLSearchParams(formData), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newTable = doc.getElementById('customer-table-wrapper');
        if (newTable) tableWrapper.innerHTML = newTable.innerHTML;
    });
}
</script>
@endsection 