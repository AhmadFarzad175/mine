<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'last_name' => $this->last_name,
            'image' => $this->image ? url('storage/' . $this->image) : null, // Image URL if available
            'phone' => $this->phone, // Assuming there's a phone field
            'address' => $this->address,
        ];
    }
}
