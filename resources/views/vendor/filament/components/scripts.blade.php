<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&libraries=places&callback=initAutocomplete">
</script>

<script>
    function initAutocomplete() {
        console.log("Google Maps API Loaded in Filament!");

        const input = document.getElementById("location-input");
        if (!input) {
            console.error("Filament Address Input Not Found!");
            return;
        }

        const autocomplete = new google.maps.places.Autocomplete(input, {
            types: ['geocode'],
            componentRestrictions: {
                country: "BD"
            }
        });

        autocomplete.addListener("place_changed", function() {
            const place = autocomplete.getPlace();
            if (!place.geometry) {
                console.error("No location data found!");
                return;
            }

            const latitude = place.geometry.location.lat();
            const longitude = place.geometry.location.lng();
            const address = place.formatted_address;

            // Emit to Livewire
            Livewire.emit('updateLocation', address, latitude, longitude);

            console.log("Selected Location:", address);
            console.log("Latitude:", latitude, "Longitude:", longitude);
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        if (typeof google !== "undefined") {
            initAutocomplete();
        } else {
            console.log("Google API Not Loaded");
        }
    });
</script>
