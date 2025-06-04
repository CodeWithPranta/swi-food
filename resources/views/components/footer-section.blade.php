<footer class="bg-black text-gray-200 py-8">
    <div
        class="max-w-screen-xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center space-y-6 md:space-y-0">
        <!-- Left: Logo and Text -->
        <div class="flex items-center space-x-2 my-2">
            <a href="/"><img src="{{ asset('storage/' . $favicon) }}" alt="Icon" class="w-8 h-8 rounded-sm"></a>
        </div>

        <!-- Middle: Navigation Links -->
        <div class="flex space-x-6 text-sm my-2">
            @foreach ($pages as $page)
                <a href="" class="hover:text-white transition">{{ $page->title }}</a>
            @endforeach
            <a href="" class="hover:text-white transition">Contact</a>
        </div>

        <!-- Right: Social Icons -->
        <div class="flex space-x-4 my-2">
            <a href="#" class="text-gray-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M22 5.9c-.8.4-1.6.7-2.4.8.9-.5 1.5-1.3 1.8-2.2-.8.5-1.7.9-2.6 1.1a4.2 4.2 0 00-7.3 3.8 11.8 11.8 0 01-8.6-4.3 4.2 4.2 0 001.3 5.6c-.7 0-1.4-.2-2-.5v.1c0 2 1.4 3.7 3.3 4.1-.6.2-1.3.2-2 .1.6 1.8 2.3 3.1 4.3 3.2a8.5 8.5 0 01-6.3 1.8 12 12 0 006.5 1.9c7.8 0 12-6.4 12-11.9v-.5c.8-.6 1.5-1.4 2-2.3z" />
                </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2.04c-5.5 0-10 4.46-10 9.96 0 4.4 2.86 8.13 6.84 9.47.5.09.66-.22.66-.48v-1.68c-2.78.6-3.37-1.33-3.37-1.33-.45-1.13-1.1-1.44-1.1-1.44-.9-.6.07-.59.07-.59 1 .07 1.53 1.04 1.53 1.04.89 1.53 2.34 1.09 2.91.83.09-.65.35-1.09.63-1.34-2.22-.25-4.56-1.11-4.56-4.95 0-1.1.39-2 .1-2.7 0 0 .83-.26 2.72 1.03a9.4 9.4 0 015 0c1.89-1.29 2.72-1.03 2.72-1.03.48.7.1 1.6.05 2.7 0 3.84-2.34 4.7-4.57 4.95.36.3.68.9.68 1.83v2.72c0 .26.16.58.67.48A10.02 10.02 0 0022 12c0-5.5-4.5-9.96-10-9.96z" />
                </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M22.23 0H1.77C.8 0 0 .77 0 1.73v20.54C0 23.23.8 24 1.77 24h20.46c.97 0 1.77-.77 1.77-1.73V1.73C24 .77 23.2 0 22.23 0zM7.08 20.45H3.54V9h3.54v11.45zM5.31 7.5A2.07 2.07 0 113.24 5.4 2.08 2.08 0 015.31 7.5zM20.45 20.45h-3.54v-5.59c0-1.33-.03-3.05-1.86-3.05-1.87 0-2.16 1.46-2.16 2.96v5.68h-3.54V9h3.4v1.56h.05c.47-.89 1.62-1.84 3.34-1.84 3.57 0 4.23 2.35 4.23 5.4v6.33z" />
                </svg>
            </a>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 border-t border-gray-700">
        <!-- Centered Copyright -->
        <div class="flex justify-center items-center pt-4">
            <span class="text-sm text-center">&copy; {{ $copyrightText }} </span>
        </div>
    </div>

</footer>
