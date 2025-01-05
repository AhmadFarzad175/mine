<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bill_expenses_id' => $this->bill_expenses_id,
            'product_name' => $this->expenseproduct->name,
            'expense_product_id' => $this->expenseProduct->id,
            'unit' => $this->expenseproduct->unit,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'total' => $this->total,

        ];
    }
}
