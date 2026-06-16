<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('course')?->id ?? $this->route('course');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'code' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('courses', 'code')->ignore($id)],
            'type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'semester_id' => ['nullable', 'integer', 'exists:semesters,id'],
            'credit_hours' => ['nullable', 'integer'],
            'total_marks' => ['nullable', 'integer'],
            'passing_percentage' => ['nullable', 'integer'],
            'weight_quiz' => ['nullable', 'integer'],
            'weight_assignment' => ['nullable', 'integer'],
            'weight_mid' => ['nullable', 'integer'],
            'weight_final' => ['nullable', 'integer'],
            'primary_instructor_id' => ['nullable', 'integer', 'exists:teachers,id'],
            'is_active' => ['nullable', 'boolean'],
            'open_enrollment' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }
}
