<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Profession extends Model
{
    protected $fillable = ['name', 'description'];

    /**
     * Relationship: A User has one VendorApplication.
     */
    public function vendorApplication(): HasOne
    {
        return $this->hasOne(VendorApplication::class);
    }
}
