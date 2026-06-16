<?php

namespace App\Http\Requests\Fee;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeeInstallmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_fee_assignment_id' => ['nullable', 'integer', 'exists:student_fee_assignments,id'],
            'installment_number' => ['required', 'integer'],
            'label' => ['nullable', 'string', 'max:255'],
            'due_date' => ['required', 'date'],
            'percentage' => ['nullable', 'numeric'],
            'amount' => ['required', 'numeric'],
            'amount_paid' => ['nullable', 'numeric'],
            'status' => ['nullable', 'string', 'max:255'],
            'paid_at' => ['nullable', 'date'],
        ];
    }
}
