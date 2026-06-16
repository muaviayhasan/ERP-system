<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffAttendanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'staff_id' => $this->staff_id,
            'department_id' => $this->department_id,
            'campus_id' => $this->campus_id,
            'attendance_date' => $this->attendance_date,
            'shift' => $this->shift,
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'work_hours' => $this->work_hours,
            'status' => $this->status,
            'is_overtime' => $this->is_overtime,
            'needs_correction' => $this->needs_correction,
            'marked_by' => $this->marked_by,
            'staff' => $this->whenLoaded('staff'),
            'department' => $this->whenLoaded('department'),
            'campus' => $this->whenLoaded('campus'),
            'marked_by_user' => $this->whenLoaded('markedBy'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
