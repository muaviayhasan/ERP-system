<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeeStructureResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'campus_id' => $this->campus_id,
            'institute_type' => $this->institute_type,
            'program_id' => $this->program_id,
            'level' => $this->level,
            'academic_year_id' => $this->academic_year_id,
            'billing_cycle' => $this->billing_cycle,
            'total_fee' => $this->total_fee,
            'scholarship_available' => $this->scholarship_available,
            'installments_enabled' => $this->installments_enabled,
            'installment_count' => $this->installment_count,
            'billing_day_of_month' => $this->billing_day_of_month,
            'status' => $this->status,
            'students_count' => $this->students_count,
            'campus' => $this->whenLoaded('campus'),
            'program' => $this->whenLoaded('program'),
            'academic_year' => $this->whenLoaded('academicYear'),
            'fee_plans' => $this->whenLoaded('feePlans'),
            'fee_structure_components' => $this->whenLoaded('feeStructureComponents'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
