<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
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
            'ref#' => $this->ref,
            'name' =>$this->user?->name,
            'customer_name' =>$this->customer?->name,
            'total_amount' => $this->total_amount,
            'paid' => $this->paid ?? 0,
            'Due' => $this->total_amount - $this->paid,
            // "salesDetails" =>  SaleDetailResource::collection($this->salesDetails),
            'total_amount_with_code' => $this->total_amount . ' ' .$this->currency?->code,
            'paid_with_code' => $this->paid . ' ' .$this->currency?->code,
            'due_with_code' => ($this->total_amount - $this->paid) . ' ' .$this->currency?->code,
            'currency' => $this->currency

        ];
    }
}
