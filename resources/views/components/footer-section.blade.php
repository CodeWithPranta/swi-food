<footer class="bg-black text-gray-200 py-8">
    <div
        class="max-w-screen-xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center md:items-start space-y-6 md:space-y-0">
        
        <!-- Middle: Navigation Links -->
        <div class="flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-3 text-sm my-2 text-center md:text-left">
            @foreach ($pages as $page)
                <a href="{{ route('page.view', $page->slug) }}" class="hover:text-white transition">
                    {{ $page->title }}
                </a>
            @endforeach
            <a href="{{ route('contact.create') }}" class="hover:text-white transition">Contact</a>
        </div>

        <!-- Right: Social Icons -->
        <div class="flex space-x-4 my-2">
            @foreach($socialLinks as $social)
                <a href="{{ $social['url'] }}" class="text-gray-200 hover:text-white transition">
                    {!! $social['svg'] !!}
                </a>
            @endforeach   
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 border-t border-gray-700">
        <div class="flex justify-center items-center pt-4">
            <span class="text-sm text-center">&copy; {{ $copyrightText }}</span>
        </div>
    </div>
</footer>
