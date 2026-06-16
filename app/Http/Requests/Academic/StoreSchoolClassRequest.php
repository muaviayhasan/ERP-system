<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:classes,code'],
            'description' => ['nullable', 'string'],
            'institution_type' => ['nullable', 'string', 'max:255'],
            'academic_level' => ['nullable', 'string', 'max:255'],
            'board' => ['nullable', 'string', 'max:255'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'semester_id' => ['nullable', 'integer', 'exists:semesters,id'],
            'coordinator_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'batch_count' => ['nullable', 'integer'],
            'total_credit_hours' => ['nullable', 'integer'],
            'multi_campus_sharing' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'allow_admissions' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }
}
