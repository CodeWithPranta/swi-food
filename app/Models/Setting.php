<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'favicon',
        'logo',
        'og_image',
        'hero_title',
        'title_text',
        'meta_description',
        'keywords',
        'copyright_text',
        'hero_background',
        'search_btn_title',
        'h_reg_image',
        'h_reg_title',
        'h_reg_paragraph',
        'h_reg_btn_text',
        'primary_bg_color',
        'primary_text_color',
        'secondary_bg_color',
        'secondary_text_color',
        'menu_card_bg_image',
        'hover_bg_color',
        'hover_text_color',
        'secondary_logo',
        'social_links',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];
}
