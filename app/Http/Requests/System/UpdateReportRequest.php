<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'category' => ['sometimes', 'required', 'string', 'max:255'],
            'format' => ['nullable', 'string', 'max:255'],
            'parameters' => ['nullable', 'array'],
            'generated_by' => ['nullable', 'integer', 'exists:users,id'],
            'generated_at' => ['nullable', 'date'],
        ];
    }
}
