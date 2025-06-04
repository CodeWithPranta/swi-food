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
    public function __construct()
    {
        $pages = \App\Models\Page::all();
        $this->pages = $pages;
        $setting = \App\Models\Setting::first();
        $this->favicon = $setting?->favicon;
        $this->copyrightText = $setting?->copyright_text ?? '© 2025 Homestaurant. All rights reserved.';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.footer-section');
    }
}
