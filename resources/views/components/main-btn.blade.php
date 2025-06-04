<button type="submit"
    {{ $attributes->merge(['id' => '', 'class' => 'px-8 py-3 cursor-pointer rounded-full bg-red-700 text-white font-semibold hover:bg-red-800 transition']) }}>
    {{ $slot }}
</button>
