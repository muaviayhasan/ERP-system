<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:255', 'unique:exams,code'],
            'exam_type' => ['required', 'in:Final,Midterm,Quiz,Practical,Supplementary,Annual'],
            'scope_label' => ['nullable', 'string', 'max:255'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'semester_id' => ['nullable', 'integer', 'exists:semesters,id'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'start_time' => ['nullable', 'date_format:H:i:s'],
            'end_time' => ['nullable', 'date_format:H:i:s'],
            'total_marks' => ['nullable', 'integer'],
            'passing_marks' => ['nullable', 'integer'],
            'is_online' => ['nullable', 'boolean'],
            'multi_set_papers' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', 'max:255'],
            'result_status' => ['nullable', 'string', 'max:255'],
            'subjects_count' => ['nullable', 'integer'],
            'students_count' => ['nullable', 'integer'],
            'created_by' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
