document.addEventListener("DOMContentLoaded", function () {
    console.log("custom.js is running...");

    function initializeAutocomplete() {
        // Get the address input field
        const addressInput = document.querySelector("input[name='address']");
        if (!addressInput) {
            console.error("Address input field not found!");
            return;
        }

        // Check if Google Maps API is loaded
        if (typeof google === "undefined" || !google.maps) {
            console.error("Google Maps API not loaded correctly.");
            return;
        }

        console.log("Initializing Google Places Autocomplete...");

        // Initialize Google Places Autocomplete
        const autocomplete = new google.maps.places.Autocomplete(addressInput, {
            types: ["geocode"],
            componentRestrictions: { country: "BD" }, // Adjust if necessary
        });

        // When a place is selected from the suggestions
        autocomplete.addListener("place_changed", function () {
            const place = autocomplete.getPlace();
            if (!place.geometry) {
                console.error("No details available for input:", addressInput.value);
                return;
            }

            // Log place details for debugging
            console.log("Latitude:", place.geometry.location.lat());
            console.log("Longitude:", place.geometry.location.lng());

            // Set latitude and longitude values into hidden fields
            document.querySelector("input[name='latitude']").value = place.geometry.location.lat();
            document.querySelector("input[name='longitude']").value = place.geometry.location.lng();
        });
    }

    // Initialize autocomplete if Google Maps API is loaded
    if (typeof google !== "undefined" && google.maps) {
        console.log("Google Maps API loaded.");
        initializeAutocomplete();
    } else {
        console.error("Google Maps API is not available.");
    }
});
