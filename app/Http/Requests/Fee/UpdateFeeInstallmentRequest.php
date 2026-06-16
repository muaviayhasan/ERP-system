<?php

namespace App\Http\Requests\Fee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeeInstallmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_fee_assignment_id' => ['nullable', 'integer', 'exists:student_fee_assignments,id'],
            'installment_number' => ['sometimes', 'required', 'integer'],
            'label' => ['nullable', 'string', 'max:255'],
            'due_date' => ['sometimes', 'required', 'date'],
            'percentage' => ['nullable', 'numeric'],
            'amount' => ['sometimes', 'required', 'numeric'],
            'amount_paid' => ['nullable', 'numeric'],
            'status' => ['nullable', 'string', 'max:255'],
            'paid_at' => ['nullable', 'date'],
        ];
    }
}
