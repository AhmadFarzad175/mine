<?php

namespace App\Http\Resources;

use App\Models\Currency;
use App\Models\Customer;
use App\Models\Customers;
use Illuminate\Http\Request;

use Illuminate\Http\Resources\Json\JsonResource;

class Sales_and_SalesDetailsResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'date' => $this->date,
            'customer_id' => $this->customer_id,
            'currency' =>Currency::find($this->currency_id),
            'currency_id' => $this->currency_id,
            'people' => Customer::find($this->customer_id),
            'SaleDetails' => saleDetailResource::collection($this->salesDetails),
            'discount' => $this->discount,
            'total_amount' => $this->total_amount,
            'description' => $this->description,
        ];
    }
}
