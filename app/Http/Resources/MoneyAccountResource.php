<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MoneyAccountResource extends JsonResource
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
            'name' => $this->name,
            'amount_with_currency' => $this->amount . ' ' .$this->currency?->code,
            'amount' => $this->amount,
            'symbol' => $this->currency->symbol,
            'currency_id' => $this->currency->id,
        ];
    }
}
