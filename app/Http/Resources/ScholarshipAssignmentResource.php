<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScholarshipAssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'scholarship_id' => $this->scholarship_id,
            'discount_amount' => $this->discount_amount,
            'status' => $this->status,
            'assigned_by' => $this->assigned_by,
            'expires_at' => $this->expires_at,
            'student' => $this->whenLoaded('student'),
            'scholarship' => $this->whenLoaded('scholarship'),
            'assigned_by_user' => $this->whenLoaded('assignedBy'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
