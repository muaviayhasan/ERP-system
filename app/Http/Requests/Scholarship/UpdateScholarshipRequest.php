<?php

namespace App\Http\Requests\Scholarship;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateScholarshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('scholarship')?->id ?? $this->route('scholarship');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'code' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('scholarships', 'code')->ignore($id)],
            'type' => ['sometimes', 'required', 'in:merit,need,sports,institutional'],
            'value_type' => ['sometimes', 'required', 'in:percentage,fixed_amount'],
            'value' => ['sometimes', 'required', 'numeric'],
            'level' => ['nullable', 'string', 'max:255'],
            'criteria' => ['nullable', 'string'],
            'estimated_liability' => ['nullable', 'numeric'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }
}
