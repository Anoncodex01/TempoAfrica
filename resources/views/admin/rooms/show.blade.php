@extends('layouts.app')

@section('content')
<main class="flex-1 p-6 md:p-10 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Room Details</h1>
        <a href="{{ route('admin.houses.rooms.index', $house) }}" class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Back to Rooms
        </a>
    </div>
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 max-w-2xl mx-auto">
        <div class="flex flex-col md:flex-row gap-8 items-center">
            <div>
                @if($room->photo)
                    <img src="{{ asset('uploads/' . $room->photo) }}" class="w-48 h-48 object-cover rounded-xl border mb-4">
                @else
                    <div class="w-48 h-48 flex items-center justify-center bg-gray-100 rounded-xl border mb-4 text-gray-400">No Photo</div>
                @endif
                <a href="{{ route('admin.rooms.photos.index', [$house, $room]) }}" class="block mt-2 px-4 py-2 bg-yellow-100 text-yellow-700 rounded-xl font-semibold text-center hover:bg-yellow-200 transition-all duration-200"><i class="fas fa-images mr-1"></i> View Gallery</a>
            </div>
            <div class="flex-1 space-y-4">
                <div><span class="font-semibold text-gray-700">Name:</span> {{ $room->name }}</div>
                <div><span class="font-semibold text-gray-700">Unique Number:</span> {{ $room->unique_number }}</div>
                <div><span class="font-semibold text-gray-700">Category:</span> {{ $room->category }}</div>
                <div><span class="font-semibold text-gray-700">Price:</span> {{ $room->currency }} {{ number_format($room->price, 2) }} <span class="text-xs text-gray-400">/{{ $room->price_duration ?? 'period' }}</span></div>
                <div><span class="font-semibold text-gray-700">Status:</span> <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $room->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">{{ $room->is_active ? 'Active' : 'Inactive' }}</span></div>
                <div><span class="font-semibold text-gray-700">Visibility:</span> <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $room->is_visible ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">{{ $room->is_visible ? 'Visible' : 'Hidden' }}</span></div>
            </div>
        </div>
    </div>
</main>
@endsection 