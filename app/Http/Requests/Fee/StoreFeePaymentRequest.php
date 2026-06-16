<?php

namespace App\Http\Requests\Fee;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
            'student_fee_assignment_id' => ['nullable', 'integer', 'exists:student_fee_assignments,id'],
            'fee_installment_id' => ['nullable', 'integer', 'exists:fee_installments,id'],
            'receipt_id' => ['nullable', 'integer', 'exists:fee_receipts,id'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'amount_payable' => ['required', 'numeric'],
            'amount_paid' => ['required', 'numeric'],
            'balance' => ['nullable', 'numeric'],
            'late_fee_amount' => ['nullable', 'numeric'],
            'payment_method' => ['required', 'in:cash,bank,card,online'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'auto_allocate_installments' => ['nullable', 'boolean'],
            'collected_by' => ['nullable', 'integer', 'exists:users,id'],
            'paid_at' => ['required', 'date'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }
}
