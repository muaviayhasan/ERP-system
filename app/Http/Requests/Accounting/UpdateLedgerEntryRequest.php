<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLedgerEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('ledgerEntry')?->id ?? $this->route('ledgerEntry');

        return [
            'reference_no' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('ledger_entries', 'reference_no')->ignore($id)],
            'entry_date' => ['sometimes', 'required', 'date'],
            'type' => ['sometimes', 'required', 'in:fee,salary,expense,other'],
            'account_id' => ['nullable', 'integer', 'exists:ledger_accounts,id'],
            'debit' => ['nullable', 'numeric', 'decimal:0,2'],
            'credit' => ['nullable', 'numeric', 'decimal:0,2'],
            'status' => ['nullable', 'in:posted,pending,reversed'],
            'previous_balance' => ['nullable', 'numeric', 'decimal:0,2'],
            'adjusted_balance' => ['nullable', 'numeric', 'decimal:0,2'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'description' => ['nullable', 'string'],
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
            'invoice_no' => ['nullable', 'string', 'max:255'],
            'source_module' => ['nullable', 'string', 'max:255'],
            'created_by' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
