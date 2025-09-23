<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_application_id',
        'special_instructions',
        'expected_receive_time',
        'delivery_option',
        'contact_name',
        'contact_phone',
        'delivery_area',
        'delivery_address',
        'payment_method',
        'delivery_charge',
        'total_price',
        'status',
    ];

    protected $casts = [
        'expected_receive_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(VendorApplication::class, 'vendor_application_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function vendorApplication()
    {
        return $this->belongsTo(\App\Models\VendorApplication::class, 'vendor_application_id');
    }

}

?>
