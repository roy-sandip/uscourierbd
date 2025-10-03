<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ShipmentUpdate extends Model
{
    protected $casts = [
            'datetime'  => 'datetime',
            'is_public' => 'boolean',
            'published_at' => 'datetime',
    ];
    protected $guarded = [];


    
    protected static function booted()
    {
        static::creating(function ($update) {
                        $update->created_by = auth()->user()->id;
        });
    }

    protected $attributes = [
            'is_public' => true,
    ];

}
