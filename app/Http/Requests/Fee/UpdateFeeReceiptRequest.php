<?php

namespace App\Http\Requests\Fee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFeeReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('fee_receipt')?->id ?? $this->route('fee_receipt');

        return [
            'receipt_number' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('fee_receipts', 'receipt_number')->ignore($id)],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
            'fee_payment_id' => ['nullable', 'integer', 'exists:fee_payments,id'],
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'total_payable' => ['sometimes', 'required', 'numeric'],
            'amount_paid' => ['sometimes', 'required', 'numeric'],
            'balance' => ['nullable', 'numeric'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'collected_by' => ['nullable', 'integer', 'exists:users,id'],
            'notes' => ['nullable', 'string'],
            'issued_at' => ['sometimes', 'required', 'date'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }
}
