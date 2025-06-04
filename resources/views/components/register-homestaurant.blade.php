<section class="relative w-full py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row items-center gap-10">

        <div class="md:w-1/2">
            <img src="{{ asset('storage/' . $h_reg_image) }}" alt="Become a Homestaurant Owner"
                class="w-full h-auto rounded-3xl shadow-lg">
        </div>

        <div class="md:w-1/2 text-center md:text-left">
            <h2 class="text-4xl font-extrabold text-gray-900 mb-6 leading-tight">
                {{ $h_reg_title }}
            </h2>
            <p class="text-lg text-gray-700 mb-8">
                {{ $h_reg_paragraph }}
            </p>
            <x-btn-link href="{{ route('homestaurant.application') }}">
                {{ $h_reg_btn_text }}
            </x-btn-link>
        </div>

    </div>
</section>
