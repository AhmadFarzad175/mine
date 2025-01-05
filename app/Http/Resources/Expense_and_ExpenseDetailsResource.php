<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Expense_and_ExpenseDetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'category' => [
                "id" => $this->expenseCategory?->id,
                "name" => $this->expenseCategory?->name,
            ],
            'supplier' => [
                "id" => $this->supplier?->id,
                "name" => $this->supplier?->name,
                "phone" => $this->supplier?->phone,
                "address" => $this->supplier?->address,
            ],
            'bill_number' => $this->bill_number,
            'ExpenseDetails' => ExpenseDetailResource::collection($this->billExpenseDetails),
            'amount' => $this->amount,
            'currency' => [
                'id' => $this->currency->id,
                'name' => $this->currency->name,
                'code' => $this->currency->code,
                'saymbol' => $this->currency->Symbol,
                'rate' => $this->currency->rate,
            ],
            'paid' => $this->paid,

            'description' => $this->description,

        ];
    }
}
