<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Accommodation;
use App\Models\Booking;
use App\Models\House;
use App\Models\HouseBooking;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        // Get time period filter from request, default to 'year'
        $timePeriod = request('period', 'year');
        
        // Set date range based on time period
        $dateRange = $this->getDateRange($timePeriod);
        
        // Basic counts with time filter
        $userCount = User::when($dateRange, function($query) use ($dateRange) {
            return $query->whereBetween('created_at', $dateRange);
        })->count();
        
        $accommodationCount = Accommodation::when($dateRange, function($query) use ($dateRange) {
            return $query->whereBetween('created_at', $dateRange);
        })->count();
        
        $bookingCount = Booking::when($dateRange, function($query) use ($dateRange) {
            return $query->whereBetween('created_at', $dateRange);
        })->count();
        
        $houseCount = House::when($dateRange, function($query) use ($dateRange) {
            return $query->whereBetween('created_at', $dateRange);
        })->count();
        
        $customerCount = Customer::when($dateRange, function($query) use ($dateRange) {
            return $query->whereBetween('created_at', $dateRange);
        })->count();

        // Active counts (not time-dependent, but included for consistency)
        $activeAccommodations = Accommodation::where('is_active', true)->count();
        $activeHouses = House::where('is_active', true)->count();
        $approvedAccommodations = Accommodation::where('is_approved', true)->count();
        $approvedHouses = House::where('is_approved', true)->count();

        // Recent activities with time filter
        $recentCustomers = Customer::when($dateRange, function($query) use ($dateRange) {
            return $query->whereBetween('created_at', $dateRange);
        })->latest()->take(5)->get();
        
        $recentBookings = Booking::with(['customer', 'accommodation'])
            ->when($dateRange, function($query) use ($dateRange) {
                return $query->whereBetween('created_at', $dateRange);
            })->latest()->take(5)->get();
        
        $recentAccommodations = Accommodation::with('customer')
            ->when($dateRange, function($query) use ($dateRange) {
                return $query->whereBetween('created_at', $dateRange);
            })->latest()->take(5)->get();
        
        $recentHouses = House::with('customer')
            ->when($dateRange, function($query) use ($dateRange) {
                return $query->whereBetween('created_at', $dateRange);
            })->latest()->take(5)->get();

        // Revenue calculation with time filter
        $regularBookingsRevenue = Booking::where('is_paid', true)
            ->when($dateRange, function($query) use ($dateRange) {
                return $query->whereBetween('created_at', $dateRange);
            })->sum('amount') ?? 0;
        
        $houseBookingsRevenue = HouseBooking::where('is_paid', true)
            ->when($dateRange, function($query) use ($dateRange) {
                return $query->whereBetween('created_at', $dateRange);
            })->sum('amount') ?? 0;
        
        $totalRevenue = $regularBookingsRevenue + $houseBookingsRevenue;
        
        // Monthly revenue (current month only)
        $monthlyRegularRevenue = Booking::where('is_paid', true)->whereMonth('created_at', Carbon::now()->month)->sum('amount') ?? 0;
        $monthlyHouseRevenue = HouseBooking::where('is_paid', true)->whereMonth('created_at', Carbon::now()->month)->sum('amount') ?? 0;
        $monthlyRevenue = $monthlyRegularRevenue + $monthlyHouseRevenue;
        
        // Weekly revenue (current week only)
        $weeklyRegularRevenue = Booking::where('is_paid', true)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('amount') ?? 0;
        $weeklyHouseRevenue = HouseBooking::where('is_paid', true)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('amount') ?? 0;
        $weeklyRevenue = $weeklyRegularRevenue + $weeklyHouseRevenue;

        // Booking trends based on time period
        $bookingTrends = $this->getBookingTrends($timePeriod);

        // Recent activities for activity feed with time filter
        $recentActivities = collect();
        
        // Add recent customers
        foreach ($recentCustomers as $customer) {
            $recentActivities->push([
                'type' => 'customer',
                'message' => 'New customer registered: ' . ($customer->first_name ?? 'Unknown') . ' ' . ($customer->last_name ?? ''),
                'time' => $customer->created_at,
                'icon' => 'fas fa-user-plus',
                'color' => 'text-blue-500',
                'bg_color' => 'bg-blue-50'
            ]);
        }

        // Add recent bookings
        foreach ($recentBookings as $booking) {
            $recentActivities->push([
                'type' => 'booking',
                'message' => 'New booking created for ' . ($booking->accommodation->name ?? 'Unknown accommodation'),
                'time' => $booking->created_at,
                'icon' => 'fas fa-calendar-check',
                'color' => 'text-green-500',
                'bg_color' => 'bg-green-50'
            ]);
        }

        // Add recent accommodations
        foreach ($recentAccommodations as $accommodation) {
            $recentActivities->push([
                'type' => 'accommodation',
                'message' => 'New accommodation added: ' . ($accommodation->name ?? 'Unknown'),
                'time' => $accommodation->created_at,
                'icon' => 'fas fa-building',
                'color' => 'text-purple-500',
                'bg_color' => 'bg-purple-50'
            ]);
        }

        // Add recent houses
        foreach ($recentHouses as $house) {
            $recentActivities->push([
                'type' => 'house',
                'message' => 'New house added: ' . ($house->name ?? 'Unknown'),
                'time' => $house->created_at,
                'icon' => 'fas fa-home',
                'color' => 'text-orange-500',
                'bg_color' => 'bg-orange-50'
            ]);
        }

        // Sort activities by time and take the most recent 10
        $recentActivities = $recentActivities->sortByDesc('time')->take(10);

        // Top performing accommodations with time filter
        $topAccommodations = Accommodation::withCount(['bookings' => function($query) use ($dateRange) {
            if ($dateRange) {
                $query->whereBetween('created_at', $dateRange);
            }
        }])->orderBy('bookings_count', 'desc')->take(5)->get();

        // Booking status distribution with time filter
        $bookingStatuses = [
            'pending' => Booking::where('is_paid', false)->where('is_cancelled', false)
                ->when($dateRange, function($query) use ($dateRange) {
                    return $query->whereBetween('created_at', $dateRange);
                })->count(),
            'confirmed' => Booking::where('is_paid', true)->where('is_checked_in', false)->where('is_cancelled', false)
                ->when($dateRange, function($query) use ($dateRange) {
                    return $query->whereBetween('created_at', $dateRange);
                })->count(),
            'completed' => Booking::where('is_checked_out', true)
                ->when($dateRange, function($query) use ($dateRange) {
                    return $query->whereBetween('created_at', $dateRange);
                })->count(),
            'cancelled' => Booking::where('is_cancelled', true)
                ->when($dateRange, function($query) use ($dateRange) {
                    return $query->whereBetween('created_at', $dateRange);
                })->count(),
        ];

        return view('dashboards.dashboard', compact(
            'userCount',
            'accommodationCount',
            'bookingCount',
            'houseCount',
            'customerCount',
            'activeAccommodations',
            'activeHouses',
            'approvedAccommodations',
            'approvedHouses',
            'totalRevenue',
            'monthlyRevenue',
            'weeklyRevenue',
            'bookingTrends',
            'recentActivities',
            'recentCustomers',
            'recentBookings',
            'topAccommodations',
            'bookingStatuses',
            'timePeriod'
        ));
    }

    /**
     * Get date range based on time period
     */
    private function getDateRange($timePeriod)
    {
        switch ($timePeriod) {
            case 'today':
                return [Carbon::today(), Carbon::today()->endOfDay()];
            case 'week':
                return [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
            case 'month':
                return [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
            case 'year':
                return [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()];
            default:
                return null; // All time
        }
    }

    /**
     * Get booking trends based on time period
     */
    private function getBookingTrends($timePeriod)
    {
        switch ($timePeriod) {
            case 'today':
                // Last 24 hours in 4-hour intervals
                $trends = [];
                for ($i = 5; $i >= 0; $i--) {
                    $hour = Carbon::now()->subHours($i * 4);
                    $trends[] = [
                        'period' => $hour->format('H:i'),
                        'count' => Booking::whereBetween('created_at', [
                            $hour,
                            $hour->copy()->addHours(4)
                        ])->count()
                    ];
                }
                return $trends;
                
            case 'week':
                // Last 7 days
                $trends = [];
                for ($i = 6; $i >= 0; $i--) {
                    $day = Carbon::now()->subDays($i);
                    $trends[] = [
                        'period' => $day->format('D'),
                        'count' => Booking::whereDate('created_at', $day)->count()
                    ];
                }
                return $trends;
                
            case 'month':
                // Last 30 days in weekly intervals
                $trends = [];
                for ($i = 3; $i >= 0; $i--) {
                    $week = Carbon::now()->subWeeks($i);
                    $trends[] = [
                        'period' => 'Week ' . (4 - $i),
                        'count' => Booking::whereBetween('created_at', [
                            $week->startOfWeek(),
                            $week->endOfWeek()
                        ])->count()
                    ];
                }
                return $trends;
                
            case 'year':
                // Last 12 months
                $trends = [];
                for ($i = 11; $i >= 0; $i--) {
                    $month = Carbon::now()->subMonths($i);
                    $trends[] = [
                        'period' => $month->format('M'),
                        'count' => Booking::whereYear('created_at', $month->year)
                            ->whereMonth('created_at', $month->month)
                            ->count()
                    ];
                }
                return $trends;
                
            default:
                // All time - last 7 months (default)
                $trends = [];
                for ($i = 6; $i >= 0; $i--) {
                    $month = Carbon::now()->subMonths($i);
                    $trends[] = [
                        'period' => $month->format('M'),
                        'count' => Booking::whereYear('created_at', $month->year)
                            ->whereMonth('created_at', $month->month)
                            ->count()
                    ];
                }
                return $trends;
        }
    }
}
