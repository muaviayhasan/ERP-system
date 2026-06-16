<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SemesterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'program_id' => $this->program_id,
            'department_id' => $this->department_id,
            'campus_id' => $this->campus_id,
            'academic_year_id' => $this->academic_year_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'total_credit_hours' => $this->total_credit_hours,
            'generate_fee_plan' => $this->generate_fee_plan,
            'late_fee_rule' => $this->late_fee_rule,
            'grading_system' => $this->grading_system,
            'is_locked' => $this->is_locked,
            'fee_cycle_generated' => $this->fee_cycle_generated,
            'exam_cycle_generated' => $this->exam_cycle_generated,
            'status' => $this->status,
            'program' => $this->whenLoaded('program'),
            'department' => $this->whenLoaded('department'),
            'campus' => $this->whenLoaded('campus'),
            'academic_year' => $this->whenLoaded('academicYear'),
            'courses' => $this->whenLoaded('courses'),
            'subjects' => $this->whenLoaded('subjects'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
