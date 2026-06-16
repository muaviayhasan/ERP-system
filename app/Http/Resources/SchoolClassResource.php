<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolClassResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'institution_type' => $this->institution_type,
            'academic_level' => $this->academic_level,
            'board' => $this->board,
            'campus_id' => $this->campus_id,
            'semester_id' => $this->semester_id,
            'coordinator_user_id' => $this->coordinator_user_id,
            'batch_count' => $this->batch_count,
            'total_credit_hours' => $this->total_credit_hours,
            'multi_campus_sharing' => $this->multi_campus_sharing,
            'is_active' => $this->is_active,
            'allow_admissions' => $this->allow_admissions,
            'status' => $this->status,
            'campus' => $this->whenLoaded('campus'),
            'semester' => $this->whenLoaded('semester'),
            'coordinator_user' => $this->whenLoaded('coordinatorUser'),
            'sections' => $this->whenLoaded('sections'),
            'batches' => $this->whenLoaded('batches'),
            'subjects' => $this->whenLoaded('subjects'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
