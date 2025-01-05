<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanPaymentReceivedRequest extends FormRequest
{
    public function authorize()
    {
        return true; // You can implement authorization logic here if needed
    }

    public function rules()
    {
        return [
            'loan_people_id' => 'required|exists:loan_people,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
            'currency_id' => 'required|exists:currencies,id',
        ];
    }

    protected function prepareForValidation()
    {
        // Assuming that `currency` is an object and `currency_id` is within that object
        $this->merge([
            'currency_id' => $this->currency['id'] ?? null, // Fetch the ID from the nested currency object
        ]);
    }
}
