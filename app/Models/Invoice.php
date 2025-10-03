<?php

namespace App\Models;

use App\Enums\Currency;
use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $casts = [
                        'status'      => InvoiceStatus::class,
                        'currency'    => Currency::class
                    ];

    protected $attributes = [
            'status'    => InvoiceStatus::ISSUED,
            'currency'  => Currency::BDT,
    ];  


    protected static function booted()
    {
        static::creating(function ($invoice) {
            if (auth()->check() && ! $invoice->created_by) {
                $invoice->created_by = auth()->id();
            }
        });
    }

}
