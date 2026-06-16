<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class StoreSemesterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:semesters,code'],
            'description' => ['nullable', 'string'],
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'total_credit_hours' => ['nullable', 'integer'],
            'generate_fee_plan' => ['nullable', 'boolean'],
            'late_fee_rule' => ['nullable', 'string', 'max:255'],
            'grading_system' => ['nullable', 'string', 'max:255'],
            'is_locked' => ['nullable', 'boolean'],
            'fee_cycle_generated' => ['nullable', 'boolean'],
            'exam_cycle_generated' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }
}
