<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'classification' => $this->classification,
            'institution_type' => $this->institution_type,
            'department_id' => $this->department_id,
            'class_id' => $this->class_id,
            'semester_id' => $this->semester_id,
            'credits' => $this->credits,
            'total_marks' => $this->total_marks,
            'weight_mid' => $this->weight_mid,
            'weight_final' => $this->weight_final,
            'primary_teacher_id' => $this->primary_teacher_id,
            'curriculum_focus' => $this->curriculum_focus,
            'prerequisites_required' => $this->prerequisites_required,
            'lock_structural_changes' => $this->lock_structural_changes,
            'status' => $this->status,
            'department' => $this->whenLoaded('department'),
            'school_class' => $this->whenLoaded('schoolClass'),
            'semester' => $this->whenLoaded('semester'),
            'primary_teacher' => $this->whenLoaded('primaryTeacher'),
            'classes' => $this->whenLoaded('classes'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
