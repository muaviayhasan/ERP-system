<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'group' => $this->group,
            'key' => $this->key,
            'value' => $this->maskIfSensitive($this->key, $this->value),
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Mask values for setting keys that hold secrets (passwords, API keys,
     * tokens) so they are never returned in plaintext over the API.
     */
    private function maskIfSensitive(string $key, mixed $value): mixed
    {
        $needles = ['password', 'secret', 'token', 'api_key', 'apikey', 'private', 'credential'];
        $lowerKey = strtolower($key);

        foreach ($needles as $needle) {
            if (str_contains($lowerKey, $needle) && filled($value)) {
                return '********';
            }
        }

        return $value;
    }
}
