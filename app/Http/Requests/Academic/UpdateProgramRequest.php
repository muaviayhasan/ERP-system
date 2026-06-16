<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('program')?->id ?? $this->route('program');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'code' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('programs', 'code')->ignore($id)],
            'degree_level' => ['nullable', 'string', 'max:255'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
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
