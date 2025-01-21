<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StakeholderStatementRequest extends FormRequest
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
        return [
            'currency_id' => 'required', // Foreign key to Currency
            'stakeholder_id' => 'required', // Foreign key to Stakeholder
            'stakeholder_account_id' => 'nullable', // Foreign key to StakeholderAccount
            'money_account_id' => 'required', // Foreign key to StakeholderAccount
            'record_id' => 'nullable',
            'type' => 'required',
            'date' => 'required', 'date',
            'amount' => 'required',
            'pay_or_receive' => 'nullable', 'string', 'in:PayMoney,ReceiveMoney', // Add specific values for pay_or_receive
            'ref' => 'nullable',
            'description' => 'nullable',
        ];
    }
}
