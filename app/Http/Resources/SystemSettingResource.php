<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SystemSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
{

    if (is_null($this)) {
        return [];
    }
    return [
        'id' => $this->id ?? '', // Return the ID or an empty string if not set
        'company_name' => $this->company_name ?? '',
        'phone' => $this->phone ?? '',
        'email' => $this->email ?? '',
        'address' => $this->address ?? '',
        'image' => $this->image ? asset('storage/' . $this->image) : null // Generate the URL for the image or return null
    ];
}

}
