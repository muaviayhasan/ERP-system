<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:departments,code'],
            'description' => ['nullable', 'string'],
            'institution_type' => ['nullable', 'string', 'max:255'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'hod_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'semester_system' => ['nullable', 'boolean'],
            'credit_hour_system' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'allow_admissions' => ['nullable', 'boolean'],
        ];
    }
}
