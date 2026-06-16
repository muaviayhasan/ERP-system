<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGradeScaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'grade' => ['sometimes', 'required', 'string', 'max:255'],
            'min_percent' => ['nullable', 'numeric'],
            'max_percent' => ['nullable', 'numeric'],
            'min_gpa' => ['nullable', 'numeric'],
            'max_gpa' => ['nullable', 'numeric'],
            'gpa_point' => ['nullable', 'numeric'],
            'is_passing' => ['nullable', 'boolean'],
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
        ];
    }
}
