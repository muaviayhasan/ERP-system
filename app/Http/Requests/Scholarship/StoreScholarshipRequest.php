<?php

namespace App\Http\Requests\Scholarship;

use Illuminate\Foundation\Http\FormRequest;

class StoreScholarshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:scholarships,code'],
            'type' => ['required', 'in:merit,need,sports,institutional'],
            'value_type' => ['required', 'in:percentage,fixed_amount'],
            'value' => ['required', 'numeric'],
            'level' => ['nullable', 'string', 'max:255'],
            'criteria' => ['nullable', 'string'],
            'estimated_liability' => ['nullable', 'numeric'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }
}
