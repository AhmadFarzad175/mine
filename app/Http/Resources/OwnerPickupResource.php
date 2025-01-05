<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerPickupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>$this->id,
            'date' => $this->date,
            'owner' => $this->owner?->name,
            'amount' => $this->amount,
            'amount_with_code' => $this->amount . ' ' .$this->currency?->code,
            'currency_id' => $this->currency_id,
            'description' => $this->description,
        ];
    }
}
