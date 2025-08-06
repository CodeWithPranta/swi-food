<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'user_id', // User who owns the payment method
        'bank_name', // Name of the payment method (e.g., Credit Card, PayPal)
        'details', // Description of the payment method
        'is_active', // Whether the payment method is active or not
    ];
}
