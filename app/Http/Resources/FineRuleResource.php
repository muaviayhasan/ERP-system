<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FineRuleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'level' => $this->level,
            'calculation_method' => $this->calculation_method,
            'amount' => $this->amount,
            'grace_period_days' => $this->grace_period_days,
            'enable_max_cap' => $this->enable_max_cap,
            'max_cap_amount' => $this->max_cap_amount,
            'status' => $this->status,
            'fines' => FineResource::collection($this->whenLoaded('fines')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
