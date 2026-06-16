<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IntegrationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'provider' => $this->provider,
            'type' => $this->type,
            'is_enabled' => $this->is_enabled,
            'status' => $this->status,
            'credentials' => $this->credentials,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
