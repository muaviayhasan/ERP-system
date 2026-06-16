<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'type' => $this->type,
            'description' => $this->description,
            'campus_id' => $this->campus_id,
            'program_id' => $this->program_id,
            'department_id' => $this->department_id,
            'semester_id' => $this->semester_id,
            'credit_hours' => $this->credit_hours,
            'total_marks' => $this->total_marks,
            'passing_percentage' => $this->passing_percentage,
            'weight_quiz' => $this->weight_quiz,
            'weight_assignment' => $this->weight_assignment,
            'weight_mid' => $this->weight_mid,
            'weight_final' => $this->weight_final,
            'primary_instructor_id' => $this->primary_instructor_id,
            'is_active' => $this->is_active,
            'open_enrollment' => $this->open_enrollment,
            'status' => $this->status,
            'campus' => $this->whenLoaded('campus'),
            'program' => $this->whenLoaded('program'),
            'department' => $this->whenLoaded('department'),
            'semester' => $this->whenLoaded('semester'),
            'primary_instructor' => $this->whenLoaded('primaryInstructor'),
            'semesters' => $this->whenLoaded('semesters'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
