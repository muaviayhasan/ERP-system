<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'teacher_id' => ['nullable', 'integer', 'exists:teachers,id'],
            'institute_type' => ['nullable', 'string', 'max:50'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'subject_id' => ['nullable', 'integer', 'exists:subjects,id'],
            'course_id' => ['nullable', 'integer', 'exists:courses,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'semester_id' => ['nullable', 'integer', 'exists:semesters,id'],
            'credits' => ['nullable', 'string', 'max:255'],
            'lecture_hours' => ['nullable', 'numeric'],
            'lab_hours' => ['nullable', 'numeric'],
            'weekly_hours' => ['nullable', 'numeric'],
            'max_weekly_hours' => ['nullable', 'numeric'],
            'timetable_status' => ['nullable', 'in:pending,scheduled,published'],
            'has_conflict' => ['nullable', 'boolean'],
            'conflict_note' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:active,inactive'],
        ];
    }
}
