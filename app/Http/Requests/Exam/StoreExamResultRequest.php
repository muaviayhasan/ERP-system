<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'exam_id' => ['nullable', 'integer', 'exists:exams,id'],
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
            'subject_id' => ['nullable', 'integer', 'exists:subjects,id'],
            'evaluator_id' => ['nullable', 'integer', 'exists:teachers,id'],
            'attendance_status' => ['nullable', 'string', 'max:255'],
            'marks_obtained' => ['nullable', 'numeric'],
            'total_marks' => ['nullable', 'integer'],
            'percentage' => ['nullable', 'numeric'],
            'grade' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string', 'max:255'],
            'is_flagged' => ['nullable', 'boolean'],
            'validation_error' => ['nullable', 'string', 'max:255'],
            'entry_status' => ['nullable', 'string', 'max:255'],
        ];
    }
}
