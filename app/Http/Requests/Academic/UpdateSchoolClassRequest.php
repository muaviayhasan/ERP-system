<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSchoolClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('class')?->id ?? $this->route('class');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'code' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('classes', 'code')->ignore($id)],
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
