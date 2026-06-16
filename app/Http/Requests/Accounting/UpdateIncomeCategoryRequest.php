<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIncomeCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('incomeCategory')?->id ?? $this->route('incomeCategory');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('income_categories', 'name')->ignore($id)],
            'slug' => ['nullable', 'string', 'max:255'],
            'module_link' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
