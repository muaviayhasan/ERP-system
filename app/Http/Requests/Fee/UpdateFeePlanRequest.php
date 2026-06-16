<?php

namespace App\Http\Requests\Fee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'fee_structure_id' => ['nullable', 'integer', 'exists:fee_structures,id'],
            'schedule_type' => ['sometimes', 'required', 'in:installments,lump_sum,monthly,quarterly,full_payment'],
            'number_of_payments' => ['nullable', 'integer'],
            'start_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }
}
