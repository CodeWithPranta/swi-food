<x-landing-layout>
    <style>
@keyframes float {
    0% {
        transform: translateY(0) scale(1);
        opacity: 0.4;
    }
    50% {
        transform: translateY(-50px) scale(1.2);
        opacity: 0.2;
    }
    100% {
        transform: translateY(-200px) scale(0.9);
        opacity: 0;
    }
}

.animate-float {
    animation-name: float;
    animation-timing-function: ease-in-out;
    animation-iteration-count: infinite;
}
</style>

     <section class="relative overflow-hidden pb-10">
        
        <!-- Animated floating bubbles -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        @for ($i = 0; $i < 20; $i++)
            <span class="absolute w-6 h-6 bg-blue-300 opacity-30 rounded-full animate-float"
                style="
                    left: {{ rand(0, 100) }}%;
                    animation-delay: {{ rand(0, 10) }}s;
                    animation-duration: {{ rand(10, 25) }}s;
                    top: {{ rand(100, 800) }}px;
                ">
            </span>
        @endfor
    </div>

    <!-- Main content -->
    <div class="relative z-10 items-center justify-center">
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
    <div class="mt-12 text-center">
        <div class="inline-block bg-white px-8 py-6 rounded-xl shadow-md">
            <h1 class="text-3xl font-extrabold text-gray-800 mb-2">{{ $vendor->kitchen_name }}</h1>

            <p class="text-lg text-gray-600">
                <span class="font-semibold text-gray-700">Chef:</span>
                {{ $vendor->chef_name ?? $vendor->user->name }}
            </p>

            <p class="text-sm text-gray-700 mt-1">
                <svg class="inline-block w-4 h-4 mb-1 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13 21.314l-4.657-4.657a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ $vendor->location }}
            </p>

            @php $links = $vendor->links; @endphp

            @if(is_array($links))
                <div class="flex justify-center gap-4 mt-4">
                    @foreach($links as $item)
                        @if(is_array($item) && isset($item['link'], $item['svg']))
                            <a href="{{ $item['link'] }}" target="_blank"
                            class="hover:scale-110 transition-transform duration-200">
                                <span class="w-6 h-6 inline-block">{!! $item['svg'] !!}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Menu Section --}}
        <div class="my-10 max-w-screen-md mx-auto px-4 py-2 bg-cover bg-center text-white" style="background-image: url('{{ asset('images/bg-yellow.jpg') }}')">
            <div class="mt-5 mx-auto sm:w-3/4 p-5 rounded-3xl text-black">
                <h1 class="bg-clip-text text-white text-xl md:text-2xl text-center font-black font-serif mt-4">
                    <b class="bg-red-700 px-4 py-2 rounded-3xl">MENU CARD</b><br>
                </h1>
                 
                @foreach ($vendorFoodCategories as $categoryName => $categoryId)
                    <h2 class="bg-clip-text text-center text-gray-700 text-lg font-serif mt-8"><b>{{ $categoryName }}</b></h2>
                    <ul class="text-md md:text-lg list-none font-sans font-extrabold my-5 space-y-4">
                        @foreach ($vendorFoods as $item)
                            @if ($item->category_id == $categoryId)
                                @php
                                    $finalPrice = $item->price - ($item->price * ($item->discount / 100));
                                @endphp

                                <li class="flex items-center justify-between rounded-xl px-4 py-3 shadow transition">
                                    <a href="#" class="text-black hover:text-red-600 font-extrabold">
                                        {{ $item->name }}
                                        <sup>
                                            @if ($item->discount > 0)
                                                <span class="text-red-600 line-through">{{ number_format($item->discount) }}%</span>
                                            @endif
                                        </sup>
                                    </a>

                                    <div class="flex items-center space-x-3">
                                        <span class="text-black">{{ number_format($finalPrice, 2) }} CHF</span>

                                       <!-- <livewire:add-to-cart :food-id="$item->id" :vendor-application-id="$item->user_id" :wire:key="'cart-'.$item->id" /> -->
                                        @livewire('add-to-cart', ['food' => $item], key($item->id))
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endforeach

                <p class="text-right mt-10 mb-4">
                    <small class="text-yellow-700 text-xl font-extrabold">Thank You</small>
                </p>
            </div>
        </div>

        @php
            $allDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            $hours = collect($vendor->opening_hours)->keyBy('day');
        @endphp

        @if($hours->isNotEmpty())
            <div class="mt-10 max-w-screen-md mx-auto px-4">
                <h2 class="text-2xl font-semibold text-gray-700 mb-6 text-center">🕒 Opening Hours</h2>

                <div class="bg-white shadow rounded-xl overflow-hidden divide-y divide-gray-200">
                    @foreach($allDays as $day)
                        @php
                            $entry = $hours->get($day);
                        @endphp
                        <div class="flex items-center justify-between px-6 py-3 hover:bg-gray-50 transition">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.75-4.75a.75.75 0 001.5 0V10a.75.75 0 00-.75-.75H7a.75.75 0 000 1.5h2.25v2.5z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-800 font-medium capitalize">{{ $day }}</span>
                            </div>
                            <div class="text-sm text-gray-600">
                                @if($entry)
                                    {{ \Carbon\Carbon::createFromFormat('H:i', $entry['open'])->format('g:i A') }} – 
                                    {{ \Carbon\Carbon::createFromFormat('H:i', $entry['close'])->format('g:i A') }}
                                @else
                                    <span class="text-red-500 font-semibold">Off</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif


        {{-- Vendor Description --}}
        <div class="max-w-screen-md mx-auto px-4 mt-12 text-gray-700">
            <div class="flex justify-center mb-4">
                <h2 class="text-2xl font-semibold flex items-center space-x-2 text-center">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 16v-4a4 4 0 118 0v4m-2 4h-4a2 2 0 01-2-2v-4a6 6 0 1112 0v4a2 2 0 01-2 2h-4z"/>
                    </svg>
                    <span>About Homestaurant</span>
                </h2>
            </div>
            <div class="bg-white rounded-xl text-justify shadow p-5 leading-relaxed">
                {!! $vendor->description !!}
            </div>
        </div>

    </div>
    </section>

    <x-footer-section />
</x-landing-layout>
