<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'degree_level' => $this->degree_level,
            'department_id' => $this->department_id,
            'faculty' => $this->faculty,
            'multi_department_access' => $this->multi_department_access,
            'total_years' => $this->total_years,
            'total_semesters' => $this->total_semesters,
            'total_credits' => $this->total_credits,
            'coordinator_user_id' => $this->coordinator_user_id,
            'allow_admissions' => $this->allow_admissions,
            'lock_structure' => $this->lock_structure,
            'catalog_banner_path' => $this->catalog_banner_path,
            'status' => $this->status,
            'department' => $this->whenLoaded('department'),
            'coordinator_user' => $this->whenLoaded('coordinatorUser'),
            'courses' => $this->whenLoaded('courses'),
            'batches' => $this->whenLoaded('batches'),
            'semesters' => $this->whenLoaded('semesters'),
            'campuses' => $this->whenLoaded('campuses'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
