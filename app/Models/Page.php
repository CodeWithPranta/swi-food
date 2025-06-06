<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    // Fillable properties
    protected $fillable = [
        'title',
        'slug',
        'content'
    ];
}
