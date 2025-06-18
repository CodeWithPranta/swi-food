<x-landing-layout>
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl pt-15 text-center font-medium mb-6 text-zinc-800">{{ __('Apply to open a homestaurant') }}</h2>

        <form method="POST" action="" enctype="multipart/form-data" class="max-w-lg mx-auto p-6">
            @csrf

            <div class="mb-4">
                <label for="kitchen_name" class="block text-sm font-medium text-zinc-800">{{ __('Homestaurant Name') }}</label>
                <input type="text" name="kitchen_name" placeholder="e.g. More Momo" id="kitchen_name" required class="mt-1 text-zinc-700 block w-full border-gray-300 rounded-full shadow-sm focus:border-gray-800 focus:ring-gray-800">
                @error('kitchen_name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="chef_name" class="block text-sm font-medium text-zinc-800">{{ __('Chef Name') }}</label>
                <input type="text" name="chef_name" id="chef_name" placeholder="e.g. Emma" required class="mt-1 text-zinc-700 block w-full border-gray-300 rounded-full shadow-sm focus:border-gray-800 focus:ring-gray-800">
                @error('chef_name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="profession_id" class="block text-sm font-medium text-zinc-800">{{ __('Profession') }}</label>
                <select name="profession_id" id="profession_id" required class="mt-1 text-zinc-700 block w-full border-gray-300 rounded-full shadow-sm focus:border-gray-800 focus:ring-gray-800">
                    <option value="">{{ __('Select Profession') }}</option>
                    @foreach($professions as $profession)
                        <option value="{{ $profession->id }}">{{ $profession->name }}</option>
                    @endforeach
                </select>
                @error('profession_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="phone_number" class="block text-sm font-medium text-zinc-800">{{ __('Phone Number') }}</label>
                <input type="text" name="phone_number" placeholder="e.g. +41 43 123 45 67" id="phone_number" required class="mt-1 text-zinc-700 block w-full border-gray-300 rounded-full shadow-sm focus:border-gray-800 focus:ring-gray-800">
                @error('phone_number')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                <textarea name="description" placeholder="Write short description on your kitchen and journey..." id="description" rows="4" required class="mt-1 text-zinc-700 block w-full border-gray-300 rounded-4xl shadow-sm focus:border-gray-800 focus:ring-gray-800"></textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="location" class="block text-sm font-medium text-zinc-800">{{ __('Your Location') }}</label>
                <input type="text" name="location" id="location" placeholder="Type & select from the suggestions"
                    class="mt-1 block w-full border-gray-300 rounded-full shadow-sm focus:border-gray-800 focus:ring-gray-800" required>
                @error('location') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">

            <div class="mb-4">
                <label for="cover_photo" class="block text-sm font-medium text-zinc-800">{{ __('Cover Photo') }}</label>
                <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
                    class="mt-1 block w-full border-gray-300 rounded-full shadow-sm focus:border-gray-800 focus:ring-gray-800" onchange="previewCoverPhoto(event)">
                <img id="cover_photo_preview" class="mt-2 rounded-lg w-full max-h-60 object-cover hidden" />
                @error('cover_photo') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="attachments" class="block text-sm font-medium text-zinc-800">{{ __('Attachments (Government ID Photos)') }}</label>
                <input type="file" name="attachments[]" id="attachments" accept="image/*" multiple
                    class="mt-1 block w-full border-gray-300 rounded-full shadow-sm focus:border-gray-800 focus:ring-gray-800" onchange="previewAttachments(event)">
                <div id="attachments_preview" class="mt-2 grid grid-cols-3 gap-2"></div>
                @error('attachments') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            
            <x-main-btn class="w-full mt-2">{{__('Apply')}}</x-main-btn>
        </form>
    </div>
    
    <script>
        function previewCoverPhoto(event) {
            const input = event.target;
            const preview = document.getElementById('cover_photo_preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewAttachments(event) {
            const files = event.target.files;
            const previewContainer = document.getElementById('attachments_preview');
            previewContainer.innerHTML = ''; // Clear previous previews

            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = "w-full h-28 object-cover rounded-lg border";
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
        
    </script>

    <script>
        function initAutocomplete() {
            const input = document.getElementById('location');
            const autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function () {
                const place = autocomplete.getPlace();
                if (place.geometry) {
                    document.getElementById('latitude').value = place.geometry.location.lat();
                    document.getElementById('longitude').value = place.geometry.location.lng();
                }
            });
        }
    </script>

    {{-- Load Google Maps JS with callback --}}
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initAutocomplete" async defer></script>

    <x-footer-section />
</x-landing-layout>
