<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeeCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'code_assignment' => $this->code_assignment,
            'description' => $this->description,
            'fee_type' => $this->fee_type,
            'default_amount' => $this->default_amount,
            'currency' => $this->currency,
            'applies_to_school' => $this->applies_to_school,
            'applies_to_college' => $this->applies_to_college,
            'applies_to_university' => $this->applies_to_university,
            'late_fee_enabled' => $this->late_fee_enabled,
            'late_fee_type' => $this->late_fee_type,
            'late_fee_amount' => $this->late_fee_amount,
            'grace_period_days' => $this->grace_period_days,
            'tax_applicable' => $this->tax_applicable,
            'tax_percentage' => $this->tax_percentage,
            'scholarship_eligible' => $this->scholarship_eligible,
            'refundable' => $this->refundable,
            'auto_generate_on_admission' => $this->auto_generate_on_admission,
            'status' => $this->status,
            'fee_structure_components' => $this->whenLoaded('feeStructureComponents'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
