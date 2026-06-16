<?php

namespace App\Http\Requests\Scholarship;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRefundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('refund')?->id ?? $this->route('refund');

        return [
            'reference_no' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('refunds', 'reference_no')->ignore($id)],
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'semester_id' => ['nullable', 'integer', 'exists:semesters,id'],
            'refund_type' => ['sometimes', 'required', 'in:overpayment,withdrawal,course_change'],
            'reason' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'payment_reference' => ['nullable', 'string', 'max:255'],
            'total_paid' => ['nullable', 'numeric'],
            'actual_due' => ['nullable', 'numeric'],
            'max_eligible_refund' => ['nullable', 'numeric'],
            'requested_amount' => ['sometimes', 'required', 'numeric'],
            'approved_amount' => ['nullable', 'numeric'],
            'payment_verified' => ['nullable', 'boolean'],
            'ledger_reconciled' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
            'approved_by' => ['nullable', 'integer', 'exists:users,id'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'payout_date' => ['nullable', 'date'],
            'payout_reference' => ['nullable', 'string', 'max:255'],
            'request_date' => ['sometimes', 'required', 'date'],
        ];
    }
}
