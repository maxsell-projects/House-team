<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'type', 'status',
        'location', 'address', 'postal_code', 'city', 'latitude', 'longitude',
        'price', 'area_gross', 'area_useful', 'area_land',
        'bedrooms', 'bathrooms', 'garages', 'floor', 'orientation', 'built_year', 'condition', 'energy_rating',
        'has_lift', 'has_garden', 'has_pool', 'has_terrace', 'has_balcony', 
        'has_air_conditioning', 'has_heating', 'is_accessible', 'is_furnished', 'is_kitchen_equipped',
        'cover_image', 'video_url', 'whatsapp_number',
        'is_featured', 'is_visible',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'area_gross' => 'decimal:2',
        'has_pool' => 'boolean',
        'has_garden' => 'boolean',
        'is_furnished' => 'boolean',
        'is_kitchen_equipped' => 'boolean',
    ];

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }
}