<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevelopmentPhoto extends Model
{
    protected $fillable = [
        'development_id', 'path', 'is_cover', 'order'
    ];

    protected $casts = [
        'is_cover' => 'boolean',
        'order' => 'integer'
    ];

    public function development()
    {
        return $this->belongsTo(Development::class);
    }
}
