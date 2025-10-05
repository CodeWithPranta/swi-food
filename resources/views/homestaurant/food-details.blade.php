<x-landing-layout>
<div class="bg-gray-100 pt-16 relative">
  <!-- Flash Messages -->
  @if(session('success'))
      <div id="flash-message" 
           class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 
                  bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg 
                  animate-fade-in-down">
          {{ session('success') }}
      </div>
  @endif

  @if(session('error'))
      <div id="flash-message" 
           class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 
                  bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg 
                  animate-fade-in-down">
          {{ session('error') }}
      </div>
  @endif

  <h1 class="text-4xl pt-8 font-bold text-gray-800 text-center">Food details</h1>
  <div class="container mx-auto px-4 py-4 md:py-8">
    <div class="flex flex-wrap -mx-4">
      <!-- Product Images -->
      <div class="w-full md:w-1/2 px-4 mb-8 mt-2">
        <img src="{{asset('storage/'. $food->images[0])}}" alt="{{ $food->name }} Main Image"
                    class="w-full h-auto rounded-lg shadow-md mb-4" id="mainImage">
        <div class="flex gap-4 py-4 justify-center overflow-x-auto">
            @php
            $food_images = $food->images
            @endphp

            @foreach ($food_images as $image)
            <img src="{{asset('storage/'.$image)}}"
                class="size-16 sm:size-20 object-cover rounded-md cursor-pointer opacity-60 hover:opacity-100 transition duration-300"
                        onclick="changeImage(this.src)">
            @endforeach
        </div>
      </div>

      <!-- Product Details -->
      <div class="w-full md:w-1/2 px-4">
        <h2 class="text-3xl font-bold mb-2">{{$food->name}}</h2>
        <p class="text-sm pb-2 italic">{{$food->amount}} {{$food->unit->name}}</p>
        <p class="text-gray-700 mb-4">Cooked by <span class="font-semibold">{{$vendor->chef_name}}</span></p>
        <div class="mb-4">
          @if ($food->discount > 0 )
          <span class="text-2xl font-bold mr-2">{{$food->price - ($food->price * $food->discount)/100}} CHF</span>
          <span class="text-gray-500 line-through">{{$food->price}}</span>
          @else 
          <span class="text-2xl font-bold mr-2">{{$food->price}} CHF</span>
          @endif
        </div>
        <div class="flex items-center mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
            class="size-6 text-yellow-500">
            <path fill-rule="evenodd"
              d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
              clip-rule="evenodd" />
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
            class="size-6 text-yellow-500">
            <path fill-rule="evenodd"
              d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
              clip-rule="evenodd" />
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
            class="size-6 text-yellow-500">
            <path fill-rule="evenodd"
              d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
              clip-rule="evenodd" />
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
            class="size-6 text-yellow-500">
            <path fill-rule="evenodd"
              d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
              clip-rule="evenodd" />
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
            class="size-6 text-yellow-500">
            <path fill-rule="evenodd"
              d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
              clip-rule="evenodd" />
          </svg>
          <span class="ml-2 text-gray-600">4.5 (120 reviews)</span>
        </div>
        
        <div class="mb-6">
          <form action="{{ route('order.now') }}" method="POST">
            @csrf
            <!-- Hidden fields -->
            <input type="hidden" name="food_id" value="{{ $food->id }}">
            <input type="hidden" name="vendor_application_id" value="{{ $vendor->id }}">

            <!-- Preference -->
            <label for="preference" class="block text-sm font-medium text-gray-700 mb-1">Preference:</label>
            <textarea id="preference" name="preference" rows="4"
              class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:border-gray-300 focus:ring focus:ring-gray-200 focus:ring-opacity-50"
              placeholder="Add any special requests (e.g. extra spicy, less oil, no onions)..."></textarea>

            <!-- Quantity -->
            <div class="mt-4">
              <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity:</label>
              <input type="number" id="quantity" name="quantity" min="1" value="1"
                class="w-16 text-center rounded-md border-gray-300 shadow-sm focus:border-gray-300 focus:ring focus:ring-gray-200 focus:ring-opacity-50">
            </div>

            <!-- Submit Button -->
            <div class="flex space-x-4 mt-6">
              <x-main-btn type="submit" class="flex gap-2">
                Order now
              </x-main-btn>
              <livewire:like-food :food="$food" :wire:key="'like-'.$food->id" />
            </div>
          </form>
        </div>

        <div>
          <h3 class="text-lg font-semibold mb-2">Details and ingredients:</h3>
          {!! $food->description !!}
        </div>
      </div>
    </div>
  </div>

  <script>
    function changeImage(src) {
            document.getElementById('mainImage').src = src;
        }
  </script>
  <script>
    // Auto dismiss flash messages after 3 seconds
    document.addEventListener("DOMContentLoaded", () => {
        const flashMessage = document.getElementById("flash-message");
        if(flashMessage){
            setTimeout(() => {
                flashMessage.classList.add("opacity-0", "translate-y-[-10px]");
                setTimeout(() => flashMessage.remove(), 500); 
            }, 3000);
        }
    });
  </script>

  <style>
    /* Smooth fade-in-down animation */
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down {
        animation: fadeInDown 0.5s ease-out;
    }
  </style>
</div>
<x-footer-section />
</x-landing-layout>