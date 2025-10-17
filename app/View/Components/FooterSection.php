<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FooterSection extends Component
{
    /**
     * Create a new component instance.
     */
    public ?string $copyrightText;
    public ?string $favicon;
    public ?object $pages;
    public ?object $menuPages;
    public ?array $socialLinks;
    public function __construct()
    {
        $pages = \App\Models\Page::where('menu_position', 'footer')
            ->where('is_active', true)->get();
        $this->pages = $pages;
        $menuPages = \App\Models\Page::where('menu_position', 'inside_menu')
            ->where('is_active', true)->get();
        $this->menuPages = $menuPages;
        $setting = \App\Models\Setting::first();
        $this->favicon = $setting?->favicon;
        $this->copyrightText = $setting?->copyright_text ?? '2025 Homestaurant. All rights reserved.';
        $this->socialLinks = $setting?->social_links ?? [];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.footer-section');
    }
}
