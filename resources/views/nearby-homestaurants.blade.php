<x-landing-layout>
    <section class="py-16 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <h1 class="text-2xl pt-10 font-bold text-gray-800 mb-8 text-center">Homestaurants near "{{ $location }}"</h1>
        <form method="GET" action="" class="flex flex-row items-center justify-center gap-2 sm:gap-4 max-w-3xl mx-auto mb-10">
            {{-- Search input --}}
            <input
                type="text"
                name="query"
                id="query"
                value="{{ request('query') }}"
                placeholder="Search for a dish..."
                class="w-full sm:w-[400px] px-6 py-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-red-500 focus:outline-none shadow-sm"
                list="suggestions"
                autocomplete="off"
            />

            <datalist id="suggestions">
                @foreach($allFoodNames as $foodName)
                    <option value="{{ $foodName }}">{{ $foodName }}</option>
                @endforeach
            </datalist>


            {{-- Submit button --}}
            <x-main-btn type="submit">Search</x-main-btn>
        </form>

        @if($nearbyVendors->isEmpty())
            <p class="text-center text-gray-500">
                No homestaurants found for "{{ request('query') }}" near "{{ $location }}".
            </p>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8 max-w-7xl mx-auto">
            @foreach($nearbyVendors as $vendor)
            <div class="bg-white rounded-xl shadow-md overflow-hidden transform transition-transform duration-300 hover:scale-105 hover:shadow-lg cursor-pointer">
                <div class="relative h-48 sm:h-56 md:h-64 overflow-hidden">
                    <img class="w-full h-full object-cover" src="{{ asset('storage/' . $vendor->cover_photo) }}" alt="Cover photo of {{$vendor->kitchen_name}}">
                    @php
                    $currentDay = strtolower(now()->timezone('Europe/Zurich')->format('l')); // 'monday', etc.
                    $currentTime = now()->timezone('Europe/Zurich')->format('H:i');

                    $todayHours = collect($vendor->opening_hours)->firstWhere('day', $currentDay);

                    $openTime = $todayHours['open'] ?? null;
                    $closeTime = $todayHours['close'] ?? null;

                    $isOpen = $openTime && $closeTime && ($currentTime >= $openTime && $currentTime <= $closeTime);
                @endphp

                <div class="absolute top-3 left-3 {{ $isOpen ? 'bg-green-700' : 'bg-red-700' }} text-white text-xs font-semibold px-2 py-1 rounded-full">
                    {{ $isOpen ? 'Open' : 'Closed' }}
                </div>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="flex justify-between items-start mb-2">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900 truncate pr-4">{{$vendor->kitchen_name}}</h2>
                        <div class="flex items-center text-sm font-semibold text-gray-700 bg-gray-100 px-2 py-1 rounded-full">
                            <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.96a1 1 0 00.95.69h4.17c.969 0 1.371 1.24.588 1.81l-3.38 2.459a1 1 0 00-.364 1.118l1.287 3.96c.3.921-.755 1.688-1.538 1.118l-3.38-2.459a1 1 0 00-1.176 0l-3.38 2.459c-.783.57-1.838-.197-1.538-1.118l1.287-3.96a1 1 0 00-.364-1.118L2.05 9.397c-.783-.57-.381-1.81.588-1.81h4.17a1 1 0 00.95-.69l1.286-3.96z"></path></svg>
                            4.7 <span class="text-gray-500 ml-1">(500+)</span>
                        </div>
                    </div>
                    @php
                        $categories = $vendor->foods
                            ->pluck('category.name')
                            ->unique()
                            ->take(4) // Limit how many categories to show
                            ->implode(', ');
                    @endphp
                    <p class="text-gray-700 text-sm mb-3 truncate">
                        {{ $categories ?: 'No categories yet' }}
                    </p>
                    <div class="flex justify-between items-center text-sm text-gray-700">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-500 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 2a6 6 0 00-6 6c0 4.418 6 10 6 10s6-5.582 6-10a6 6 0 00-6-6zm0 8a2 2 0 110-4 2 2 0 010 4z" clip-rule="evenodd"/>
                            </svg>

                            {{ $vendor->distance ? number_format($vendor->distance, 2) . ' km' : '0 km' }}
                        </div>
                        <div class="font-semibold">
                            $$$
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-10 max-w-7xl mx-auto">
            {{ $nearbyVendors->links() }}
        </div>
        @endif
    </div>
</section>
    <x-footer-section />
</x-landing-layout>
