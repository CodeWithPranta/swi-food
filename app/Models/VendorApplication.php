<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VendorApplication extends Model
{
    protected $fillable = [
        'user_id',          // Foreign key for users
        'kitchen_name',     // Unique kitchen name
        'cover_photo',      // Nullable cover photo
        'chef_name',        // Chef's name
        'attachments',      // JSON attachments
        'profession_id',    // Foreign key for professions
        'phone_number',     // Contact number
        'description',      // Detailed description
        'links',            // JSON field for external links (nullable)
        'location',          // Address field
        'latitude',         // Latitude for location
        'longitude',        // Longitude for location
        'is_approved',    // Approval status (default false)
        'opening_hours',    // JSON field for opening hours per week
    ];

    protected $casts = [
        'attachments' => 'array', // Ensure attachments JSON is cast as an array
        'links' => 'array',       // Ensure links JSON is cast as an array
        'opening_hours' => 'array', // Cast opening hours as JSON
    ];

    /**
     * Relationship: A VendorApplication belongs to a User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: A VendorApplication belongs to a Profession.
     */
    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class);
    }

    public function foods(): HasMany
    {
        // Define a one-to-many relationship with the Food model
        // This assumes that the 'user_id' in the Food model corresponds to the 'user_id' in VendorApplication
        // Adjust the foreign key and local key as necessary based on your database schema
        return $this->hasMany(Food::class, 'user_id', 'user_id');
    }


    // public function charges()
    // {
    //     return $this->hasMany(DeliveryCharge::class, 'user_id');
    // }


    protected $appends = [];

    /**
     * ADD THE FOLLOWING METHODS TO YOUR VendorApplication MODEL
     *
     * The 'latitude' and 'longitude' attributes should exist as fields in your table schema,
     * holding standard decimal latitude and longitude coordinates.
     *
     * The 'address' attribute should NOT exist in your table schema, rather it is a computed attribute,
     * which you will use as the field name for your Filament Google Maps form fields and table columns.
     *
     * You may of course strip all comments, if you don't feel verbose.
     */

    /**
    * Returns the 'latitude' and 'longitude' attributes as the computed 'address' attribute,
    * as a standard Google Maps style Point array with 'lat' and 'lng' attributes.
    *
    * Used by the Filament Google Maps package.
    *
    * Requires the 'address' attribute be included in this model's $fillable array.
    *
    * @return array
    */

    public function getAddressAttribute(): array
    {
        return [
            "lat" => (float) ($this->latitude ?? 0),
            "lng" => (float) ($this->longitude ?? 0),
            "location" => $this->location,
        ];
    }

    /**
    * Takes a Google style Point array of 'lat' and 'lng' values and assigns them to the
    * 'latitude' and 'longitude' attributes on this model.
    *
    * Used by the Filament Google Maps package.
    *
    * Requires the 'address' attribute be included in this model's $fillable array.
    *
    * @param ?array $location
    * @return void
    */
    public function setAddressAttribute(?array $location): void
    {
        if (is_array($location))
        {
            if (is_array($location)) {
                $this->attributes['latitude'] = $location['lat'] ?? null;
                $this->attributes['longitude'] = $location['lng'] ?? null;
                $this->attributes['location'] = $location['location'] ?? $location['formatted_address'] ?? null;
            }
        }
    }

    /**
     * Get the lat and lng attribute/field names used on this table
     *
     * Used by the Filament Google Maps package.
     *
     * @return string[]
     */
    public static function getLatLngAttributes(): array
    {
        return [
            'lat' => 'latitude',
            'lng' => 'longitude',
        ];
    }

   /**
    * Get the name of the computed location attribute
    *
    * Used by the Filament Google Maps package.
    *
    * @return string
    */
    public static function getComputedLocation(): string
    {
        return 'address';
    }

    // Create vendors nearby scope filter
    public function scopeNearby(Builder $query, $latLng, $radius)
    {
        if (!$latLng) {
            return $query;
        }

        [$lat, $lng] = explode(',', $latLng);
        if (!$lat || !$lng) {
            return $query;
        }

        $haversine = "(6371 * acos(cos(radians($lat)) * cos(radians(latitude)) * cos(radians(longitude) - radians($lng)) + sin(radians($lat)) * sin(radians(latitude))))";
        return $query->select('vendor_applications.*')
            ->selectRaw("{$haversine} AS distance")
            ->whereRaw("{$haversine} < ?", [$radius])
            ->orderBy('distance');
    }
}
