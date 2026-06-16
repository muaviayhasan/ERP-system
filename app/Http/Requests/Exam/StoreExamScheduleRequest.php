<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'exam_id' => ['nullable', 'integer', 'exists:exams,id'],
            'subject_id' => ['nullable', 'integer', 'exists:subjects,id'],
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'class_label' => ['nullable', 'string', 'max:255'],
            'exam_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i:s'],
            'end_time' => ['required', 'date_format:H:i:s'],
            'duration_hours' => ['nullable', 'numeric'],
            'venue' => ['nullable', 'string', 'max:255'],
            'invigilator_id' => ['nullable', 'integer', 'exists:teachers,id'],
            'exam_type' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:255'],
            'has_conflict' => ['nullable', 'boolean'],
            'conflict_severity' => ['nullable', 'string', 'max:255'],
            'conflict_note' => ['nullable', 'string', 'max:255'],
        ];
    }
}
