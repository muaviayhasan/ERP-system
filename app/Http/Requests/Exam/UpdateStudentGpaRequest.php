<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentGpaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'semester_id' => ['nullable', 'integer', 'exists:semesters,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'credits' => ['nullable', 'integer'],
            'gpa' => ['nullable', 'numeric'],
            'cgpa' => ['nullable', 'numeric'],
            'performance_status' => ['nullable', 'string', 'max:255'],
            'academic_standing' => ['nullable', 'string', 'max:255'],
            'last_calculated_at' => ['nullable', 'date'],
        ];
    }
}
