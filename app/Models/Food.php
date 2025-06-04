<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Food extends Model
{
    protected $table = 'foods'; // Explicitly set the table name
    // Fillable properties
    protected $fillable = [
        'user_id',
        'category_id',
        'unit_id',
        'name',
        'slug',
        'images',
        'description',
        'quantity',
        'production_cost',
        'price',
        'discount',
        'is_visible',
    ];

    // Cast 'images' field to array since it's stored as JSON
    protected $casts = [
        'images' => 'array',
        'is_visible' => 'boolean',
    ];

    // Relationships

    /**
     * Get the user who created the food item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category of the food item.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the unit of measurement for the food item.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
