<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalePaymentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'amount' => $this->amount,
            'currency' =>[
                'id' => $this->currency->id,
                'name' => $this->currency->name,
                'code' => $this->currency->code,
            ],
            'description' => $this->description,
        ];


    }


}
