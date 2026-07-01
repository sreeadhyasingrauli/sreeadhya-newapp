<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
             'company_name' => 'required|string|max:255',
            'address_line1' => 'nullable|string|max:255',
             'address_line2' => 'nullable|string|max:255',
             'city' => 'nullable|string|max:255',
             'state' => 'nullable|string|max:255',
             'pin_code' => 'nullable|string|max:10',
             'country' => 'nullable|string|max:255',
             'contact_number' => 'nullable|string|max:20',
             'email' => 'nullable|string|max:100',
             'website' => 'nullable|string|max:255',
             'pan_number' => 'nullable|string|max:10',
             'gst_number' => 'nullable|string|max:20',
             'bank_name' => 'nullable|string|max:255',
             'account_number' => 'nullable|string|max:20',
             'ifsc_code' => 'nullable|string|max:20',
             'branch_name' => 'nullable|string|max:255',
             
            //
        ];
    }
}
