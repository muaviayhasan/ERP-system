<?php

namespace App\Http\Requests\Scholarship;

use Illuminate\Foundation\Http\FormRequest;

class StoreRefundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference_no' => ['required', 'string', 'max:255', 'unique:refunds,reference_no'],
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'semester_id' => ['nullable', 'integer', 'exists:semesters,id'],
            'refund_type' => ['required', 'in:overpayment,withdrawal,course_change'],
            'reason' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'payment_reference' => ['nullable', 'string', 'max:255'],
            'total_paid' => ['nullable', 'numeric'],
            'actual_due' => ['nullable', 'numeric'],
            'max_eligible_refund' => ['nullable', 'numeric'],
            'requested_amount' => ['required', 'numeric'],
            'approved_amount' => ['nullable', 'numeric'],
            'payment_verified' => ['nullable', 'boolean'],
            'ledger_reconciled' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
            'approved_by' => ['nullable', 'integer', 'exists:users,id'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'payout_date' => ['nullable', 'date'],
            'payout_reference' => ['nullable', 'string', 'max:255'],
            'request_date' => ['required', 'date'],
        ];
    }
}
