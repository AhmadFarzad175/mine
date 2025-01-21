<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StakeholderResource extends JsonResource
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
            'type' => $this->type,
            'image' => $this->image ? url('storage/' . $this->image) : null, // Image URL if available
            'phone' => $this->phone,
            'email' => $this->email,
            'city' => $this->city,
            'country' => $this->country,
            'companyName' => $this->company_name,
            'kataNumber' => $this->kata_number,
            'address' => $this->address,
        ];    }
}
