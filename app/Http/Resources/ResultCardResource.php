<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResultCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'exam_id' => $this->exam_id,
            'academic_year_id' => $this->academic_year_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'campus_id' => $this->campus_id,
            'verification_code' => $this->verification_code,
            'cumulative_gpa' => $this->cumulative_gpa,
            'overall_grade' => $this->overall_grade,
            'rank_in_class' => $this->rank_in_class,
            'class_size' => $this->class_size,
            'result_status' => $this->result_status,
            'is_published' => $this->is_published,
            'is_locked' => $this->is_locked,
            'allow_reevaluation' => $this->allow_reevaluation,
            'attendance_percent' => $this->attendance_percent,
            'fee_status' => $this->fee_status,
            'class_teacher_id' => $this->class_teacher_id,
            'registrar_id' => $this->registrar_id,
            'generated_at' => $this->generated_at,
            'student' => $this->whenLoaded('student'),
            'exam' => $this->whenLoaded('exam'),
            'academic_year' => $this->whenLoaded('academicYear'),
            'school_class' => $this->whenLoaded('schoolClass'),
            'section' => $this->whenLoaded('section'),
            'campus' => $this->whenLoaded('campus'),
            'class_teacher' => $this->whenLoaded('classTeacher'),
            'registrar' => $this->whenLoaded('registrar'),
            'result_card_lines' => $this->whenLoaded('resultCardLines'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
