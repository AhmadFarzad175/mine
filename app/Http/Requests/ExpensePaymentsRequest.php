<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpensePaymentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'currency_id' => $this->input('currency.id'),
        ]);
        // dd(Request());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bill_expenses_id' => 'required|numeric|exists:bill_expenses,id',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'currency_id' => 'required',
            'description' => 'nullable|string',
        ];
    }
}
