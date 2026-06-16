<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademicSettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'academic_year_id' => $this->academic_year_id,
            'grading_system' => $this->grading_system,
            'pass_mark_threshold' => $this->pass_mark_threshold,
            'min_attendance_required' => $this->min_attendance_required,
            'attendance_grace_minutes' => $this->attendance_grace_minutes,
            'attendance_session_limit' => $this->attendance_session_limit,
            'attendance_warning_threshold' => $this->attendance_warning_threshold,
            'attendance_critical_threshold' => $this->attendance_critical_threshold,
            'exam_structure' => $this->exam_structure,
            'weight_final' => $this->weight_final,
            'weight_midterm' => $this->weight_midterm,
            'weight_assignments_lab' => $this->weight_assignments_lab,
            'weight_quizzes' => $this->weight_quizzes,
            'approval_workflow' => $this->approval_workflow,
            'promotion_enabled' => $this->promotion_enabled,
            'promotion_min_gpa' => $this->promotion_min_gpa,
            'promotion_max_fail_subjects' => $this->promotion_max_fail_subjects,
            'university_mode_enabled' => $this->university_mode_enabled,
            'min_credit_load' => $this->min_credit_load,
            'max_credit_load' => $this->max_credit_load,
            'year_start_month' => $this->year_start_month,
            'makeup_class_allowance' => $this->makeup_class_allowance,
            'academic_year' => $this->whenLoaded('academicYear'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
