<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('subject')?->id ?? $this->route('subject');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'code' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('subjects', 'code')->ignore($id)],
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
