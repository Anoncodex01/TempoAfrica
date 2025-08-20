@extends('layouts.app')

@section('content')
<main class="flex-1 p-6 md:p-10 bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Countries</h1>
                <p class="text-gray-600">Manage and organize country information</p>
            </div>
            <button onclick="openAddModal()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white font-semibold rounded-xl hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <i class="fas fa-plus mr-2"></i>
                Add Country
            </button>
        </div>
    </div>
    
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl flex items-center">
            <i class="fas fa-check-circle mr-3 text-green-500"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Main Content Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <!-- Card Header -->
        <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-[#d71418]/5 to-[#f19e00]/5">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-flag text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Country List</h2>
                    <p class="text-gray-600 text-sm">View and manage all countries in the system</p>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Country Code</th>
                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Zip Code</th>
                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Established</th>
                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($countries as $country)
                    <tr class="hover:bg-gray-50 transition-all duration-200">
                        <td class="px-8 py-6 whitespace-nowrap text-sm font-medium text-gray-900">{{ $country->id }}</td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-flag text-white text-xs"></i>
                                </div>
                                <span class="font-semibold text-gray-900">{{ $country->name }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap text-sm text-gray-600">{{ $country->country_code }}</td>
                        <td class="px-8 py-6 whitespace-nowrap text-sm text-gray-600">{{ $country->zip_code ?? 'NULL' }}</td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            @if($country->is_established)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Yes
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i> No
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap text-sm text-gray-600">{{ $country->created_at ? $country->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.countries.edit', $country) }}" class="inline-flex items-center px-4 py-2 bg-[#d71418] text-white text-xs font-semibold rounded-lg hover:bg-[#b31216] transition-all duration-200 shadow-sm hover:shadow-md">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.countries.destroy', $country) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this country?')" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-xs font-semibold rounded-lg hover:bg-gray-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-8 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-flag text-gray-400 text-xl"></i>
                                </div>
                                <p class="text-gray-500 font-medium">No countries found</p>
                                <p class="text-gray-400 text-sm mt-1">Get started by adding your first country</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Add Country Modal -->
<div id="addCountryModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-2xl rounded-2xl bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#d71418] to-[#f19e00] rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-plus text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Add New Country</h3>
                </div>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Form -->
            <form action="{{ route('admin.countries.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3" for="name">
                            <i class="fas fa-globe mr-2 text-[#d71418]"></i>
                            Country Name
                        </label>
                        <input type="text" name="name" id="name" required class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200" placeholder="Enter country name">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3" for="country_code">
                            <i class="fas fa-hashtag mr-2 text-[#d71418]"></i>
                            Country Code
                        </label>
                        <input type="text" name="country_code" id="country_code" required class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200" placeholder="e.g., 255, US, GB">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3" for="zip_code">
                            <i class="fas fa-mail-bulk mr-2 text-[#d71418]"></i>
                            Zip Code
                        </label>
                        <input type="text" name="zip_code" id="zip_code" class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:border-[#d71418] focus:ring-4 focus:ring-[#d71418]/10 transition-all duration-200" placeholder="Enter zip code (optional)">
                    </div>
                    
                    <div>
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl hover:border-[#d71418]/30 transition-all duration-200 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="is_established" id="is_established" value="1" class="sr-only">
                                <div class="w-6 h-6 border-2 border-gray-300 rounded-lg flex items-center justify-center transition-all duration-200 group-hover:border-[#d71418]">
                                    <i class="fas fa-check text-white text-xs opacity-0 transition-opacity duration-200"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <span class="text-sm font-semibold text-gray-700">Is Established</span>
                                <p class="text-xs text-gray-500 mt-1">Mark this country as active and established</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
                    <button type="button" onclick="closeAddModal()" class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-[#d71418] to-[#f19e00] text-white font-semibold rounded-xl hover:from-[#b31216] hover:to-[#d68900] transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-plus mr-2"></i>
                        Add Country
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('addCountryModal').classList.remove('hidden');
}

function closeAddModal() {
    document.getElementById('addCountryModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('addCountryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddModal();
    }
});
</script>
@endsection 