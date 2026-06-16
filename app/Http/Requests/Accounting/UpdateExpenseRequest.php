<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('expense')?->id ?? $this->route('expense');

        return [
            'reference_no' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('expenses', 'reference_no')->ignore($id)],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:expense_categories,id'],
            'amount' => ['sometimes', 'required', 'numeric', 'decimal:0,2'],
            'tax_percent' => ['nullable', 'numeric', 'decimal:0,2'],
            'currency' => ['nullable', 'string', 'max:255'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'status' => ['nullable', 'in:pending,approved,paid,rejected'],
            'approver_id' => ['nullable', 'integer', 'exists:users,id'],
            'payee' => ['nullable', 'string', 'max:255'],
            'expense_date' => ['sometimes', 'required', 'date'],
            'receipt_path' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'created_by' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
