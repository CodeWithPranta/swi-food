<a
    {{ $attributes->merge([
        'href' => '#',
        'class' =>
            'inline-block px-6 py-3 rounded-full bg-red-700 text-white text-md font-semibold hover:bg-red-800 transition',
    ]) }}>
    {{ $slot }}
</a>
