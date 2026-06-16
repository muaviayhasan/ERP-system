<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuardianResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'relationship' => $this->relationship,
            'cnic' => $this->cnic,
            'phone' => $this->phone,
            'email' => $this->email,
            'residential_address' => $this->residential_address,
            'photo_url' => $this->photo_url,
            'is_primary_fee_payer' => (bool) $this->is_primary_fee_payer,
            'is_emergency_authorized' => (bool) $this->is_emergency_authorized,
            'phone_verified' => (bool) $this->phone_verified,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'students' => StudentResource::collection($this->whenLoaded('students')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
