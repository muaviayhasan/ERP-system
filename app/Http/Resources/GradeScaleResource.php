<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeScaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'grade' => $this->grade,
            'min_percent' => $this->min_percent,
            'max_percent' => $this->max_percent,
            'min_gpa' => $this->min_gpa,
            'max_gpa' => $this->max_gpa,
            'gpa_point' => $this->gpa_point,
            'is_passing' => $this->is_passing,
            'program_id' => $this->program_id,
            'program' => $this->whenLoaded('program'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
