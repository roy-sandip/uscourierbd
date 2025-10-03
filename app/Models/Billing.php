<?php

namespace App\Models;


use App\Enums\BillingStatus;
use App\Enums\Currency;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $casts = [
                          'status'      => BillingStatus::class,
                          'currency'    => Currency::class
                        ];

    protected $attributes = [
            'status'    => BillingStatus::PENDING,
            'currency'  => Currency::BDT,
    ];                 
    protected $guarded = [];


    public function getBill()                            
    {
        return $this->total_bill;
    }

    public function getPaid()
    {
        return $this->total_paid;
    }

    public function getDue()
    {
        return $this->total_due;
    }



    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }


      /**
     * Fill billing fields from request data.
     * Works for both new and existing models.
     *
     * @param array $data
     * @param \App\Models\User $user
     * @param array $defaults
     * @return $this
     */
    public function fillFromRequest(array $data, array $defaults = []): self
    {
        $user = auth()->user();
        if($user->cannot('admin'))
        {
            return $this;
        }

        $this->fill([
            'status'        => array_key_exists('status', $data) 
                                                            ? $data['status'] 
                                                            : ($this->status ?: ($defaults['status'] ?? BillingStatus::PENDING)),


            'billed_weight'      =>  array_key_exists('billed_weight', $data) 
                                                            ? $data['billed_weight'] 
                                                            : ($this->billed_weight ?: ($defaults['billed_weight'] ?? null)),
            'net_bill'      =>  array_key_exists('net_bill', $data) 
                                                            ? $data['net_bill'] 
                                                            : ($this->net_bill ?: ($defaults['net_bill'] ?? 0)),
            'extra_charge'  =>   array_key_exists('extra_charge', $data) 
                                                            ? $data['extra_charge'] 
                                                            : ($this->extra_charge ?: ($defaults['extra_charge'] ?? 0)),
            'total_paid'    =>   array_key_exists('total_paid', $data) 
                                                            ? $data['total_paid'] 
                                                            : ($this->total_paid ?: ($defaults['total_paid'] ?? 0)),
            'remark'       =>   array_key_exists('remark', $data) 
                                                            ? $data['remark'] 
                                                            : ($this->remark ?: ($defaults['remark'] ?? null)),
        ]);

         // Always calculate total_bill
            $this->total_bill = ($this->net_bill + $this->extra_charge);
            $this->total_paid = min($this->total_paid, $this->total_bill);
            $this->total_due = max($this->total_bill - $this->total_paid, 0);
            
        

        return $this;
    }
}
