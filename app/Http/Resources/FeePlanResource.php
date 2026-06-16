<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeePlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'fee_structure_id' => $this->fee_structure_id,
            'schedule_type' => $this->schedule_type,
            'number_of_payments' => $this->number_of_payments,
            'start_date' => $this->start_date,
            'status' => $this->status,
            'fee_structure' => $this->whenLoaded('feeStructure'),
            'student_fee_assignments' => $this->whenLoaded('studentFeeAssignments'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
