<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StakeholderStatementResource extends JsonResource
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
            'type' => $this->type,
            'date' => $this->date,
            'amountWithCurrency' => number_format($this->amount,2). ' ' .   $this->currency->code,
            'amount' => number_format($this->amount,2),
            'balance' => number_format($this->balance,2),
            'recordId' => $this->record_id,
            'rate' => $this->rate,
            'ref' => $this->ref,
            'payOrReceive' => $this->pay_or_receive,
            'description' => $this->description,
            'stakeholder' => [
                'id' => $this->stakeholder->id,
                'name' => $this->stakeholder->name,
            ],
            'stakeholderAccount' => [
                'id' => $this->stakeholderAccount->id,
                'name' => $this->stakeholderAccount->name,
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
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
