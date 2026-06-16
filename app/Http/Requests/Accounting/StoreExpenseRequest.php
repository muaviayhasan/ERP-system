<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference_no' => ['required', 'string', 'max:255', 'unique:expenses,reference_no'],
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:expense_categories,id'],
            'amount' => ['required', 'numeric', 'decimal:0,2'],
            'tax_percent' => ['nullable', 'numeric', 'decimal:0,2'],
            'currency' => ['nullable', 'string', 'max:255'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'status' => ['nullable', 'in:pending,approved,paid,rejected'],
            'approver_id' => ['nullable', 'integer', 'exists:users,id'],
            'payee' => ['nullable', 'string', 'max:255'],
            'expense_date' => ['required', 'date'],
            'receipt_path' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'created_by' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
