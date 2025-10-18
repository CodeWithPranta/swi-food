<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <!-- Delivered Orders -->
        <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 flex flex-col justify-center items-center text-center">
            <h2 class="text-lg font-semibold">Delivered Orders</h2>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $deliveredOrders }}</p>
        </div>

        <!-- Pending Orders -->
        <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 flex flex-col justify-center items-center text-center">
            <h2 class="text-lg font-semibold">Pending Orders</h2>
            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $pendingOrders }}</p>
        </div>

        <!-- Total Purchased -->
        <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 flex flex-col justify-center items-center text-center">
            <h2 class="text-lg font-semibold">Total Purchased</h2>
            <p class="text-3xl font-bold text-blue-600 mt-2">${{ number_format($totalPurchased, 2) }}</p>
        </div>
    </div>

    <!-- Liked Foods -->
    <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900">
        <h2 class="text-xl font-semibold mb-4">Liked Foods</h2>

        @if($likedFoods->isEmpty())
            <div class="flex items-center justify-center h-40">
                <p class="text-gray-500 text-center text-sm">You haven’t liked any foods yet 🍽️</p>
            </div>
        @else
            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($likedFoods as $food)
                    <a href="{{ route('food.details', ['id' => $food->id, 'name' => $food->slug]) }}" 
                    class="block rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden hover:shadow-md transition">
                    
                        @php $images = $food->images; @endphp

                        @if(!empty($images) && file_exists(public_path('storage/' . $images[0])))
                            <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $food->name }}" class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-gray-200 dark:bg-neutral-800 flex items-center justify-center text-gray-400 text-sm">
                                No Image
                            </div>
                        @endif

                        <div class="p-3 text-center">
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $food->name }}</h3>
                            <p class="text-gray-500 text-xs mt-1">${{ $food->price }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
    
</div>
