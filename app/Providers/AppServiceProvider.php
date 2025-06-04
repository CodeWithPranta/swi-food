<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Support\Assets\Js;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentAsset;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Filament::registerRenderHook('scripts.end', fn () => view('vendor.filament.components.scripts'));
        // FilamentAsset::register([
        //     Js::make('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . config('services.google_maps.key') . '&libraries=places'),
        //     Js::make('custom-script', asset('js/custom.js')),
        // ]);

        // FilamentAsset::register([
        //     // Register Google Maps API
        //     Js::make('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . env('GOOGLE_MAPS_API_KEY') . '&libraries=places&callback=initAutocomplete'),

        //     // Register custom script for autocomplete functionality
        //     Js::make('custom-script', asset('js/filament-autocomplete.js')),
        // ]);
    }
}
