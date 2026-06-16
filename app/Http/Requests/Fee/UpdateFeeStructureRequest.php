<?php

namespace App\Http\Requests\Fee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFeeStructureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('fee_structure')?->id ?? $this->route('fee_structure');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'code' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('fee_structures', 'code')->ignore($id)],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'institute_type' => ['nullable', 'string', 'max:255'],
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'level' => ['nullable', 'string', 'max:255'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'billing_cycle' => ['sometimes', 'required', 'string', 'max:255'],
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
