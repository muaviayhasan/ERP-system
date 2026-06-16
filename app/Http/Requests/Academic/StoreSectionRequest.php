<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class StoreSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:sections,code'],
            'section_type' => ['nullable', 'string', 'max:255'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'institution_type' => ['nullable', 'string', 'max:255'],
            'max_capacity' => ['nullable', 'integer'],
            'current_enrollment' => ['nullable', 'integer'],
            'enable_waitlist' => ['nullable', 'boolean'],
            'class_teacher_id' => ['nullable', 'integer', 'exists:teachers,id'],
            'is_active' => ['nullable', 'boolean'],
            'allow_admissions' => ['nullable', 'boolean'],
            'lock_structure' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }
}
