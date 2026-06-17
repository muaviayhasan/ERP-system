<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:programs,code'],
            'degree_level' => ['nullable', 'string', 'max:255'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'campuses' => ['nullable', 'array'],
            'campuses.*' => ['integer', 'exists:campuses,id'],
            'faculty' => ['nullable', 'string', 'max:255'],
            'multi_department_access' => ['nullable', 'boolean'],
            'total_years' => ['nullable', 'numeric'],
            'total_semesters' => ['nullable', 'integer'],
            'total_credits' => ['nullable', 'integer'],
            'coordinator_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'allow_admissions' => ['nullable', 'boolean'],
            'lock_structure' => ['nullable', 'boolean'],
            'catalog_banner_path' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }
}
