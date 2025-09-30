<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Agent extends Model
{
    protected $guarded = []; //make all fillable
    protected $casts = [
                'is_active' => 'boolean',
                'is_admin'  => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

        
    
    protected function phone(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->contact
        );
    }


    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }



    public function invoices()
    {
        return $this->hasMany(AgentInvoice::class);
    }


    public function billings()
    {
        return  $this->hasManyThrough(AgentBilling::class, Shipment::class);
    }

    

    public function lastInvoice()
    {
        return $this->hasOne(AgentInvoice::class)->latest('id')->withDefault();
    }


    public function showActiveStatus()
    {
        return $this->is_active ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="far fa-times-circle text-danger"></i>';
    }
    
    public function showAdminStatus()
    {
        return $this->is_admin ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="far fa-times-circle text-danger"></i>';
    }

}
