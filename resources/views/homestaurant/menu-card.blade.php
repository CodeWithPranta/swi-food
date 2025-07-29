<x-landing-layout>
    {{-- Cover Section --}}
    <div class="relative w-full h-72 md:h-96 bg-gray-200">
        <img src="{{ asset('storage/' . $vendor->cover_photo) }}"
             alt="Cover of {{ $vendor->kitchen_name }}"
             class="w-full h-full object-cover">
        <div class="absolute left-1/2 bottom-[-60px] transform -translate-x-1/2">
           <img src="{{ asset($vendor->user->profile_image != null ? 'storage/' . $vendor->user->profile_image : 'images/default-profile.jpg') }}"
                class="w-28 h-28 md:w-40 md:h-40 rounded-full border-4 border-white shadow-lg object-cover"
                alt="{{ $vendor->user->name }}">
        </div>
    </div>

    {{-- Info Section --}}
    <div class="text-center mt-24">
        <h1 class="text-3xl font-bold text-gray-700">{{ $vendor->kitchen_name }}</h1>
        <p class="text-gray-700"><span class="font-semibold">Chef:</span> {{ $vendor->chef_name ?? $vendor->user->name }}</p>
        <p class="text-sm text-gray-700 mt-1">{{ $vendor->location }}</p>

        @php
            $links = $vendor->links;
        @endphp

        @if(is_array($links))
            <div class="flex justify-center gap-4 mt-3">
                @foreach($links as $item)
                    @if(is_array($item) && isset($item['link'], $item['svg']))
                        <a href="{{ $item['link'] }}" target="_blank" class="hover:scale-110 transition-transform duration-200">
                            {!! $item['svg'] !!}
                        </a>
                    @endif
                @endforeach
            </div>
        @endif
    </div>


    {{-- Menu Section --}}
    <section class="bg-gray-50 py-12">
        <div class="max-w-screen-md mx-auto px-4" style="background-image: url('images/bg.gif')">
            <h2 class="text-2xl font-bold text-center mb-8">Menu</h2>

            @php
                $groupedFoods = $vendor->foods->groupBy(fn($food) => $food->category->name ?? 'Uncategorized');
            @endphp

            @foreach($groupedFoods as $category => $foods)
                <div class="mb-10">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ $category }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach($foods as $food)
                            @php
                                $discounted = $food->discount 
                                    ? $food->price - ($food->price * $food->discount / 100)
                                    : $food->price;
                            @endphp

                            <div class="bg-white rounded-xl shadow hover:shadow-xl transform hover:scale-[1.02] transition-all p-5">
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $food->name }}</h4>
                                <p class="text-sm text-gray-700">
                                    Price:
                                    @if($food->discount)
                                        <span class="line-through text-red-500 mr-1">{{ number_format($food->price, 2) }} CHF</span>
                                        <span class="font-semibold text-green-600">{{ number_format($discounted, 2) }} CHF</span>
                                        <span class="text-xs text-gray-500 ml-1">({{ $food->discount }}% OFF)</span>
                                    @else
                                        <span class="font-medium">{{ number_format($food->price, 2) }} CHF</span>
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        @php
            $hours = $vendor->opening_hours;
        @endphp

        @if(is_array($hours))
            <div class="mt-4 max-w-screen-md mx-auto px-4">
                <h2 class="text-xl font-semibold text-gray-700 mb-2">Opening Hours</h2>
                <ul class="text-gray-700 flex flex-wrap space-x-4">
                    {{-- Display today's opening hours --}}
                    {{-- Display each day's opening hours --}}
                    @foreach($hours as $entry)
                        @if(is_array($entry) && isset($entry['day'], $entry['open'], $entry['close']))
                            @php
                                $openTime = \Carbon\Carbon::createFromFormat('H:i', $entry['open'])->format('g:i A');
                                $closeTime = \Carbon\Carbon::createFromFormat('H:i', $entry['close'])->format('g:i A');
                            @endphp
                            <li><span class="font-semibold">{{ ucfirst($entry['day']) }}</span>: {{ $openTime }} - {{ $closeTime }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="max-w-screen-md mx-auto px-4 mt-8 text-gray-700">
            {!! $vendor->description !!}
        </div>

    </section>

    <x-footer-section />
</x-landing-layout>
