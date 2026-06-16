<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('batch')?->id ?? $this->route('batch');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'code' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('batches', 'code')->ignore($id)],
            'batch_type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'max:255'],
            'institution_type' => ['nullable', 'string', 'max:255'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'semester_id' => ['nullable', 'integer', 'exists:semesters,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'weekly_days' => ['nullable', 'array'],
            'max_students' => ['nullable', 'integer'],
            'allow_waitlist' => ['nullable', 'boolean'],
            'primary_instructor_id' => ['nullable', 'integer', 'exists:teachers,id'],
            'fee_plan_id' => ['nullable', 'integer', 'exists:fee_plans,id'],
            'attendance_tracking' => ['nullable', 'string', 'max:255'],
            'installments_allowed' => ['nullable', 'boolean'],
            'open_for_admissions' => ['nullable', 'boolean'],
        ];
    }
}
