<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevelopmentFraction extends Model
{
    protected $fillable = [
        'development_id', 'ref', 'block', 'floor', 'typology', 
        'abp', 'balcony_area', 'parking_spaces', 'price', 
        'floor_plan_path', 'remax_id', 'status'
    ];

    protected $casts = [
        'abp' => 'decimal:2',
        'balcony_area' => 'decimal:2',
        'price' => 'decimal:2',
        'parking_spaces' => 'integer'
    ];

    public function development()
    {
        return $this->belongsTo(Development::class);
    }
}
