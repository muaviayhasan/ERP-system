<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RefundResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference_no' => $this->reference_no,
            'student_id' => $this->student_id,
            'program_id' => $this->program_id,
            'semester_id' => $this->semester_id,
            'refund_type' => $this->refund_type,
            'reason' => $this->reason,
            'description' => $this->description,
            'payment_reference' => $this->payment_reference,
            'total_paid' => $this->total_paid,
            'actual_due' => $this->actual_due,
            'max_eligible_refund' => $this->max_eligible_refund,
            'requested_amount' => $this->requested_amount,
            'approved_amount' => $this->approved_amount,
            'payment_verified' => $this->payment_verified,
            'ledger_reconciled' => $this->ledger_reconciled,
            'status' => $this->status,
            'remarks' => $this->remarks,
            'approved_by' => $this->approved_by,
            'payment_method' => $this->payment_method,
            'payout_date' => $this->payout_date,
            'payout_reference' => $this->payout_reference,
            'request_date' => $this->request_date,
            'student' => $this->whenLoaded('student'),
            'program' => $this->whenLoaded('program'),
            'semester' => $this->whenLoaded('semester'),
            'approved_by_user' => $this->whenLoaded('approvedBy'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
