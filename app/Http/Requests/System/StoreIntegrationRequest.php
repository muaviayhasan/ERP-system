<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class StoreIntegrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'provider' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'is_enabled' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', 'max:255'],
            'credentials' => ['nullable', 'array'],
        ];
    }
}
