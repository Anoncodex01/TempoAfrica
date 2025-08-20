@extends('layouts.app')

@section('content')
<main class="flex-1 p-6 md:p-10 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Rooms for {{ $house->name }}</h1>
            <p class="text-gray-600">Manage all rooms for this house.</p>
        </div>
        <a href="{{ route('admin.houses.show', $house) }}" class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Back to House
        </a>
    </div>
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 mb-8">
        <form method="GET" action="" class="flex flex-col md:flex-row items-center gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search rooms..." class="px-4 py-2 border-2 border-gray-200 rounded-xl text-gray-900">
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
            <a href="{{ route('admin.houses.rooms.create', $house) }}" class="px-6 py-2 bg-green-600 text-white font-semibold rounded-xl shadow-md hover:bg-green-700 transition-all duration-200">
                <i class="fas fa-plus mr-2"></i> Add Room
            </a>
        </form>
    </div>
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-4">
        <form method="POST" action="#" id="bulk-action-form">
            @csrf
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th><input type="checkbox" @click="let cbs = document.querySelectorAll('.room-cb'); cbs.forEach(cb => cb.checked = $event.target.checked)"></th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Visibility</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $room)
                        <tr class="border-b">
                            <td><input type="checkbox" name="rooms[]" value="{{ $room->id }}" class="room-cb"></td>
                            <td>
                                @if($room->photo)
                                    <img src="{{ asset('storage/' . $room->photo) }}" class="w-14 h-14 object-cover rounded-lg border">
                                @else
                                    <span class="text-gray-400">No Photo</span>
                                @endif
                            </td>
                            <td class="font-semibold">{{ $room->name }}</td>
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
                                <a href="{{ route('admin.houses.rooms.edit', [$house, $room]) }}" class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold"><i class="fas fa-edit mr-1"></i> Edit</a>
                                <a href="{{ route('admin.houses.rooms.show', [$house, $room]) }}" class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold"><i class="fas fa-eye mr-1"></i> View</a>
                                <a href="{{ route('admin.rooms.photos.index', [$house, $room]) }}" class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold"><i class="fas fa-images mr-1"></i> Gallery</a>
                                <form action="{{ route('admin.houses.rooms.destroy', [$house, $room]) }}" method="POST" onsubmit="return confirm('Delete this room?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold"><i class="fas fa-trash mr-1"></i> Delete</button>
                                </form>
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
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white font-semibold rounded-xl shadow-md hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200">Bulk Action</button>
                </div>
                <div>
                    {{ $rooms->links() }}
                </div>
            </div>
        </form>
    </div>
</main>
@endsection 