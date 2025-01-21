<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StakeholderAccountRequest extends FormRequest
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
            'currency_id' => $this->input('currencyId'),
            'stakeholder_id' => $this->input('stakeholderId'),
            'pay_or_receive' => $this->input('payOrReceive'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'amount' => 'required',
            'currency_id' => 'required',
            'stakeholder_id' => 'required',
            'pay_or_receive' => 'nullable',

        ];
    }
}
