<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id', 'vendor_application_id', 'food_id', 'quantity', 'price'
    ];
}
