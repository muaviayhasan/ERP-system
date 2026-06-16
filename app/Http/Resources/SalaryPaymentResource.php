<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalaryPaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_type' => $this->employee_type,
            'employee_id' => $this->employee_id,
            'salary_structure_id' => $this->salary_structure_id,
            'payroll_month' => $this->payroll_month,
            'role_label' => $this->role_label,
            'department_label' => $this->department_label,
            'basic' => $this->basic,
            'allowances' => $this->allowances,
            'overtime_bonus' => $this->overtime_bonus,
            'deductions' => $this->deductions,
            'tax_deducted' => $this->tax_deducted,
            'net_salary' => $this->net_salary,
            'status' => $this->status,
            'transaction_ref' => $this->transaction_ref,
            'processed_at' => $this->processed_at,
            'employee' => $this->whenLoaded('employee'),
            'salary_structure' => $this->whenLoaded('salaryStructure'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
