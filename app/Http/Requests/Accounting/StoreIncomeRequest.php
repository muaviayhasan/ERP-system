<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class StoreIncomeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference_no' => ['required', 'string', 'max:255', 'unique:incomes,reference_no'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:income_categories,id'],
            'amount' => ['required', 'numeric', 'decimal:0,2'],
            'tax_percent' => ['nullable', 'numeric', 'decimal:0,2'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'payment_method' => ['required', 'in:bank_transfer,cash,check,card_payment'],
            'status' => ['nullable', 'in:received,confirmed,pending'],
            'module_link' => ['nullable', 'string', 'max:255'],
            'income_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'created_by' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
