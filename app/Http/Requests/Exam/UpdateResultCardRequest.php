<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateResultCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('result_card')?->id ?? $this->route('result_card');

        return [
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
            'exam_id' => ['nullable', 'integer', 'exists:exams,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'verification_code' => ['nullable', 'string', 'max:255', Rule::unique('result_cards', 'verification_code')->ignore($id)],
            'cumulative_gpa' => ['nullable', 'numeric'],
            'overall_grade' => ['nullable', 'string', 'max:255'],
            'rank_in_class' => ['nullable', 'integer'],
            'class_size' => ['nullable', 'integer'],
            'result_status' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
            'is_locked' => ['nullable', 'boolean'],
            'allow_reevaluation' => ['nullable', 'boolean'],
            'attendance_percent' => ['nullable', 'numeric'],
            'fee_status' => ['nullable', 'string', 'max:255'],
            'class_teacher_id' => ['nullable', 'integer', 'exists:teachers,id'],
            'registrar_id' => ['nullable', 'integer', 'exists:users,id'],
            'generated_at' => ['nullable', 'date'],
        ];
    }
}
