<section class="relative flex items-center justify-center w-full h-[90vh] bg-gray-50 pt-24"
    style="background-image: url('{{ asset('storage/' . $heroBackground) }}'); background-size: cover;
    background-position: center; background-repeat: no-repeat;">

    <!-- Overlay for readability -->
    <div class="absolute inset-0 bg-black/40"></div>
    <!-- Try bg-black/40 if you want a darker overlay -->

    <div class="relative text-center max-w-2xl px-4">
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6 leading-tight">
            {{ $heroTitle }}
        </h1>
        <p class="text-lg text-white mb-8">
            {{ $titleText }}
        </p>
        <form id="location-form" action="{{ route('store-location') }}" method="POST"
            class="flex flex-col sm:flex-row items-center justify-center gap-4">
            @csrf
            <input type="text" id="location-input" name="location" value="{{ $location }}"
                placeholder="Enter your address..."
                class="w-full sm:w-96 px-6 py-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-red-500 focus:outline-none"
                required>
            <input type="hidden" name="latitude" value="{{ $latitude }}" id="location-lat">
            <input type="hidden" name="longitude" value="{{ $longitude }}" id="location-lng">
            <x-main-btn id="search-btn">{{ $searchBtnTitle }}</x-main-btn>
        </form>
    </div>

    <!-- Google Maps Places API Script -->
    <script>
        function initAutocomplete() {
            const input = document.getElementById("location-input");
            const autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener("place_changed", () => {
                const place = autocomplete.getPlace();
                const latitude = place.geometry.location.lat();
                const longitude = place.geometry.location.lng();

                document.getElementById("location-input").value = place.formatted_address;
                document.getElementById("search-btn").disabled = false;
                document.getElementById("search-btn").classList.remove("disabled");

                document.getElementById("location-lat").value = latitude;
                document.getElementById("location-lng").value = longitude;
            });

            const searchBtn = document.getElementById("search-btn");

            searchBtn.addEventListener("click", () => {
                document.getElementById("location-form").submit();
            });
        }
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initAutocomplete">
    </script>
</section>
