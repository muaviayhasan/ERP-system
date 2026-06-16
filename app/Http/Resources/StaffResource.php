<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'staff_code' => $this->staff_code,
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name ?? trim("{$this->first_name} {$this->last_name}"),
            'email' => $this->email,
            'phone' => $this->phone,
            'photo_url' => $this->photo_url,
            'department_id' => $this->department_id,
            'campus_id' => $this->campus_id,
            'role' => $this->role,
            'shift' => $this->shift,
            'reporting_to_id' => $this->reporting_to_id,
            'joining_date' => $this->joining_date,
            'status' => $this->status,
            'user' => $this->whenLoaded('user'),
            'department' => $this->whenLoaded('department'),
            'campus' => $this->whenLoaded('campus'),
            'reporting_to' => $this->whenLoaded('reportingTo'),
            'attendances' => StaffAttendanceResource::collection($this->whenLoaded('attendances')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
