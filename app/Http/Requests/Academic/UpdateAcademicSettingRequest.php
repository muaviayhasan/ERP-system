<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAcademicSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'grading_system' => ['nullable', 'string', 'max:255'],
            'pass_mark_threshold' => ['nullable', 'integer'],
            'min_attendance_required' => ['nullable', 'integer'],
            'attendance_grace_minutes' => ['nullable', 'integer'],
            'attendance_session_limit' => ['nullable', 'string', 'max:255'],
            'attendance_warning_threshold' => ['nullable', 'integer'],
            'attendance_critical_threshold' => ['nullable', 'integer'],
            'exam_structure' => ['nullable', 'string', 'max:255'],
            'weight_final' => ['nullable', 'integer'],
            'weight_midterm' => ['nullable', 'integer'],
            'weight_assignments_lab' => ['nullable', 'integer'],
            'weight_quizzes' => ['nullable', 'integer'],
            'approval_workflow' => ['nullable', 'array'],
            'promotion_enabled' => ['nullable', 'boolean'],
            'promotion_min_gpa' => ['nullable', 'numeric'],
            'promotion_max_fail_subjects' => ['nullable', 'integer'],
            'university_mode_enabled' => ['nullable', 'boolean'],
            'min_credit_load' => ['nullable', 'integer'],
            'max_credit_load' => ['nullable', 'integer'],
            'year_start_month' => ['nullable', 'string', 'max:255'],
            'makeup_class_allowance' => ['nullable', 'string', 'max:255'],
        ];
    }
}
