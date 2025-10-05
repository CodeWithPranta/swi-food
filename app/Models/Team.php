<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'designation', 'email', 'links', 'biography', 'image'];

    protected $casts = [
        'links' => 'array',
    ];
}
