<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryCharge extends Model
{
    protected $fillable = [
        'user_id', // User who sets the delivery charge
        'area', // Area for which the delivery charge applies
        'charge', // Delivery charge amount
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
