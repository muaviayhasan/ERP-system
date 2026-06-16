<?php

namespace App\Http\Requests\Fee;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePendingFeeRequest extends FormRequest
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
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'amount_payable' => ['sometimes', 'required', 'numeric'],
            'amount_paid' => ['nullable', 'numeric'],
            'amount_pending' => ['sometimes', 'required', 'numeric'],
            'late_fee_amount' => ['nullable', 'numeric'],
            'due_date' => ['nullable', 'date'],
            'days_overdue' => ['nullable', 'integer'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }
}
