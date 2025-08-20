@extends('layouts.app')

@section('content')
<main class="flex-1 p-6 md:p-10 bg-gray-100">
    <h1 class="text-3xl font-bold text-[#d71418] mb-8">Accommodations</h1>
    <!-- Filter Bar -->
    <div class="flex flex-col md:flex-row md:items-center gap-4 mb-6">
        <input type="text" placeholder="Search by name..." class="w-full md:w-1/3 px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#f19e00] focus:border-[#f19e00]" />
        <select class="w-full md:w-1/4 px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#f19e00] focus:border-[#f19e00]">
            <option value="">All Categories</option>
            <option value="Hotel">Hotel</option>
            <option value="Apartment">Apartment</option>
            <option value="Hostel">Hostel</option>
        </select>
        <select class="w-full md:w-1/4 px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#f19e00] focus:border-[#f19e00]">
            <option value="">All Countries</option>
            @foreach($accommodations->pluck('country.name')->unique()->filter() as $country)
                <option value="{{ $country }}">{{ $country }}</option>
            @endforeach
        </select>
        <button class="bg-[#d71418] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#b31216] transition">Filter</button>
    </div>
    <!-- List View Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#f19e00]/10">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-[#d71418] uppercase tracking-wider">Photo</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-[#d71418] uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-[#d71418] uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-[#d71418] uppercase tracking-wider">Country</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-[#d71418] uppercase tracking-wider">Owner</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-[#d71418] uppercase tracking-wider">Min Price</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-[#d71418] uppercase tracking-wider">Rooms</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-[#d71418] uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($accommodations as $accommodation)
                <tr class="hover:bg-[#f19e00]/5 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="w-16 h-12 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                            @if($accommodation->photos->first())
                                <img src="{{ asset($accommodation->photos->first()->photo) }}" alt="{{ $accommodation->name }}" class="object-cover w-full h-full">
                            @else
                                <span class="text-gray-400 text-xs">No photo</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-700">{{ $accommodation->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-[#f19e00] font-semibold">{{ $accommodation->category }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $accommodation->country->name ?? '' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $accommodation->customer->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $accommodation->minimum_price }} {{ $accommodation->currency }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $accommodation->rooms->count() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="#" class="text-xs text-white bg-[#d71418] hover:bg-[#b31216] px-4 py-2 rounded-lg transition font-semibold shadow">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-400">No accommodations found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection 