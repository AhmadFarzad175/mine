<?php

namespace App\Http\Resources;

use App\Http\Requests\SalesRequest;
use App\Models\SaleDetails;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleDetailResource extends JsonResource
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
            'sales_id' => $this->sales_id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'total' => $this->quantity * $this->unit_price,
            'sale_products_id' => $this->SaleProducts->id,
            'product' => new saleProductResource($this->saleProducts),
        ];
    }
}




