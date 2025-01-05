<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'currency_id' => $this->currency_id,
            
            // 'ref#' => $this->ref,
            'user_name' => $this->user?->name,
            'amount' => $this->amount,
            'amount_with_currency' => $this->amount . ' ' .  $this->currency?->code,
            'category' => [
                "id" => $this->expenseCategory?->id,
                "name" => $this->expenseCategory?->name,
            ],

        ];
    }
}
