<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\ShipmentPermissionScope;
use App\Models\Contact;
use App\Models\Agent;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;



class Shipment extends Model
{
    use HasFactory;
    use \Znck\Eloquent\Traits\BelongsToThrough;

    const AWB_SERIAL = 5200000;
    
    protected $guarded = [];
    protected $casts = [
        'received_at'           => 'datetime',
        'est_delivery_date'     => 'datetime',
        'dimensions'            => 'array',
    ];
    
    protected static function booted()
    {
        static::creating(function ($shipment) {
                        $shipment->created_by = auth()->user()->id;
        });
    }



    // Accessor for dimensions with defaults
    protected function dimensions(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (object) array_merge([
                'length' => null,
                'width'  => null,
                'height' => null,
            ], (is_array($value) ? $value : (array) json_decode($value, true)) ?? [])
        );
    }

     protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ShipmentPermissionScope);
    }

    protected function bookingDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->received_at
        );
    }

    protected function operator(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->received_by
        );
    }
    


    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function shipper()
    {
        return $this->belongsTo(Contact::class, 'sender_id')->withDefault();
    }

    public function receiver()
    {
        return $this->belongsTo(Contact::class, 'receiver_id')->withDefault();
    }

    public function billing()
    {
        return $this->hasOne(Billing::class)->withDefault();
    }

    public function invoice()
    {
        return $this->belongsToThrough(Invoice::class, Billing::class);
    }
 

    
    public function setAWB()
    {
        $this->awb = self::AWB_SERIAL + $this->id;
        return $this;
    }


    public function fillFromRequest(array $data, array $defaults = []): self
    {
        $user = auth()->user();
        $this->fill([
            'agent_id'       => $user->can('admin')
                                    ? (array_key_exists('agent_id', $data) ? $data['agent_id'] : ($this->agent_id ?? ($defaults['agent_id'] ?? null)))
                                    : ($this->agent_id ?? $user->agent_id),
            'service_id'     => array_key_exists('service_id', $data) ? $data['service_id'] : ($this->service_id ?? ($defaults['service_id'] ?? null)),
            'reference'      => array_key_exists('reference', $data) ? $data['reference'] : ($this->reference ?? ($defaults['reference'] ?? null)),
            'pieces'         => array_key_exists('pieces', $data) ? $data['pieces'] : ($this->pieces ?? ($defaults['pieces'] ?? 1)),
            'gross_weight'     => array_key_exists('gross_weight', $data) ? $data['gross_weight'] : ($this->gross_weight ?? ($defaults['gross_weight'] ?? null)),
            //'billed_weight'  => array_key_exists('billed_weight', $data) ? $data['billed_weight'] : ($this->billed_weight ?? ($defaults['billed_weight'] ?? null)),
            'description'    => array_key_exists('description', $data) ? $data['description'] : ($this->description ?? ($defaults['description'] ?? null)),
            'dimensions'     => array_key_exists('dimensions', $data) ? $data['dimensions'] : ($this->dimensions ?? ($defaults['dimensions'] ?? null)),
            'received_at'    => array_key_exists('received_at', $data)
                                    ? Carbon::createFromFormat('d/m/Y', $data['received_at'])
                                    : ($this->received_at ?? ($defaults['received_at'] ?? now())),
            'received_by'    => $data['received_by'] ?? $this->received_by ?? ($defaults['received_by'] ?? $user->userid),
            'created_by'     => $this->created_by ?? ($defaults['created_by'] ?? $user->id),
        ]);

        // Calculate volumetric weight
        if ($this->dimensions) {
            $dims = is_array($this->dimensions) ? $this->dimensions : (array)$this->dimensions;
            $this->volumetric_weight = round(array_product($dims) / 5000, 2);
        }
            
        return $this;
    }
  

    /**
     * Shipment Updates
     */
    public function updates()
    {
        return $this->hasMany(ShipmentUpdate::class);
    }

    /**
     * Create new update
     */
    public function addUpdate(array $update)
    {
        return $this->updates()->create($update);
    }

    
}
