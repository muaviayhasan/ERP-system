<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'section_type' => $this->section_type,
            'class_id' => $this->class_id,
            'campus_id' => $this->campus_id,
            'institution_type' => $this->institution_type,
            'max_capacity' => $this->max_capacity,
            'current_enrollment' => $this->current_enrollment,
            'enable_waitlist' => $this->enable_waitlist,
            'class_teacher_id' => $this->class_teacher_id,
            'is_active' => $this->is_active,
            'allow_admissions' => $this->allow_admissions,
            'lock_structure' => $this->lock_structure,
            'status' => $this->status,
            'school_class' => $this->whenLoaded('schoolClass'),
            'campus' => $this->whenLoaded('campus'),
            'class_teacher' => $this->whenLoaded('classTeacher'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
