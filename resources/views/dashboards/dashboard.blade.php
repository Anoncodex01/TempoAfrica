@extends('layouts.app')

@section('sidebar')
    <div class="mb-2">
        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mb-2">Main</div>
        <a href="/dashboard" class="flex items-center px-6 py-3 mb-1 rounded-lg font-semibold text-[#d71418] bg-[#f19e00]/10">
            <i class="fas fa-home mr-3"></i> Dashboard
        </a>
        <a href="#" class="flex items-center px-6 py-3 mb-1 rounded-lg text-gray-700 hover:bg-[#f19e00]/10 hover:text-[#d71418] transition">
            <i class="fas fa-bed mr-3"></i> Accommodations
        </a>
        <a href="#" class="flex items-center px-6 py-3 mb-1 rounded-lg text-gray-700 hover:bg-[#f19e00]/10 hover:text-[#d71418] transition">
            <i class="fas fa-building mr-3"></i> Houses
        </a>
        <a href="#" class="flex items-center px-6 py-3 mb-1 rounded-lg text-gray-700 hover:bg-[#f19e00]/10 hover:text-[#d71418] transition">
            <i class="fas fa-calendar-check mr-3"></i> Bookings
        </a>
        <a href="/users" class="flex items-center px-6 py-3 mb-1 rounded-lg text-gray-700 hover:bg-[#f19e00]/10 hover:text-[#d71418] transition">
            <i class="fas fa-users mr-3"></i> Users
        </a>
        <a href="#" class="flex items-center px-6 py-3 mb-1 rounded-lg text-gray-700 hover:bg-[#f19e00]/10 hover:text-[#d71418] transition">
            <i class="fas fa-user-friends mr-3"></i> Customers
        </a>
    </div>
    <div class="border-t border-gray-100 my-4"></div>
    <div>
        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mb-2">Account</div>
        <!-- Add account-related links here if needed -->
    </div>
@endsection

@section('content')
<style>
    /* Custom scrollbar styling */
    .scrollbar-thin::-webkit-scrollbar {
        width: 4px;
    }
    
    .scrollbar-thin::-webkit-scrollbar-track {
        background: #f3f4f6;
        border-radius: 4px;
    }
    
    .scrollbar-thin::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 4px;
    }
    
    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
    
    /* Smooth transitions for all elements */
    * {
        transition: all 0.2s ease-in-out;
    }
    
    /* Card hover effects */
    .metric-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Activity item hover effects */
    .activity-item {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .activity-item:hover {
        transform: translateX(4px);
    }
    
    /* Pagination button effects */
    .pagination-btn {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .pagination-btn:hover:not(:disabled) {
        transform: scale(1.05);
    }
    
    /* Chart container improvements */
    .chart-container {
        position: relative;
        overflow: hidden;
    }
    
    .chart-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(215, 20, 24, 0.1), transparent);
    }
</style>

