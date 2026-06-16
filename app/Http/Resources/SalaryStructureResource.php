<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalaryStructureResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_type' => $this->employee_type,
            'employee_id' => $this->employee_id,
            'basic_salary' => $this->basic_salary,
            'transport_allowance' => $this->transport_allowance,
            'medical_allowance' => $this->medical_allowance,
            'housing_allowance' => $this->housing_allowance,
            'overtime_rate' => $this->overtime_rate,
            'performance_bonus' => $this->performance_bonus,
            'currency' => $this->currency,
            'effective_from' => $this->effective_from,
            'employee' => $this->whenLoaded('employee'),
            'payments' => SalaryPaymentResource::collection($this->whenLoaded('payments')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
