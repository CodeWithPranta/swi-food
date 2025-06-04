<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Colors extends Component
{
    /**
     * Create a new component instance.
     */
    public ?string $primaryBgColor;
    public ?string $primaryTextColor;
    public ?string $secondaryBgColor;
    public ?string $secondaryTextColor;
    public ?string $hoverBgColor;
    public ?string $hoverTextColor;
    public function __construct()
    {
        $setting = \App\Models\Setting::first();
        $this->primaryBgColor = $setting?->primary_bg_color;
        $this->primaryTextColor = $setting?->primary_text_color;
        $this->secondaryBgColor = $setting?->secondary_bg_color;
        $this->secondaryTextColor = $setting?->secondary_text_color;
        $this->hoverBgColor = $setting?->hover_bg_color;
        $this->hoverTextColor = $setting?->hover_text_color;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.colors');
    }
}
