<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(){
        $this->merge([
            // 'total_amount' => $this->input('total_amount'),
            // 'currency_id' => $this->input('currency_id'),
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
            'date' => 'required|date',
            'customer_id' => 'required',
            'total_amount' => 'required',
            'description' => 'nullable|string',
            'currency_id' => 'required|integer',
            'discount' => 'nullable|integer',
            'SaleDetails' => 'required|array',



            'SaleDetails.*.id' => 'nullable|integer',
            'SaleDetails.*.sale_products_id' => 'required|integer|exists:sale_products,id',

            'SaleDetails.*.quantity' => 'required|integer',
            'SaleDetails.*.unit_price' => 'required|numeric',
            'SaleDetails.*.total' => 'required|numeric',
        ];
    }
}
