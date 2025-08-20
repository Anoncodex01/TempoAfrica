<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Tempo Africa') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* Modern Sidebar CSS */
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: sticky;
            top: 0;
            font-family: 'Inter', sans-serif;
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid #e2e8f0;
            background: linear-gradient(135deg, rgba(215, 20, 24, 0.05) 0%, rgba(241, 158, 0, 0.05) 100%);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #d71418 0%, #f19e00 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(215, 20, 24, 0.3);
        }

        .logo-icon i {
            color: white;
            font-size: 18px;
        }

        .app-title {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .app-subtitle {
            font-size: 12px;
            color: #64748b;
            margin: 0;
        }

        .sidebar-nav {
            flex: 1;
            padding: 20px 16px;
            overflow-y: auto;
        }

        .nav-section {
            margin-bottom: 32px;
        }

        .nav-section-title {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
            padding-left: 8px;
        }

        .nav-item {
            margin-bottom: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-radius: 12px;
            text-decoration: none;
            color: #64748b;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .nav-link:hover {
            background: linear-gradient(135deg, rgba(215, 20, 24, 0.08) 0%, rgba(241, 158, 0, 0.08) 100%);
            color: #d71418;
            transform: translateX(4px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(215, 20, 24, 0.12) 0%, rgba(241, 158, 0, 0.12) 100%);
            color: #d71418;
            border: 1px solid rgba(215, 20, 24, 0.2);
            box-shadow: 0 4px 12px rgba(215, 20, 24, 0.15);
        }

        .nav-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            transition: all 0.3s ease;
        }

        .nav-link:hover .nav-icon {
            transform: scale(1.1);
        }

        .nav-link.active .nav-icon {
            background: linear-gradient(135deg, #d71418 0%, #f19e00 100%);
            box-shadow: 0 4px 12px rgba(215, 20, 24, 0.3);
        }

        .nav-icon i {
            font-size: 14px;
            color: #64748b;
            transition: color 0.3s ease;
        }

        .nav-link:hover .nav-icon i,
        .nav-link.active .nav-icon i {
            color: white;
        }

        .nav-icon.simple i {
            color: #64748b;
        }

        .nav-link:hover .nav-icon.simple i,
        .nav-link.active .nav-icon.simple i {
            color: #d71418;
        }

        .nav-text {
            flex: 1;
        }

        .dropdown-toggle {
            background: none;
            border: none;
            cursor: pointer;
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-radius: 12px;
            color: #64748b;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .dropdown-toggle:hover {
            background: linear-gradient(135deg, rgba(215, 20, 24, 0.08) 0%, rgba(241, 158, 0, 0.08) 100%);
            color: #d71418;
            transform: translateX(4px);
        }

        .dropdown-toggle.active {
            background: linear-gradient(135deg, rgba(215, 20, 24, 0.12) 0%, rgba(241, 158, 0, 0.12) 100%);
            color: #d71418;
            border: 1px solid rgba(215, 20, 24, 0.2);
            box-shadow: 0 4px 12px rgba(215, 20, 24, 0.15);
        }

        .dropdown-arrow {
            margin-left: auto;
            transition: transform 0.3s ease;
            color: #94a3b8;
        }

        .dropdown-toggle:hover .dropdown-arrow,
        .dropdown-toggle.active .dropdown-arrow {
            color: #d71418;
        }

        .dropdown-arrow.rotate-180 {
            transform: rotate(180deg);
        }

        .dropdown-menu {
            margin-left: 32px;
            margin-top: 8px;
            transition: all 0.3s ease;
        }

        .dropdown-menu[x-cloak] {
            display: none !important;
        }

        .dropdown-item {
            display: block;
            padding: 10px 16px;
            color: #64748b;
            text-decoration: none;
            font-size: 13px;
            border-radius: 8px;
            margin-bottom: 4px;
            transition: all 0.3s ease;
            position: relative;
        }

        .dropdown-item:hover {
            background: rgba(215, 20, 24, 0.08);
            color: #d71418;
            transform: translateX(8px);
        }

        .dropdown-item.active {
            background: rgba(215, 20, 24, 0.12);
            color: #d71418;
            border-left: 3px solid #d71418;
        }

        .dropdown-item i {
            margin-right: 8px;
            font-size: 12px;
            width: 16px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid #e2e8f0;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #d71418 0%, #f19e00 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 14px;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 12px;
            color: #64748b;
            margin: 0;
        }

        .logout-btn {
            width: 32px;
            height: 32px;
            background: rgba(239, 68, 68, 0.1);
            border: none;
            border-radius: 8px;
            color: #ef4444;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            transform: scale(1.1);
        }

        .logout-btn i {
            font-size: 12px;
        }

        /* Scrollbar Styling */
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }


    </style>
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">
    @auth('user')
    @php
        $currentPath = request()->path();
    @endphp
    <div class="flex min-h-screen">
        <!-- Modern CSS Sidebar -->
        <aside class="sidebar">
            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <div class="logo-container">
                    <div class="logo-icon">
                        <i class="fas fa-compass"></i>
                    </div>
                    <div>
                        <h1 class="app-title">Tempo Africa</h1>
                        <p class="app-subtitle">Property Management</p>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar Navigation -->
            <nav class="sidebar-nav">
                <!-- Main Navigation -->
                <div class="nav-section">
                    <div class="nav-section-title">Main Navigation</div>
                    
                    <!-- Dashboard -->
                    <div class="nav-item">
                        <a href="/dashboard" class="nav-link {{ $currentPath === 'dashboard' ? 'active' : '' }}">
                            <div class="nav-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </div>
                    
                    <!-- Accommodations Dropdown -->
                    <div class="nav-item" x-data="{ open: false }">
                        <button class="dropdown-toggle {{ in_array($currentPath, ['admin/accommodations', 'admin/accommodation-rooms']) ? 'active' : '' }}" 
                                @click="open = !open"
                                :aria-expanded="open">
                            <div class="nav-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <span class="nav-text">Accommodations</span>
                            <i class="fas fa-chevron-down dropdown-arrow" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div class="dropdown-menu" x-show="open" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                            <a href="/admin/accommodations" class="dropdown-item {{ $currentPath === 'admin/accommodations' ? 'active' : '' }}">
                                <i class="fas fa-list"></i>All Accommodations
                            </a>
                            <a href="/admin/accommodation-rooms" class="dropdown-item {{ $currentPath === 'admin/accommodation-rooms' ? 'active' : '' }}">
                                <i class="fas fa-door-open"></i>Accommodation Rooms
                            </a>
                        </div>
                    </div>
                    
                    <!-- Houses -->
                    <div class="nav-item">
                        <a href="/admin/houses" class="nav-link {{ $currentPath === 'admin/houses' ? 'active' : '' }}">
                            <div class="nav-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <span class="nav-text">Houses</span>
                        </a>
                    </div>
                    
                    <!-- Bookings -->
                    <div class="nav-item">
                        <a href="/admin/bookings" class="nav-link {{ $currentPath === 'admin/bookings' ? 'active' : '' }}">
                            <div class="nav-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <span class="nav-text">Bookings</span>
                        </a>
                    </div>
                    
                    <!-- House Bookings -->
                    <div class="nav-item">
                        <a href="/admin/house-bookings" class="nav-link {{ $currentPath === 'admin/house-bookings' ? 'active' : '' }}">
                            <div class="nav-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <span class="nav-text">House Bookings</span>
                        </a>
                    </div>
                    
                    <!-- Customers -->
                    <div class="nav-item">
                        <a href="/admin/customers" class="nav-link {{ $currentPath === 'admin/customers' ? 'active' : '' }}">
                            <div class="nav-icon">
                                <i class="fas fa-user-friends"></i>
                            </div>
                            <span class="nav-text">Customers</span>
                        </a>
                    </div>
                </div>
                
                <!-- Administration Section -->
                <div class="nav-section">
                    <div class="nav-section-title">Administration</div>
                    
                    <!-- Locations Dropdown -->
                    <div class="nav-item" x-data="{ open: false }">
                        <button class="dropdown-toggle {{ in_array($currentPath, ['admin/countries', 'admin/provinces', 'admin/districts', 'admin/streets']) ? 'active' : '' }}"
                                @click="open = !open"
                                :aria-expanded="open">
                            <div class="nav-icon simple">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <span class="nav-text">Locations</span>
                            <i class="fas fa-chevron-down dropdown-arrow" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div class="dropdown-menu" x-show="open" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                            <a href="/admin/countries" class="dropdown-item {{ $currentPath === 'admin/countries' ? 'active' : '' }}">
                                <i class="fas fa-flag"></i>Countries
                            </a>
                            <a href="/admin/provinces" class="dropdown-item {{ $currentPath === 'admin/provinces' ? 'active' : '' }}">
                                <i class="fas fa-map"></i>Provinces
                            </a>
                            <a href="/admin/districts" class="dropdown-item {{ $currentPath === 'admin/districts' ? 'active' : '' }}">
                                <i class="fas fa-city"></i>Districts
                            </a>
                            <a href="/admin/streets" class="dropdown-item {{ $currentPath === 'admin/streets' ? 'active' : '' }}">
                                <i class="fas fa-road"></i>Streets
                            </a>
                        </div>
                    </div>
                </div>
            </nav>
            
            <!-- Sidebar Footer -->
            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::guard('user')->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <p class="user-name">{{ Auth::guard('user')->user()->name ?? 'User' }}</p>
                        <p class="user-role">Administrator</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>
    </div>
    @else
    <!-- Redirect to login if not authenticated -->
    <script>window.location.href = '{{ route("login") }}';</script>
    @endauth
</body>
</html> 
