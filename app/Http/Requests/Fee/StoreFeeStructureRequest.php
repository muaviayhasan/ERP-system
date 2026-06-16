<?php

namespace App\Http\Requests\Fee;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeeStructureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:fee_structures,code'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'institute_type' => ['nullable', 'string', 'max:255'],
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'level' => ['nullable', 'string', 'max:255'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'billing_cycle' => ['required', 'string', 'max:255'],
            'total_fee' => ['nullable', 'numeric'],
            'scholarship_available' => ['nullable', 'boolean'],
            'installments_enabled' => ['nullable', 'boolean'],
            'installment_count' => ['nullable', 'integer'],
            'billing_day_of_month' => ['nullable', 'integer'],
            'status' => ['nullable', 'string', 'max:255'],
            'students_count' => ['nullable', 'integer'],
        ];
    }
}
