<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'fine_rule_id' => $this->fine_rule_id,
            'reason' => $this->reason,
            'amount' => $this->amount,
            'date_applied' => $this->date_applied,
            'status' => $this->status,
            'collected_by' => $this->collected_by,
            'collected_at' => $this->collected_at,
            'waived_by' => $this->waived_by,
            'waived_at' => $this->waived_at,
            'student' => $this->whenLoaded('student'),
            'fine_rule' => $this->whenLoaded('fineRule'),
            'collected_by_user' => $this->whenLoaded('collectedBy'),
            'waived_by_user' => $this->whenLoaded('waivedBy'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
