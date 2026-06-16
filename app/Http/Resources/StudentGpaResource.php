<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentGpaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'program_id' => $this->program_id,
            'department_id' => $this->department_id,
            'semester_id' => $this->semester_id,
            'academic_year_id' => $this->academic_year_id,
            'credits' => $this->credits,
            'gpa' => $this->gpa,
            'cgpa' => $this->cgpa,
            'performance_status' => $this->performance_status,
            'academic_standing' => $this->academic_standing,
            'last_calculated_at' => $this->last_calculated_at,
            'student' => $this->whenLoaded('student'),
            'program' => $this->whenLoaded('program'),
            'department' => $this->whenLoaded('department'),
            'semester' => $this->whenLoaded('semester'),
            'academic_year' => $this->whenLoaded('academicYear'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
