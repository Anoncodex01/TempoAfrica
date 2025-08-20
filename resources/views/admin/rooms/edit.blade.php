@extends('layouts.app')

@section('content')
<main class="flex-1 p-6 md:p-10 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Room</h1>
        <a href="{{ route('admin.houses.rooms.index', $house) }}" class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Back to Rooms
        </a>
    </div>
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 max-w-2xl mx-auto">
        <form action="{{ route('admin.houses.rooms.update', [$house, $room]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Name</label>
                    <input type="text" name="name" value="{{ old('name', $room->name) }}" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl text-gray-900">
                    @error('name')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Unique Number</label>
                    <input type="text" name="unique_number" value="{{ old('unique_number', $room->unique_number) }}" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl text-gray-900">
                    @error('unique_number')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Currency</label>
                    <input type="text" name="currency" value="{{ old('currency', $room->currency) }}" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl text-gray-900">
                    @error('currency')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Price</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $room->price) }}" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl text-gray-900">
                    @error('price')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Price Duration</label>
                    <input type="text" name="price_duration" value="{{ old('price_duration', $room->price_duration) }}" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl text-gray-900">
                    @error('price_duration')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Category</label>
                    <input type="text" name="category" value="{{ old('category', $room->category) }}" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl text-gray-900">
                    @error('category')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" @if(old('is_active', $room->is_active)) checked @endif>
                        <span class="text-gray-700">Active</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_visible" value="1" @if(old('is_visible', $room->is_visible)) checked @endif>
                        <span class="text-gray-700">Visible</span>
                    </label>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Main Photo</label>
                    <input type="file" name="photo" accept="image/*" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl text-gray-900">
                    @if($room->photo)
                        <img src="{{ asset('storage/' . $room->photo) }}" class="w-20 h-20 object-cover rounded-lg mt-2 border">
                    @endif
                    @error('photo')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.houses.rooms.index', $house) }}" class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition-all duration-200">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white font-semibold rounded-xl shadow-md hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200">Update Room</button>
            </div>
        </form>
    </div>
</main>
@endsection 