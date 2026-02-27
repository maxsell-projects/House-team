<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevelopmentNeighborhoodPhoto extends Model
{
    protected $fillable = ['development_id', 'path', 'order'];

    public function development()
    {
        return $this->belongsTo(Development::class);
    }
}
