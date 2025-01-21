<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StakeholderAccountResource extends JsonResource
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
            'date' => $this->date,
            'amount' => $this->amount,
            'payOrReceive' => $this->pay_or_receive,
            'stakeholder' => [
                'id' => $this->stakeholder->id,
                'name' => $this->stakeholder->name,
            ],
            'currency' => [
                'id' => $this->currency->id,
                'name' => $this->currency->name,
                'symbol' => $this->currency->symbol,
                'code' => $this->currency->code,
            ],
        ];
    }

}
