<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScholarshipApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'scholarship_id' => $this->scholarship_id,
            'program_id' => $this->program_id,
            'semester_id' => $this->semester_id,
            'institute' => $this->institute,
            'type' => $this->type,
            'requested_discount_percent' => $this->requested_discount_percent,
            'requested_value' => $this->requested_value,
            'original_fee' => $this->original_fee,
            'final_payable' => $this->final_payable,
            'reason' => $this->reason,
            'cgpa' => $this->cgpa,
            'documents_count' => $this->documents_count,
            'gpa_check_passed' => $this->gpa_check_passed,
            'policy_compliance_passed' => $this->policy_compliance_passed,
            'no_duplicate_passed' => $this->no_duplicate_passed,
            'priority' => $this->priority,
            'status' => $this->status,
            'decision_notes' => $this->decision_notes,
            'reviewed_by' => $this->reviewed_by,
            'application_date' => $this->application_date,
            'student' => $this->whenLoaded('student'),
            'scholarship' => $this->whenLoaded('scholarship'),
            'program' => $this->whenLoaded('program'),
            'semester' => $this->whenLoaded('semester'),
            'reviewed_by_user' => $this->whenLoaded('reviewedBy'),
            'documents' => $this->whenLoaded('documents'),
            'logs' => $this->whenLoaded('logs'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
