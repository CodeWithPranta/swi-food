<footer class="bg-neutral-900 text-gray-300 py-10">
    <div class="max-w-screen-xl mx-auto px-6 flex flex-col items-center space-y-8">

        <!-- Top: Social Icons + Logo + Menu -->
        <div class="flex justify-between w-full items-center">

            <!-- Left: Social Icons -->
            <div class="flex items-center space-x-2 md:space-x-4">
                @foreach($socialLinks as $social)
                    <a href="{{ $social['url'] }}" target="_blank"
                       class="text-gray-200 hover:text-white transition-colors duration-200">
                        {!! $social['svg'] !!}
                    </a>
                @endforeach
            </div>

            <!-- Middle: Logo -->
            <div class="flex justify-center">
                <x-secondary-logo class="w-32 md:w-48" />
            </div>

            <!-- Right: Extra Icons -->
            <div class="flex items-center space-x-2 md:space-x-4">

                <!-- Message Icon -->
                <a href="{{ route('contact.create') }}" class="text-gray-400 hover:text-white transition-colors duration-200"
                   title="Text Us">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-200 pt-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                       <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 10.5h.01m-4.01 0h.01M8 10.5h.01M5 5h14a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1h-6.6a1 1 0 0 0-.69.275l-2.866 2.723A.5.5 0 0 1 8 18.635V17a1 1 0 0 0-1-1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/>
                    </svg>
                </a>

                <!-- Menu Toggle Icon -->
                <button id="menu-toggle" class="hover:text-white transition-colors duration-200" title="Menu">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-200" xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" d="M5 7h14M5 12h14M5 17h14" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Middle: Navigation Links -->
        <div class="flex flex-wrap justify-center gap-x-8 gap-y-3 text-sm font-medium text-gray-300">
            @foreach ($pages as $page)
                <a href="{{ route('page.view', $page->slug) }}" 
                   class="hover:text-white transition duration-200">
                    {{ $page->title }}
                </a>
            @endforeach
        </div>

        <!-- Bottom: Copyright -->
        <div class="w-full border-t border-gray-700 pt-4 text-center">
            <p class="text-sm tracking-wide">&copy; {{ $copyrightText }}</p>
        </div>
    </div>

    <!-- Hidden Fullscreen Menu -->
    <div id="mobile-menu"
         class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex-col items-center justify-center space-y-6 text-white text-lg">
        @foreach ($menuPages as $page)
            <a href="{{ route('page.view', $page->slug) }}" class="hover:text-red-400 transition">{{ $page->title }}</a>
        @endforeach

        <button id="close-menu" class="absolute top-6 right-6">
            <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- JS for Menu Toggle -->
    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const closeMenu = document.getElementById('close-menu');

        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
            mobileMenu.classList.add('flex');
        });

        closeMenu.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            mobileMenu.classList.remove('flex');
        });
    </script>
</footer>
