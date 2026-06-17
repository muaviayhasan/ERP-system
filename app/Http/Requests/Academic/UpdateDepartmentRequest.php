<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('department')?->id ?? $this->route('department');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'code' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('departments', 'code')->ignore($id)],
            'description' => ['nullable', 'string'],
            'institution_type' => ['nullable', 'string', 'max:255'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'campuses' => ['nullable', 'array'],
            'campuses.*' => ['integer', 'exists:campuses,id'],
            'hod_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'semester_system' => ['nullable', 'boolean'],
            'credit_hour_system' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'allow_admissions' => ['nullable', 'boolean'],
        ];
    }
}
