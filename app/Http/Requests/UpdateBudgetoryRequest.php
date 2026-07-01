<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBudgetoryRequest extends FormRequest
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
            'budgetory_number' => 'required|integer|unique:budgetories,budget_number,' . $this->budgetory->id,
            'budgetory_date' => 'required|date',
            'customer_name' => 'required|string|max:255',
            'address_to' => 'required|string|max:255',
            'budget_description' => 'nullable|string',
            'budget_amount' => 'required|numeric|min:0',
            'payment_terms' => 'nullable|string|max:255',
            'delivery_terms' => 'nullable|string|max:255',
            'warranty_terms' => 'nullable|string|max:255',
            'offer_validity' => 'nullable|string|max:255',
            'validity_end_date' => 'nullable|date|after:budgetory_date',
            'status' => 'nullable|string|max:255'
        ];
    }
}
