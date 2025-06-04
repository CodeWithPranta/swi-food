function initAutocomplete() {
    console.log("Google Maps API Initialized!");

    if (typeof google === "undefined") {
        console.error("Google Maps API is still undefined!");
        return;
    }

    const input = document.getElementById("location-input");
    if (!input) {
        console.error("Location input field not found!");
        return;
    }

    console.log("Setting up Google Places Autocomplete...");
    const autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.addListener("place_changed", () => {
        console.log("Place changed!");

        const place = autocomplete.getPlace();
        if (!place.geometry) {
            console.error("No geometry found for this place!");
            return;
        }

        const latitude = place.geometry.location.lat();
        const longitude = place.geometry.location.lng();

        document.getElementById("location-input").value = place.formatted_address;
        document.getElementById("location-lat").value = latitude;
        document.getElementById("location-lng").value = longitude;

        console.log("Selected Location:", place.formatted_address);
        console.log("Latitude:", latitude, "Longitude:", longitude);
    });
}
