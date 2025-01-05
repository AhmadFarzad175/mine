<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalePaymentsRequest extends FormRequest
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
    public function prepareForValidation()
    {
        $this->merge([
            'currency_id' => $this->input('currency.id'), // Extract the id from the currency array
        ]);
    }
    

    public function rules(): array
    {
        return [
            'sales_id' => 'required',
            'date' => 'required',
            'amount' => 'required|numeric',
            'currency_id' => 'required|numeric',
            'description' => 'nullable|string',
        ];
    }

}
