<x-landing-layout>
    <section class="py-16 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-8 text-center">Homestaurants near "{{ $location }}"</h2>

        @if($nearbyVendors->isEmpty())
            <p class="text-center text-gray-500">No homestaurants found for this location.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($nearbyVendors as $vendor)
                    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition-all duration-300 overflow-hidden">
                        <img src="{{ asset('storage/' . $vendor->cover_photo) }}" alt="{{ $vendor->chef_name }}"
                            class="w-full h-48 object-cover" />
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $vendor->kitchen_name }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($vendor->description, 100) }}</p>
                            <p class="text-sm text-red-500 mt-2">{{ $vendor->distance ?? '' }}</p>
                            <a href=""
                                class="text-sm mt-4 inline-block text-red-600 hover:underline">View Details</a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-10">
                {{ $nearbyVendors->links() }}
            </div>
        @endif
    </div>
</section>
    <x-footer-section />
</x-landing-layout>
