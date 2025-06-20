<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SecondaryLogo extends Component
{
    /**
     * Create a new component instance.
     */
    public ?string $secondaryLogo = null;

    public function __construct()
    {
        $setting = \App\Models\Setting::first();
        $this->secondaryLogo = $setting?->secondary_logo;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.secondary-logo');
    }
}
