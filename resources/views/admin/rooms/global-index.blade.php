@extends('layouts.app')

@section('content')
<main class="flex-1 p-4 md:p-6 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">House Rooms</h1>
            <p class="text-sm text-gray-600">Manage and track all rooms for all houses</p>
        </div>
    </div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-bed text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600">Total Rooms</p>
                    <p class="text-lg font-bold text-gray-900">{{ $rooms->total() }}</p>
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
                    <p class="text-lg font-bold text-gray-900">{{ $rooms->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-eye text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600">Visible</p>
                    <p class="text-lg font-bold text-gray-900">{{ $rooms->where('is_visible', true)->count() }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Search and Filter Section -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 mb-8">
        <form method="GET" action="" class="flex flex-col md:flex-row items-center gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search rooms..." class="px-4 py-2 border-2 border-gray-200 rounded-xl text-gray-900">
            <select name="house_id" class="px-4 py-2 border-2 border-gray-200 rounded-xl text-gray-900">
                <option value="">All Houses</option>
                @foreach($houses as $house)
                    <option value="{{ $house->id }}" @if(request('house_id') == $house->id) selected @endif>{{ $house->name }}</option>
                @endforeach
            </select>
            <select name="is_active" class="px-4 py-2 border-2 border-gray-200 rounded-xl text-gray-900">
                <option value="">All Status</option>
                <option value="1" @if(request('is_active')==='1') selected @endif>Active</option>
                <option value="0" @if(request('is_active')==='0') selected @endif>Inactive</option>
            </select>
            <select name="is_visible" class="px-4 py-2 border-2 border-gray-200 rounded-xl text-gray-900">
                <option value="">All Visibility</option>
                <option value="1" @if(request('is_visible')==='1') selected @endif>Visible</option>
                <option value="0" @if(request('is_visible')==='0') selected @endif>Hidden</option>
            </select>
            <input type="text" name="category" value="{{ request('category') }}" placeholder="Category" class="px-4 py-2 border-2 border-gray-200 rounded-xl text-gray-900">
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white font-semibold rounded-xl shadow-md hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200">
                <i class="fas fa-search mr-2"></i> Filter
            </button>
        </form>
    </div>
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-4">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>House</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Visibility</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                    <tr class="border-b">
                        <td>
                            @if($room->photo)
                                <img src="{{ asset('storage/' . $room->photo) }}" class="w-14 h-14 object-cover rounded-lg border">
                            @else
                                <span class="text-gray-400">No Photo</span>
                            @endif
                        </td>
                        <td class="font-semibold">{{ $room->name }}</td>
                        <td>{{ $room->house->name ?? '-' }}</td>
                        <td>{{ $room->currency }} {{ number_format($room->price, 2) }} <span class="text-xs text-gray-400">/{{ $room->price_duration ?? 'period' }}</span></td>
                        <td>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $room->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $room->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $room->is_visible ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $room->is_visible ? 'Visible' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="flex gap-2">
                            <a href="{{ route('admin.houses.rooms.edit', [$room->house_id, $room]) }}" class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold"><i class="fas fa-edit mr-1"></i> Edit</a>
                            <a href="{{ route('admin.houses.rooms.show', [$room->house_id, $room]) }}" class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold"><i class="fas fa-eye mr-1"></i> View</a>
                            <a href="{{ route('admin.rooms.photos.index', [$room->house_id, $room]) }}" class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold"><i class="fas fa-images mr-1"></i> Gallery</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-400 py-12">No rooms found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex items-center justify-between">
            <div>
                <!-- Bulk actions or other controls can go here -->
            </div>
            <div>
                {{ $rooms->links() }}
            </div>
        </div>
    </div>
</main>
@endsection 