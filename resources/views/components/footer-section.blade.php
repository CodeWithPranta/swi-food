<footer  class="bg-black text-gray-200 py-8">
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
            @foreach($socialLinks as $social)
                <a href="{{ $social['url'] }}" class="text-gray-400 hover:text-white transition">
                    {!! $social['svg'] !!}
                </a>
            @endforeach   
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 border-t border-gray-700">
        <!-- Centered Copyright -->
        <div class="flex justify-center items-center pt-4">
            <span class="text-sm text-center">&copy; {{ $copyrightText }} </span>
        </div>
    </div>

</footer>
