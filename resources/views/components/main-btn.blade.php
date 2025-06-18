<button type="submit"
    {{ $attributes->merge(['id' => '', 'class' => 'px-6 py-3 text-md cursor-pointer rounded-full bg-red-700 text-white font-semibold hover:bg-red-800 transition']) }}>
    {{ $slot }}
</button>
