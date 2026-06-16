<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PendingFeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'student_fee_assignment_id' => $this->student_fee_assignment_id,
            'program_id' => $this->program_id,
            'amount_payable' => $this->amount_payable,
            'amount_paid' => $this->amount_paid,
            'amount_pending' => $this->amount_pending,
            'late_fee_amount' => $this->late_fee_amount,
            'due_date' => $this->due_date,
            'days_overdue' => $this->days_overdue,
            'status' => $this->status,
            'student' => $this->whenLoaded('student'),
            'student_fee_assignment' => $this->whenLoaded('studentFeeAssignment'),
            'program' => $this->whenLoaded('program'),
            'fee_reminders' => $this->whenLoaded('feeReminders'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
