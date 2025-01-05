<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillExpenseResource extends JsonResource
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
            'ref' => $this->ref,
            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
            ],
            'supplier' => [
                'id' => $this->supplier?->id,
                'name' => $this->supplier?->name,
            ],
            'amount' => $this->amount,
            'amount_with_currency' => $this->amount . ' ' . $this->currency?->code,
            'paid' => $this->paid,
            'paid_with_currency' => $this->paid . ' ' . $this->currency?->code,
            'Due' => $this->Due,
            'Due_with_currency' => $this->Due . ' ' . $this->currency?->code,
            'currency' => $this->currency
        ];
    }
}
