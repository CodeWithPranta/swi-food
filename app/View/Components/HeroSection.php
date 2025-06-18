<?php
namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HeroSection extends Component
{
    public ?string $heroBackground;
    public ?string $searchBtnTitle;
    public ?string $heroTitle;
    public ?string $titleText;

    public ?string $location;
    public ?string $latitude;
    public ?string $longitude;

    public function __construct(
        ?string $location = '',
        ?string $latitude = '',
        ?string $longitude = ''
    ) {
        $setting = \App\Models\Setting::first();
        $this->heroBackground = $setting?->hero_background;
        $this->searchBtnTitle = $setting?->search_btn_title ?? 'Find homestaurants';
        $this->heroTitle = $setting?->hero_title ?? 'Missing home food?';
        $this->titleText = $setting?->title_text ?? 'Type and select address..';

        $this->location = $location;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function render(): View|Closure|string
    {
        return view('components.hero-section');
    }
}
