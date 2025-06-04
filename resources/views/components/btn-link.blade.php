<a
    {{ $attributes->merge([
        'href' => '#',
        'class' =>
            'inline-block px-8 py-4 rounded-full bg-red-700 text-white font-semibold text-lg hover:bg-red-800 transition',
    ]) }}>
    {{ $slot }}
</a>
