<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIntegrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'provider' => ['sometimes', 'required', 'string', 'max:255'],
            'type' => ['sometimes', 'required', 'string', 'max:255'],
            'is_enabled' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', 'max:255'],
            'credentials' => ['nullable', 'array'],
        ];
    }
}
