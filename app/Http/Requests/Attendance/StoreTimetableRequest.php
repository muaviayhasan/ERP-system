<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class StoreTimetableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'semester_id' => ['nullable', 'integer', 'exists:semesters,id'],
            'institute_type' => ['nullable', 'string', 'max:255'],
            'week_start_date' => ['nullable', 'date'],
            'week_end_date' => ['nullable', 'date'],
        ];
    }
}
