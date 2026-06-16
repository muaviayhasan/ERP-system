<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalaryPaymentRequest extends FormRequest
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
            'salary_structure_id' => ['nullable', 'integer', 'exists:salary_structures,id'],
            'payroll_month' => ['required', 'string', 'max:255'],
            'role_label' => ['nullable', 'string', 'max:255'],
            'department_label' => ['nullable', 'string', 'max:255'],
            'basic' => ['required', 'numeric'],
            'allowances' => ['nullable', 'numeric'],
            'overtime_bonus' => ['nullable', 'numeric'],
            'deductions' => ['nullable', 'numeric'],
            'tax_deducted' => ['nullable', 'numeric'],
            'net_salary' => ['required', 'numeric'],
            'status' => ['nullable', 'in:pending,processed,paid,failed'],
            'transaction_ref' => ['nullable', 'string', 'max:255'],
            'processed_at' => ['nullable', 'date'],
        ];
    }
}
