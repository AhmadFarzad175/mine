<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $role = $this->roles->first();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'role' => $role ? [
                'id' => $role->id,
                "name" =>  $role->name,
                "description" =>  $role->description,
            ] : null,            'phone' => $this->phone,
            'email' => $this->email,
            'status' => (bool) $this->status,
            'image' => $this->image ? url('storage/' . $this->image) : null, // Image URL if available
        ];
    }
}
