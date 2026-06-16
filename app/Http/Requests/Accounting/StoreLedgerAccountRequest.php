<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class StoreLedgerAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'in:asset,liability,income,expense'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
