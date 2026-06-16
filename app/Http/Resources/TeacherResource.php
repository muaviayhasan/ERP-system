<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'teacher_code' => $this->teacher_code,
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name ?? trim("{$this->first_name} {$this->last_name}"),
            'email' => $this->email,
            'phone' => $this->phone,
            'cnic' => $this->cnic,
            'photo_url' => $this->photo_url,
            'campus_id' => $this->campus_id,
            'department_id' => $this->department_id,
            'designation' => $this->designation,
            'institute_type' => $this->institute_type,
            'weekly_workload_hours' => $this->weekly_workload_hours,
            'max_workload_hours' => $this->max_workload_hours,
            'joining_date' => $this->joining_date,
            'status' => $this->status,
            'user' => $this->whenLoaded('user'),
            'campus' => $this->whenLoaded('campus'),
            'department' => $this->whenLoaded('department'),
            'programs' => $this->whenLoaded('programs'),
            'assignments' => TeacherAssignmentResource::collection($this->whenLoaded('assignments')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
