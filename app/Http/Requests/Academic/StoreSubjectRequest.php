<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:subjects,code'],
            'classification' => ['nullable', 'string', 'max:255'],
            'institution_type' => ['nullable', 'string', 'max:255'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'semester_id' => ['nullable', 'integer', 'exists:semesters,id'],
            'credits' => ['nullable', 'numeric'],
            'total_marks' => ['nullable', 'integer'],
            'weight_mid' => ['nullable', 'integer'],
            'weight_final' => ['nullable', 'integer'],
            'primary_teacher_id' => ['nullable', 'integer', 'exists:teachers,id'],
            'curriculum_focus' => ['nullable', 'string'],
            'prerequisites_required' => ['nullable', 'boolean'],
            'lock_structural_changes' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }
}
