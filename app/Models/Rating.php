<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'ratings';

    protected $fillable = [
        'user_id',
        'vendor_application_id',
        'rating',
        'review',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendorApplication()
    {
        return $this->belongsTo(VendorApplication::class);
    }
}
