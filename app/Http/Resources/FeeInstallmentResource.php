<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeeInstallmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_fee_assignment_id' => $this->student_fee_assignment_id,
            'installment_number' => $this->installment_number,
            'label' => $this->label,
            'due_date' => $this->due_date,
            'percentage' => $this->percentage,
            'amount' => $this->amount,
            'amount_paid' => $this->amount_paid,
            'status' => $this->status,
            'paid_at' => $this->paid_at,
            'student_fee_assignment' => $this->whenLoaded('studentFeeAssignment'),
            'fee_payments' => $this->whenLoaded('feePayments'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
