<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'subject_id' => ['nullable', 'integer', 'exists:subjects,id'],
            'teacher_id' => ['nullable', 'integer', 'exists:teachers,id'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'date' => ['sometimes', 'required', 'date'],
            'session' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:present,absent,late,leave'],
            'lecture_no' => ['nullable', 'string', 'max:255'],
            'room' => ['nullable', 'string', 'max:255'],
            'start_time' => ['nullable', 'date_format:H:i:s'],
            'end_time' => ['nullable', 'date_format:H:i:s'],
            'remarks' => ['nullable', 'string', 'max:255'],
            'marked_by' => ['nullable', 'integer', 'exists:users,id'],
            'marked_method' => ['nullable', 'string', 'max:255'],
            'marked_at' => ['nullable', 'date'],
        ];
    }
}
