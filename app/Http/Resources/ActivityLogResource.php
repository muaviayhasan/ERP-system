<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'audit_ref' => $this->audit_ref,
            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
            'role' => $this->role,
            'module' => $this->module,
            'action' => $this->action,
            'description' => $this->description,
            'changes' => $this->changes,
            'ip_address' => $this->ip_address,
            'device' => $this->device,
            'protocol' => $this->protocol,
            'origin_id' => $this->origin_id,
            'mfa_status' => $this->mfa_status,
            'geo_lat' => $this->geo_lat,
            'geo_lng' => $this->geo_lng,
            'status' => $this->status,
            'user' => $this->whenLoaded('user'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
