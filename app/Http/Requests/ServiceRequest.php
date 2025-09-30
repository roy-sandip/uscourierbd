<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
         return auth()->user()->can('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
          $service = $this->route('service'); // Service model instance (or null on store)
         $serviceId = $service?->id;   
        $rules =  [
            'label'         => ['required', 'string', 'min:2', 'max:128', Rule::unique('services', 'label')->ignore($serviceId)],
            'public_label'  => ['required', 'string', 'min:2', 'max:128'],
            'company'       => ['nullable', 'integer', 'exists:App\Models\Company']
        ];

        
        return $rules;
    }
}
