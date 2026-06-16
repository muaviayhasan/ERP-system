<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalaryStructureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_type' => ['nullable', 'string', 'max:255'],
            'employee_id' => ['nullable', 'integer'],
            'basic_salary' => ['required', 'numeric'],
            'transport_allowance' => ['nullable', 'numeric'],
            'medical_allowance' => ['nullable', 'numeric'],
            'housing_allowance' => ['nullable', 'numeric'],
            'overtime_rate' => ['nullable', 'numeric'],
            'performance_bonus' => ['nullable', 'numeric'],
            'currency' => ['nullable', 'string', 'max:10'],
            'effective_from' => ['nullable', 'date'],
        ];
    }
}
