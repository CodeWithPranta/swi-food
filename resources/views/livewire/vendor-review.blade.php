<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 sm:p-6">
    <h3 class="text-lg font-semibold mb-4">Leave a Review</h3>

    @if (session()->has('message'))
        <div class="p-2 bg-green-500 text-white rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="submitReview" class="space-y-4">
        {{-- Star Rating --}}
        <div class="flex items-center space-x-2">
            @for ($i = 1; $i <= 5; $i++)
                <button type="button" wire:click="$set('rating', {{ $i }})" class="focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.965a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.39 2.462a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.39-2.462a1 1 0 00-1.176 0l-3.39 2.462c-.784.57-1.838-.197-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.045 9.392c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.965z" />
                    </svg>
                </button>
            @endfor
        </div>
        @error('rating') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

        {{-- Review Text --}}
        <div>
            <textarea wire:model.defer="review" rows="4"
                class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600"
                placeholder="Write your review here..."></textarea>
            @error('review') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Submit Review
        </button>
    </form>
</div>
