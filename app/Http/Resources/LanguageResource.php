<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LanguageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'is_enabled' => $this->is_enabled,
            'is_default' => $this->is_default,
            'is_rtl' => $this->is_rtl,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
