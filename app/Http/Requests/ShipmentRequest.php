<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\BillingStatus;
use Carbon\Carbon;

class ShipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $countryCodes = collect(config('countries'))->pluck('code')->toArray();

        $rules =  [
            'reference' => ['nullable', 'string', 'max:64'],
            'pieces' => ['required', 'integer', 'min:1', 'max:100'],
            'agent_id' => ['required', 'integer', 'exists:App\Models\Agent,id'],
            'service_id' => ['required', 'integer', 'exists:App\Models\Service,id'],
            
            'shipper' => ['array'],
            'shipper.name' => ['required', 'string', 'min:4', 'max:190'],
            'shipper.street' => ['required', 'string', 'min:4', 'max:500'],
            'shipper.city' => ['nullable', 'string', 'min:3', 'max:190'],
            'shipper.zip' => ['nullable', 'string', 'min:4', 'max:32'],
            'shipper.country' => ['required', 'string', 'min:2', 'max:2', Rule::in($countryCodes)],
            'shipper.primary_contact' => ['nullable', 'string', 'min:4', 'max:190'],
            'shipper.email' => ['nullable', 'email'],

            'receiver.name' => ['required', 'string', 'min:4', 'max:190'],
            'receiver.street' => ['required', 'string', 'min:2', 'max:500'],
            'receiver.city' => ['required', 'string', 'min:2', 'max:190'],
            'receiver.state' => ['nullable', 'string', 'max:190'],
            'receiver.zip' => ['required', 'string', 'min:2', 'max:32'],
            'receiver.country' => ['required', 'string', 'min:2', 'max:2', Rule::in($countryCodes)],
            'receiver.primary_contact' => ['required', 'string', 'min:2', 'max:190'],
            'receiver.email' => ['nullable', 'email'],

            'description' => ['required', 'string', 'min:2', 'max:99999'],
            'gross_weight' => ['required', 'decimal:0,2,', 'min:0', 'max:999'],
            'dimensions'    => ['array'],
            'dimensions.length' => ['integer', 'nullable', 'required_with:dimensions.width,dimensions.height'],
            'dimensions.width' => ['integer', 'nullable', 'required_with:dimensions.length,dimensions.height'],
            'dimensions.height' => ['integer', 'nullable', 'required_with:dimensions.length,dimensions.width'],
            
            
            'operator' => ['nullable', 'string', 'max:190'],
            'received_at' => ['required', "date_format:d/m/Y",  'after_or_equal:' . now()->subYear()->toDateString(),'before_or_equal:' . now()->addYear()->toDateString()],
            'est_delivery_date' => ['nullable', "date_format:d/m/Y",  'after_or_equal:' . now()->subYear()->toDateString(),'before_or_equal:' . now()->addYear()->toDateString()],

            'billing' => ['array'],
            'billing.billed_weight' => ['required', 'gte:gross_weight', 'decimal:0,2', 'min:0', 'max:999'],
            'billing.net_bill' => ['nullable', 'numeric', 'max:999999'],
            'billing.extra_charge' => ['nullable', 'numeric', 'max:999999'],
            'billing.total_paid' => ['nullable', 'numeric', 'max:999999'],
            'billing.status' => ['string', Rule::in(BillingStatus::list(BillingStatus::INVOICED)) ],
            'billing.remark' => ['nullable', 'string', 'max:190'],
        ];


       

        return $rules;
    }


  public function attributes(): array
  {
    return [
        'shipper.name' => 'Shipper Name',
        'shipper.street' => 'Shipper Street address',
        'shipper.city' => 'Shipper City',
        'shipper.zip' => 'Shipper post code',
        'shipper.country' => 'Shipper Country',
        'shipper.phone' => 'Shipper phone number',
        'shipper.email' => 'Shipper Email',

        'receiver.name' => 'Receiver Name',
        'receiver.street' => 'Receiver Street address',
        'receiver.city' => 'Receiver city',
        'receiver.state' => 'Receiver state',
        'receiver.zip' => 'Receiver ZIP',
        'receiver.country' => 'Receiver Country',
        'receiver.phone' => 'Receiver phone number',
        'receiver.email' => 'Receiver Email',


        'billing.billed_weight' => 'Billed Weight',
        'billing.extra_charge' => 'Extra Charge',
        'billing.paid' => 'Paid Amount',
        'billing.status' => 'Billing Status',
        'billing.comment' => 'Billing Comment',

        'dimensions.length' => 'Package Length',
        'dimensions.width' => 'Package Width',
        'dimensions.height' => 'Package height',

        'received_at' => 'Received at',
        'est_delivery_date' => 'Estimated Delivery Date',

    ];
  }


  public function messages()
  {
    return [
        '*.after_or_equal' => ':attribute is too old, use a more recent date',
        '*.before_or_equal' => ':attribute is too future, use a more recent date',
    ];
  }






    
}
