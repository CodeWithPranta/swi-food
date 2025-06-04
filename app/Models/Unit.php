<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = ['name', 'short_name'];

    public function foods(): HasMany
    {
        return $this->hasMany(Food::class);
    }
}
