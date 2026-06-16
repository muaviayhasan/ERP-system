<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScholarshipResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'type' => $this->type,
            'value_type' => $this->value_type,
            'value' => $this->value,
            'level' => $this->level,
            'criteria' => $this->criteria,
            'estimated_liability' => $this->estimated_liability,
            'status' => $this->status,
            'assignments' => ScholarshipAssignmentResource::collection($this->whenLoaded('assignments')),
            'applications' => ScholarshipApplicationResource::collection($this->whenLoaded('applications')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
