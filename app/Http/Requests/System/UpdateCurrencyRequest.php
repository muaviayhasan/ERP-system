<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCurrencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('currency')?->id ?? $this->route('currency');

        return [
            'code' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('currencies', 'code')->ignore($id)],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'symbol' => ['sometimes', 'required', 'string', 'max:255'],
            'is_base' => ['nullable', 'boolean'],
        ];
    }
}
