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
            // Never expose stored secrets. Report which credential keys are
            // configured, with their values masked.
            'credentials' => $this->maskCredentials(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * @return array<string, string>
     */
    private function maskCredentials(): array
    {
        $masked = [];
        foreach ((array) $this->credentials as $key => $value) {
            $masked[$key] = '********';
        }

        return $masked;
    }
}
