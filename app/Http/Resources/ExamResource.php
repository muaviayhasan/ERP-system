<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'exam_type' => $this->exam_type,
            'scope_label' => $this->scope_label,
            'academic_year_id' => $this->academic_year_id,
            'program_id' => $this->program_id,
            'department_id' => $this->department_id,
            'semester_id' => $this->semester_id,
            'campus_id' => $this->campus_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'total_marks' => $this->total_marks,
            'passing_marks' => $this->passing_marks,
            'is_online' => $this->is_online,
            'multi_set_papers' => $this->multi_set_papers,
            'status' => $this->status,
            'result_status' => $this->result_status,
            'subjects_count' => $this->subjects_count,
            'students_count' => $this->students_count,
            'created_by' => $this->created_by,
            'academic_year' => $this->whenLoaded('academicYear'),
            'program' => $this->whenLoaded('program'),
            'department' => $this->whenLoaded('department'),
            'semester' => $this->whenLoaded('semester'),
            'campus' => $this->whenLoaded('campus'),
            'created_by_user' => $this->whenLoaded('createdBy'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
