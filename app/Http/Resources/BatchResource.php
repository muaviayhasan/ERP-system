<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'batch_type' => $this->batch_type,
            'description' => $this->description,
            'status' => $this->status,
            'institution_type' => $this->institution_type,
            'campus_id' => $this->campus_id,
            'program_id' => $this->program_id,
            'class_id' => $this->class_id,
            'semester_id' => $this->semester_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'weekly_days' => $this->weekly_days,
            'max_students' => $this->max_students,
            'allow_waitlist' => $this->allow_waitlist,
            'primary_instructor_id' => $this->primary_instructor_id,
            'fee_plan_id' => $this->fee_plan_id,
            'attendance_tracking' => $this->attendance_tracking,
            'installments_allowed' => $this->installments_allowed,
            'open_for_admissions' => $this->open_for_admissions,
            'campus' => $this->whenLoaded('campus'),
            'program' => $this->whenLoaded('program'),
            'school_class' => $this->whenLoaded('schoolClass'),
            'semester' => $this->whenLoaded('semester'),
            'primary_instructor' => $this->whenLoaded('primaryInstructor'),
            'fee_plan' => $this->whenLoaded('feePlan'),
            'students' => $this->whenLoaded('students'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
