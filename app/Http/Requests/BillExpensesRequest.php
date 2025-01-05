<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillExpensesRequest extends FormRequest
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
            // 'currency_id' => $this->input('currency'),
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
            'expense_category_id' => 'required|numeric',
            'supplier_id' => 'required',
            'bill_number' => 'required|numeric',
            'amount' => 'required|numeric',
            'currency_id' => 'required|',
            'paid' => 'required|numeric',
            'description' => 'nullable|',
            'Bill_expense_details' => 'required|array',


            'Bill_expense_details.*.id' => 'nullable|integer',
            'Bill_expense_details.*.expense_product_id' => 'required|integer|exists:expense_products,id',
            'Bill_expense_details.*.quantity' => 'required|integer',
            'Bill_expense_details.*.unit_price' => 'required|numeric',
            'Bill_expense_details.*.total' => 'required|numeric',


        ];
    }
}


// namespace App\Http\Requests;

// use Illuminate\Foundation\Http\FormRequest;

// class BillExpensesRequest extends FormRequest
// {
//     /**
//      * Determine if the user is authorized to make this request.
//      */
//     public function authorize(): bool
//     {
//         return true;
//     }

//     /**
//      * Get the validation rules that apply to the request.
//      *
//      * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
//      */
//     public function rules(): array
//     {
//         $rules = [
//             'date' => 'required|date',
//             'expense_category_id' => 'required|numeric',
//             'supplier_id' => 'required',
//             'bill_number' => 'required|numeric',

//             'Bill_expense_details.*.quantity' => 'required|integer',
//             'Bill_expense_details.*.unit_price' => 'required|numeric',
//             'Bill_expense_details.*.total' => 'required|numeric',

//             'amount' => 'required|numeric',
//             'paid' => 'required|numeric',
//             'description' => 'nullable|string',
//         ];

//         // Only require the 'id' field during updates
//         if ($this->isMethod('put') || $this->isMethod('patch')) {
//             $rules['Bill_expense_details.*.id'] = 'required|integer|exists:bill_expense_details,id';
//         } else {
//             $rules['Bill_expense_details.*.id'] = 'nullable|integer';
//         }

//         return $rules;
//     }
// }

