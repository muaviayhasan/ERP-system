<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HostelAllocationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'hostel_id' => $this->hostel_id,
            'room_id' => $this->room_id,
            'bed_id' => $this->bed_id,
            'check_in_date' => $this->check_in_date,
            'check_out_date' => $this->check_out_date,
            'room_rate' => $this->room_rate,
            'rate_period' => $this->rate_period,
            'status' => $this->status,
            'student' => $this->whenLoaded('student'),
            'hostel' => $this->whenLoaded('hostel'),
            'room' => $this->whenLoaded('room'),
            'bed' => $this->whenLoaded('bed'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
