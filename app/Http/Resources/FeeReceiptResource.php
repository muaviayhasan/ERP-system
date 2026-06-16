<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeeReceiptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'receipt_number' => $this->receipt_number,
            'transaction_id' => $this->transaction_id,
            'student_id' => $this->student_id,
            'fee_payment_id' => $this->fee_payment_id,
            'program_id' => $this->program_id,
            'campus_id' => $this->campus_id,
            'total_payable' => $this->total_payable,
            'amount_paid' => $this->amount_paid,
            'balance' => $this->balance,
            'payment_method' => $this->payment_method,
            'reference_number' => $this->reference_number,
            'collected_by' => $this->collected_by,
            'notes' => $this->notes,
            'issued_at' => $this->issued_at,
            'status' => $this->status,
            'student' => $this->whenLoaded('student'),
            'fee_payment' => $this->whenLoaded('feePayment'),
            'program' => $this->whenLoaded('program'),
            'campus' => $this->whenLoaded('campus'),
            'collected_by_user' => $this->whenLoaded('collectedBy'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
