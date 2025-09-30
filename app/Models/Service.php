<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Company;

class Service extends Model
{
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class)->withDefault(['name' => 'Unknown']);
    }
}
