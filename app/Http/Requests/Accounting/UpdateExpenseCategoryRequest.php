<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExpenseCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('expenseCategory')?->id ?? $this->route('expenseCategory');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('expense_categories', 'name')->ignore($id)],
            'slug' => ['nullable', 'string', 'max:255'],
            'budget_amount' => ['nullable', 'numeric', 'decimal:0,2'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