<main class="flex-1 p-4 md:p-6 bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Welcome Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Welcome back, {{ Auth::guard('user')->user()->name ?? 'Admin' }}! 👋</h1>
                <p class="text-gray-600">Here's what's happening with your Tempo Africa platform today.</p>
            </div>
            <div class="hidden md:flex items-center space-x-3">
                <!-- Time Period Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center space-x-3 px-5 py-3 bg-gradient-to-r from-white to-gray-50 border-2 border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:border-[#d71418]/30 hover:from-white hover:to-gray-50 focus:outline-none focus:ring-4 focus:ring-[#d71418]/20 focus:border-[#d71418] transition-all duration-300 shadow-sm hover:shadow-md">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center">
                            <i class="fas fa-filter text-white text-sm"></i>
                        </div>
                        <span class="text-gray-800">
                            @switch($timePeriod)
                                @case('today')
                                    Today
                                    @break
                                @case('week')
                                    This Week
                                    @break
                                @case('month')
                                    This Month
                                    @break
                                @case('year')
                                    This Year
                                    @break
                                @default
                                    This Year
                            @endswitch
                        </span>
                        <div class="w-5 h-5 bg-gray-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-chevron-down text-xs text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </div>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
                         class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-2xl border border-gray-200 z-50 overflow-hidden">
                        <!-- Dropdown Header -->
                        <div class="px-4 py-3 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5 border-b border-gray-100">
                            <h3 class="text-sm font-semibold text-gray-800">Select Time Period</h3>
                            <p class="text-xs text-gray-500 mt-1">Filter dashboard data</p>
                        </div>
                        
                        <!-- Dropdown Options -->
                        <div class="py-2">
                            <a href="{{ route('dashboard') }}?period=today" 
                               class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-[#d71418]/5 hover:to-[#f19e00]/5 transition-all duration-200 {{ $timePeriod === 'today' ? 'bg-gradient-to-r from-[#d71418]/10 to-[#f19e00]/10 text-[#d71418] border-r-2 border-[#d71418]' : '' }}">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar-day text-white text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium">Today</div>
                                    <div class="text-xs text-gray-500">Current day data</div>
                                </div>
                                @if($timePeriod === 'today')
                                    <i class="fas fa-check text-[#d71418] text-sm"></i>
                                @endif
                            </a>
                            
                            <a href="{{ route('dashboard') }}?period=week" 
                               class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-[#d71418]/5 hover:to-[#f19e00]/5 transition-all duration-200 {{ $timePeriod === 'week' ? 'bg-gradient-to-r from-[#d71418]/10 to-[#f19e00]/10 text-[#d71418] border-r-2 border-[#d71418]' : '' }}">
                                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar-week text-white text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium">This Week</div>
                                    <div class="text-xs text-gray-500">Current week data</div>
                                </div>
                                @if($timePeriod === 'week')
                                    <i class="fas fa-check text-[#d71418] text-sm"></i>
                                @endif
                            </a>
                            
                            <a href="{{ route('dashboard') }}?period=month" 
                               class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-[#d71418]/5 hover:to-[#f19e00]/5 transition-all duration-200 {{ $timePeriod === 'month' ? 'bg-gradient-to-r from-[#d71418]/10 to-[#f19e00]/10 text-[#d71418] border-r-2 border-[#d71418]' : '' }}">
                                <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar-alt text-white text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium">This Month</div>
                                    <div class="text-xs text-gray-500">Current month data</div>
                                </div>
                                @if($timePeriod === 'month')
                                    <i class="fas fa-check text-[#d71418] text-sm"></i>
                                @endif
                            </a>
                            
                            <a href="{{ route('dashboard') }}?period=year" 
                               class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-[#d71418]/5 hover:to-[#f19e00]/5 transition-all duration-200 {{ $timePeriod === 'year' ? 'bg-gradient-to-r from-[#d71418]/10 to-[#f19e00]/10 text-[#d71418] border-r-2 border-[#d71418]' : '' }}">
                                <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar text-white text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium">This Year</div>
                                    <div class="text-xs text-gray-500">Current year data</div>
                                </div>
                                @if($timePeriod === 'year')
                                    <i class="fas fa-check text-[#d71418] text-sm"></i>
                                @endif
                            </a>
                            
                            <a href="{{ route('dashboard') }}?period=all" 
                               class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-[#d71418]/5 hover:to-[#f19e00]/5 transition-all duration-200 {{ $timePeriod === 'all' ? 'bg-gradient-to-r from-[#d71418]/10 to-[#f19e00]/10 text-[#d71418] border-r-2 border-[#d71418]' : '' }}">
                                <div class="w-8 h-8 bg-gradient-to-br from-gray-500 to-gray-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-infinity text-white text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium">All Time</div>
                                    <div class="text-xs text-gray-500">All historical data</div>
                                </div>
                                @if($timePeriod === 'all')
                                    <i class="fas fa-check text-[#d71418] text-sm"></i>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="text-right">
                    <p class="text-sm text-gray-500">Current Time</p>
                    <p class="text-lg font-semibold text-gray-900" id="current-time">{{ now()->format('M d, Y H:i') }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Customers -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 metric-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Customers</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($customerCount) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-green-500 text-sm font-medium">+12%</span>
                        <span class="text-gray-500 text-sm ml-1">from last month</span>
                    </div>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 metric-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-900">TZS {{ number_format($totalRevenue) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-green-500 text-sm font-medium">+8.2%</span>
                        <span class="text-gray-500 text-sm ml-1">from last month</span>
                    </div>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Accommodations -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 metric-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Accommodations</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($accommodationCount) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-blue-500 text-sm font-medium">{{ $activeAccommodations }}</span>
                        <span class="text-gray-500 text-sm ml-1">active</span>
                    </div>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-building text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Houses -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 metric-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Houses</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($houseCount) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-orange-500 text-sm font-medium">{{ $activeHouses }}</span>
                        <span class="text-gray-500 text-sm ml-1">active</span>
                    </div>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-home text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Booking Trends Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <h2 class="text-xl font-bold text-gray-900">Booking Trends</h2>
                    <div class="flex items-center space-x-2 px-3 py-1 bg-gradient-to-r from-[#d71418]/10 to-[#f19e00]/10 rounded-full">
                        <span class="w-2 h-2 bg-gradient-to-r from-[#d71418] to-[#f19e00] rounded-full"></span>
                        <span class="text-xs text-gray-600 font-medium">
                            @switch($timePeriod)
                                @case('today')
                                    Last 24 hours
                                    @break
                                @case('week')
                                    Last 7 days
                                    @break
                                @case('month')
                                    Last 4 weeks
                                    @break
                                @case('year')
                                    Last 12 months
                                    @break
                                @default
                                    Last 7 months
                            @endswitch
                        </span>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="flex items-center space-x-1 px-3 py-1 bg-green-50 rounded-full">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        <span class="text-xs text-green-700 font-medium">+156%</span>
                    </div>
                    <span class="text-xs text-gray-500">vs last period</span>
                </div>
            </div>
            <div class="h-80 relative chart-container">
                <canvas id="bookingTrendsChart"></canvas>
                <!-- Chart overlay for better visual appeal -->
                <div class="absolute inset-0 pointer-events-none">
                    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-transparent via-transparent to-gray-50/30 rounded-b-2xl"></div>
                </div>
            </div>
        </div>

            <!-- Recent Activity -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Recent Activity</h2>
                <a href="#" class="text-sm text-[#d71418] hover:underline font-medium">View all</a>
            </div>
            <div id="activity-container" class="space-y-4 max-h-80 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                @forelse($recentActivities->take(4) as $activity)
                <div class="flex items-start space-x-3 p-3 rounded-xl hover:bg-gray-50 transition-all duration-200 border border-transparent hover:border-gray-200 activity-item">
                    <div class="w-8 h-8 {{ $activity['bg_color'] }} rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                        <i class="{{ $activity['icon'] }} {{ $activity['color'] }} text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate leading-tight">{{ $activity['message'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $activity['time']->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-gray-400 text-3xl mb-2"></i>
                    <p class="text-gray-500 text-sm">No recent activity</p>
                </div>
                @endforelse
            </div>
            
            <!-- Simple Pagination - Previous/Next Only -->
            <div class="mt-6 pt-4 border-t border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Showing <span class="font-medium" id="start-item">1</span> to <span class="font-medium" id="end-item">4</span> of <span class="font-medium" id="total-items">{{ count($recentActivities) }}</span> activities
                    </div>
                    <div class="flex items-center space-x-2">
                        <!-- Previous Button -->
                        <button id="prev-btn" class="flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed pagination-btn" disabled>
                            <i class="fas fa-chevron-left text-xs"></i>
                        </button>
                        
                        <!-- Next Button -->
                        <button id="next-btn" class="flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 pagination-btn">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Top Performing Accommodations -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Top Performing Accommodations</h2>
                <a href="/admin/accommodations" class="text-sm text-[#d71418] hover:underline">View all</a>
            </div>
            <div class="space-y-4">
                @forelse($topAccommodations as $accommodation)
                <div class="flex items-center justify-between p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-building text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $accommodation->name ?? 'Unknown' }}</p>
                            <p class="text-sm text-gray-500">{{ $accommodation->bookings_count ?? 0 }} bookings</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">TZS {{ number_format($accommodation->minimum_price ?? 0) }}</p>
                        <p class="text-xs text-gray-500">per night</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-building text-gray-400 text-3xl mb-2"></i>
                    <p class="text-gray-500 text-sm">No accommodations found</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Booking Status Distribution -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Booking Status</h2>
                <a href="/admin/bookings" class="text-sm text-[#d71418] hover:underline">View all</a>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 rounded-lg bg-yellow-50 border border-yellow-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-white text-sm"></i>
                        </div>
                        <span class="font-medium text-gray-900">Pending</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ $bookingStatuses['pending'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-blue-50 border border-blue-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <span class="font-medium text-gray-900">Confirmed</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ $bookingStatuses['confirmed'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-green-50 border border-green-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-double text-white text-sm"></i>
                        </div>
                        <span class="font-medium text-gray-900">Completed</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ $bookingStatuses['completed'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-red-50 border border-red-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-times text-white text-sm"></i>
                        </div>
                        <span class="font-medium text-gray-900">Cancelled</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ $bookingStatuses['cancelled'] ?? 0 }}</span>
                </div>
                </div>
            </div>
        </div>
    </main>

<!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Update current time
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleDateString('en-US', { 
            month: 'short', 
            day: 'numeric', 
            year: 'numeric' 
        }) + ' ' + now.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        document.getElementById('current-time').textContent = timeString;
    }
    
    updateTime();
    setInterval(updateTime, 60000); // Update every minute

    // Activity Pagination
    const activities = @json($recentActivities);
    const itemsPerPage = 4;
    let currentPage = 1;
    const totalPages = Math.ceil(activities.length / itemsPerPage);
    
    const activityContainer = document.getElementById('activity-container');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const startItem = document.getElementById('start-item');
    const endItem = document.getElementById('end-item');
    const totalItems = document.getElementById('total-items');
    
    function renderActivities() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const pageActivities = activities.slice(startIndex, endIndex);
        
        // Clear container
        activityContainer.innerHTML = '';
        
        // Render activities
        pageActivities.forEach(activity => {
            const activityHtml = `
                <div class="flex items-start space-x-3 p-3 rounded-xl hover:bg-gray-50 transition-all duration-200 border border-transparent hover:border-gray-200 activity-item">
                    <div class="w-8 h-8 ${activity.bg_color} rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                        <i class="${activity.icon} ${activity.color} text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate leading-tight">${activity.message}</p>
                        <p class="text-xs text-gray-500 mt-1">${activity.time}</p>
                    </div>
                </div>
            `;
            activityContainer.innerHTML += activityHtml;
        });
        
        // Update pagination info
        startItem.textContent = startIndex + 1;
        endItem.textContent = Math.min(endIndex, activities.length);
        totalItems.textContent = activities.length;
        
        // Update button states
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages;
        
        if (prevBtn.disabled) {
            prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            prevBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
        
        if (nextBtn.disabled) {
            nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
    
    // Event listeners
    prevBtn.addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            renderActivities();
        }
    });
    
    nextBtn.addEventListener('click', function() {
        if (currentPage < totalPages) {
            currentPage++;
            renderActivities();
        }
    });

    // Booking Trends Chart
            const ctx = document.getElementById('bookingTrendsChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
            labels: @json(array_column($bookingTrends, 'period')),
                    datasets: [{
                        label: 'Bookings',
                data: @json(array_column($bookingTrends, 'count')),
                        borderColor: '#d71418',
                        backgroundColor: 'rgba(215, 20, 24, 0.1)',
                borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#f19e00',
                        pointBorderColor: '#d71418',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#d71418',
                pointHoverBorderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                legend: { 
                    display: false 
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#d71418',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        title: function(context) {
                            return context[0].label + ' Bookings';
                        },
                        label: function(context) {
                            return context.parsed.y + ' bookings';
                        }
                    }
                }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                    grid: { 
                        color: '#f3f4f6',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: 12
                        }
                    }
                        },
                        x: {
                    grid: { 
                        display: false
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: 12
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
                    }
                }
            });
        });
    </script>
@endsection 