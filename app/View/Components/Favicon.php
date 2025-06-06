<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Favicon extends Component
{
    /**
     * Create a new component instance.
     */
    public ?string $favicon;
    public function __construct()
    {
        $setting = \App\Models\Setting::first();
        $this->favicon = $setting?->favicon;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.favicon');
    }
}
