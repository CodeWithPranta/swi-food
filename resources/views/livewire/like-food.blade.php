<button wire:click="toggleLike"
    class="bg-gray-200 flex gap-2 items-center text-gray-800 px-6 py-2 cursor-pointer rounded-full hover:bg-gray-300 focus:outline-none">

    @if ($isLiked)
        <!-- Filled red heart -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="red" viewBox="0 0 24 24" class="w-6 h-6">
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 
                     2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09 
                     C13.09 3.81 14.76 3 16.5 3 
                     19.58 3 22 5.42 22 8.5 
                     c0 3.78 -3.4 6.86 -8.55 11.54L12 21.35z" />
        </svg>
    @else
        <!-- Outline gray heart -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="gray" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 
                     2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09 
                     C13.09 3.81 14.76 3 16.5 3 
                     19.58 3 22 5.42 22 8.5 
                     c0 3.78 -3.4 6.86 -8.55 11.54L12 21.35z"/>
        </svg>
    @endif

    <!-- Show like count only if more than 0 -->
    @if ($likesCount > 0)
        <span>{{ $likesCount }}</span>
    @endif
</button>
