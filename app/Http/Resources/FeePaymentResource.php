<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeePaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'student_fee_assignment_id' => $this->student_fee_assignment_id,
            'fee_installment_id' => $this->fee_installment_id,
            'receipt_id' => $this->receipt_id,
            'transaction_id' => $this->transaction_id,
            'amount_payable' => $this->amount_payable,
            'amount_paid' => $this->amount_paid,
            'balance' => $this->balance,
            'late_fee_amount' => $this->late_fee_amount,
            'payment_method' => $this->payment_method,
            'reference_number' => $this->reference_number,
            'auto_allocate_installments' => $this->auto_allocate_installments,
            'collected_by' => $this->collected_by,
            'paid_at' => $this->paid_at,
            'status' => $this->status,
            'student' => $this->whenLoaded('student'),
            'student_fee_assignment' => $this->whenLoaded('studentFeeAssignment'),
            'fee_installment' => $this->whenLoaded('feeInstallment'),
            'receipt' => $this->whenLoaded('receipt'),
            'collected_by_user' => $this->whenLoaded('collectedBy'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
