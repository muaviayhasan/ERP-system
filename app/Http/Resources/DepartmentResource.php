<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'institution_type' => $this->institution_type,
            'campus_id' => $this->campus_id,
            'hod_user_id' => $this->hod_user_id,
            'semester_system' => $this->semester_system,
            'credit_hour_system' => $this->credit_hour_system,
            'is_active' => $this->is_active,
            'allow_admissions' => $this->allow_admissions,
            'campus' => $this->whenLoaded('campus'),
            'hod_user' => $this->whenLoaded('hodUser'),
            'programs' => $this->whenLoaded('programs'),
            'courses' => $this->whenLoaded('courses'),
            'subjects' => $this->whenLoaded('subjects'),
            'semesters' => $this->whenLoaded('semesters'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
