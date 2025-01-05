<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanPaymentReceivedResource extends JsonResource
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
            'payment_date'=>$this->payment_date,
            'loan_people_id' => $this->loan_people_id,
            'loan_people' => $this->loanPeople->name,
            'amount' => $this->amount,
            'amount_with_currency' => $this->amount . ' ' . $this->currency?->code,
            'notes' => $this->notes,
            'currency' => $this->currency,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
