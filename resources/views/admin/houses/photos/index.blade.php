@extends('layouts.app')

@section('content')
<main class="flex-1 p-6 md:p-10 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">House Photos</h1>
            <p class="text-gray-600">Manage photos for <span class="font-semibold">{{ $house->name }}</span></p>
        </div>
        <a href="{{ route('admin.houses.show', $house) }}" class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Back to House
        </a>
    </div>
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 mb-8">
        <form action="{{ route('admin.houses.photos.store', $house) }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center gap-4">
            @csrf
            <input type="file" name="photo" required accept="image/*" class="px-4 py-2 border-2 border-gray-200 rounded-xl text-gray-900">
            <input type="text" name="description" placeholder="Description (optional)" class="px-4 py-2 border-2 border-gray-200 rounded-xl text-gray-900">
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white font-semibold rounded-xl shadow-md hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200">
                <i class="fas fa-upload mr-2"></i> Upload Photo
            </button>
        </form>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @forelse($photos as $photo)
        <div class="bg-gray-50 rounded-xl shadow p-4 flex flex-col items-center">
            <img src="{{ asset('storage/' . $photo->photo) }}" alt="Photo" class="w-full h-40 object-cover rounded-lg mb-3 border border-gray-200">
            <div class="text-xs text-gray-700 mb-2">{{ $photo->description }}</div>
            <div class="flex items-center gap-2">
                <form action="{{ route('admin.houses.photos.toggle', [$house, $photo]) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3 py-1 rounded-full text-xs font-semibold {{ $photo->can_show ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                        <i class="fas fa-eye{{ $photo->can_show ? '' : '-slash' }} mr-1"></i> {{ $photo->can_show ? 'Visible' : 'Hidden' }}
                    </button>
                </form>
                <form action="{{ route('admin.houses.photos.destroy', [$house, $photo]) }}" method="POST" onsubmit="return confirm('Delete this photo?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold"><i class="fas fa-trash mr-1"></i> Delete</button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-4 text-center text-gray-400 py-12">No photos uploaded yet.</div>
        @endforelse
    </div>
</main>
@endsection 