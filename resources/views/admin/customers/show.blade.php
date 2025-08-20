@extends('layouts.app')

@section('content')
<main class="flex-1 p-4 md:p-6 bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">Customer Details</h1>
                <p class="text-sm text-gray-600">View customer profile and owned properties</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.customers.edit', $customer) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white text-sm font-semibold rounded-lg hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('admin.customers.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Customer Information</h2>
                            <p class="text-xs text-gray-600">Personal details and contact information</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        @if($customer->photo)
                            <img src="{{ asset('uploads/' . $customer->photo) }}" alt="Customer Photo" class="w-16 h-16 rounded-full object-cover border-4 border-[#d71418] mr-4 shadow-md">
                        @else
                            <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center text-2xl text-gray-500 mr-4">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $customer->first_name }} {{ $customer->last_name }}</h3>
                            <div class="flex items-center space-x-2 mt-1">
                                @if($customer->is_verified)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $customer->email }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Phone</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $customer->phone }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Gender</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ ucfirst($customer->gender) ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Date of Birth</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $customer->dob ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Country</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $customer->country->name ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Province</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $customer->province->name ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Street</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $customer->street->name ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Last Login</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">
                                @if($customer->last_login_at)
                                    {{ \Illuminate\Support\Carbon::parse($customer->last_login_at)->format('M d, Y H:i') }}
                                @else
                                    Never
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Properties -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-home text-white text-sm"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Owned Properties</h2>
                                <p class="text-xs text-gray-600">Accommodations and houses owned by this customer</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $customer->accommodations->count() + $customer->houses->count() }} Total
                            </span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Accommodations Section -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-bed mr-2 text-blue-600"></i>
                                Accommodations ({{ $customer->accommodations->count() }})
                            </h3>
                            <a href="{{ route('admin.accommodations.create') }}?customer_id={{ $customer->id }}" 
                               class="inline-flex items-center px-3 py-1 bg-blue-500 text-white text-xs font-semibold rounded-md hover:bg-blue-600 transition-all duration-200">
                                <i class="fas fa-plus mr-1"></i>Add Accommodation
                            </a>
                        </div>
                        
                        @if($customer->accommodations->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($customer->accommodations as $accommodation)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200">
                                        <div class="flex items-start justify-between mb-2">
                                            <h4 class="font-semibold text-gray-900">{{ $accommodation->name }}</h4>
                                            <div class="flex items-center space-x-1">
                                                @if($accommodation->is_active)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-check-circle mr-1"></i>Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-times-circle mr-1"></i>Inactive
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-2">{{ $accommodation->category ?? 'No category' }}</p>
                                        <p class="text-sm text-gray-500 mb-3">{{ $accommodation->country->name ?? 'No location' }}</p>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-semibold text-gray-900">{{ number_format($accommodation->minimum_price) }} TZS</span>
                                            <a href="{{ route('admin.accommodations.show', $accommodation) }}" 
                                               class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-md hover:bg-gray-200 transition-all duration-200">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3 mx-auto">
                                    <i class="fas fa-bed text-gray-400 text-lg"></i>
                                </div>
                                <p class="text-gray-500 font-medium text-sm">No accommodations found</p>
                                <p class="text-gray-400 text-xs mt-1">This customer hasn't registered any accommodations yet</p>
                            </div>
                        @endif
                    </div>

                    <!-- Houses Section -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-home mr-2 text-green-600"></i>
                                Houses ({{ $customer->houses->count() }})
                            </h3>
                            <a href="{{ route('admin.houses.create') }}?customer_id={{ $customer->id }}" 
                               class="inline-flex items-center px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-md hover:bg-green-600 transition-all duration-200">
                                <i class="fas fa-plus mr-1"></i>Add House
                            </a>
                        </div>
                        
                        @if($customer->houses->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($customer->houses as $house)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200">
                                        <div class="flex items-start justify-between mb-2">
                                            <h4 class="font-semibold text-gray-900">{{ $house->name }}</h4>
                                            <div class="flex items-center space-x-1">
                                                @if($house->is_active)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-check-circle mr-1"></i>Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-times-circle mr-1"></i>Inactive
                                                    </span>
                                                @endif
                                                @if($house->is_approved)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <i class="fas fa-check mr-1"></i>Approved
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-2">{{ $house->category ?? 'No category' }}</p>
                                        <p class="text-sm text-gray-500 mb-3">{{ $house->country->name ?? 'No location' }}</p>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-semibold text-gray-900">{{ number_format($house->price) }} TZS</span>
                                            <a href="{{ route('admin.houses.show', $house) }}" 
                                               class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-md hover:bg-gray-200 transition-all duration-200">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3 mx-auto">
                                    <i class="fas fa-home text-gray-400 text-lg"></i>
                                </div>
                                <p class="text-gray-500 font-medium text-sm">No houses found</p>
                                <p class="text-gray-400 text-xs mt-1">This customer hasn't registered any houses yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
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
                            <p class="text-xs text-gray-600">Common actions for this customer</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('admin.customers.edit', $customer) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-[#d71418] hover:bg-[#b31216] text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-edit mr-2"></i>Edit Customer
                        </a>
                        <a href="{{ route('admin.accommodations.create') }}?customer_id={{ $customer->id }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-bed mr-2"></i>Add Accommodation
                        </a>
                        <a href="{{ route('admin.houses.create') }}?customer_id={{ $customer->id }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-home mr-2"></i>Add House
                        </a>
                    </div>
                </div>
            </div>

            <!-- Customer Stats -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-chart-bar text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Customer Stats</h3>
                            <p class="text-xs text-gray-600">Property ownership statistics</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">Total Properties</span>
                            <span class="text-lg font-bold text-gray-900">{{ $customer->accommodations->count() + $customer->houses->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">Accommodations</span>
                            <span class="text-sm font-semibold text-blue-600">{{ $customer->accommodations->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">Houses</span>
                            <span class="text-sm font-semibold text-green-600">{{ $customer->houses->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">Active Properties</span>
                            <span class="text-sm font-semibold text-green-600">
                                {{ $customer->accommodations->where('is_active', true)->count() + $customer->houses->where('is_active', true)->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Timeline -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-clock text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Timeline</h3>
                            <p class="text-xs text-gray-600">Account activity history</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-plus text-blue-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Account Created</p>
                                <p class="text-xs text-gray-500">{{ $customer->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @if($customer->last_login_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-sign-in-alt text-green-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Last Login</p>
                                <p class="text-xs text-gray-500">{{ \Illuminate\Support\Carbon::parse($customer->last_login_at)->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        @if($customer->is_verified)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-green-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Account Verified</p>
                                <p class="text-xs text-gray-500">{{ $customer->phone_verified_at ? $customer->phone_verified_at->format('M d, Y H:i') : 'Recently' }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection 