<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentFeeAssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'fee_structure_id' => $this->fee_structure_id,
            'fee_plan_id' => $this->fee_plan_id,
            'program_id' => $this->program_id,
            'semester_id' => $this->semester_id,
            'campus_id' => $this->campus_id,
            'academic_year_id' => $this->academic_year_id,
            'scholarship_id' => $this->scholarship_id,
            'scholarship_amount' => $this->scholarship_amount,
            'total_fee' => $this->total_fee,
            'final_payable' => $this->final_payable,
            'total_paid' => $this->total_paid,
            'total_pending' => $this->total_pending,
            'next_due_date' => $this->next_due_date,
            'late_fee_enabled' => $this->late_fee_enabled,
            'email_notifications_enabled' => $this->email_notifications_enabled,
            'status' => $this->status,
            'student' => $this->whenLoaded('student'),
            'fee_structure' => $this->whenLoaded('feeStructure'),
            'fee_plan' => $this->whenLoaded('feePlan'),
            'program' => $this->whenLoaded('program'),
            'semester' => $this->whenLoaded('semester'),
            'campus' => $this->whenLoaded('campus'),
            'academic_year' => $this->whenLoaded('academicYear'),
            'scholarship' => $this->whenLoaded('scholarship'),
            'fee_installments' => $this->whenLoaded('feeInstallments'),
            'fee_payments' => $this->whenLoaded('feePayments'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
