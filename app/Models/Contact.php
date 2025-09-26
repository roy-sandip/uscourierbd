<?php

namespace App\Models;

use App\Enums\ContactType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
     
    protected $guarded = []; //make all fillable
    
    protected function phone(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->primary_contact
        );
    }

    protected function countryName(): Attribute
    {
        $countryName = collect(config('countries'))
                            ->firstWhere('code', $this->country);
        return Attribute::make(
            get: fn () => $countryName['name']
        );
    }



    public function fillFromRequest(array $data, ContactType $type, array $defaults = []): self
    {
        $this->fill([
            'name'      => array_key_exists('name', $data) ? $data['name'] : ($this->name ?? ($defaults['name'] ?? null)),
            'attn'      => array_key_exists('attn', $data) ? $data['attn'] : ($this->attn ?? ($defaults['attn'] ?? $data['name']) ?? null),
            'street'    => array_key_exists('street', $data) ? $data['street'] : ($this->street ?? ($defaults['street'] ?? null)),
            'city'      => array_key_exists('city', $data) ? $data['city'] : ($this->city ?? ($defaults['city'] ?? null)),
            'state'     => array_key_exists('state', $data) ? $data['state'] : ($this->state ?? ($defaults['state'] ?? null)),
            'zip'       => array_key_exists('zip', $data) ? $data['zip'] : ($this->zip ?? ($defaults['zip'] ?? null)),
            'country'   => array_key_exists('country', $data) ? $data['country'] : ($this->country ?? ($defaults['country'] ?? null)),
            'primary_contact' => array_key_exists('primary_contact', $data) ? $data['primary_contact'] : ($this->primary_contact ?? ($defaults['primary_contact'] ?? null)),
            'alt_contact' =>  array_key_exists('alt_contact', $data) ? $data['alt_contact'] : ($this->alt_contact ?? ($defaults['alt_contact'] ?? null)),
            'email' => array_key_exists('email', $data) ? $data['email'] : ($this->email ?? ($defaults['email'] ?? null)),
            'type' => $type,
        ]);

        return $this;
    }


}
