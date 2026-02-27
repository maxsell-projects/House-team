<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Development extends Model
{
    protected $fillable = [
        'title', 'slug', 'status', 'typologies', 'areas', 
        'built_year', 'energy_rating', 'description', 'neighborhood_description',
        'latitude', 'longitude', 'brochure_path', 
        'finishes_map_path', 'development_sheet_path', 
        'order', 'is_visible', 'consultant_id'
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'order' => 'integer'
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('created_at', 'desc');
    }

    public function fractions()
    {
        return $this->hasMany(DevelopmentFraction::class);
    }

    public function photos()
    {
        return $this->hasMany(DevelopmentPhoto::class)->orderBy('order', 'asc');
    }

    public function coverPhoto()
    {
        return $this->hasOne(DevelopmentPhoto::class)->where('is_cover', true);
    }

    public function neighborhoodPhotos()
    {
        return $this->hasMany(DevelopmentNeighborhoodPhoto::class)->orderBy('order', 'asc');
    }

    public function consultant()
    {
        return $this->belongsTo(Consultant::class);
    }
}
