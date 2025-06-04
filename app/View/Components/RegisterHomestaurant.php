<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RegisterHomestaurant extends Component
{
    /**
     * Create a new component instance.
     */
    public ?string $h_reg_image;
    public ?string $h_reg_title;
    public ?string $h_reg_paragraph;
    public ?string $h_reg_btn_text;
    public function __construct()
    {
        $setting = \App\Models\Setting::first();
        $this->h_reg_image = $setting?->h_reg_image;
        $this->h_reg_title = $setting?->h_reg_title;
        $this->h_reg_paragraph = $setting?->h_reg_paragraph;
        $this->h_reg_btn_text = $setting?->h_reg_btn_text;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.register-homestaurant');
    }
}
