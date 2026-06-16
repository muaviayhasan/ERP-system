<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_code' => $this->student_code,
            'roll_number' => $this->roll_number,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name ?? trim("{$this->first_name} {$this->last_name}"),
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'cnic' => $this->cnic,
            'father_name' => $this->father_name,
            'photo_url' => $this->photo_url,
            'institute_type' => $this->institute_type,
            'specialization' => $this->specialization,
            'enrollment_session' => $this->enrollment_session,
            'status' => $this->status,
            'admission_status' => $this->admission_status,
            'campus_id' => $this->campus_id,
            'program_id' => $this->program_id,
            'academic_year_id' => $this->academic_year_id,
            'current_semester_id' => $this->current_semester_id,
            'section_id' => $this->section_id,
            'batch_id' => $this->batch_id,
            'advisor_id' => $this->advisor_id,
            'campus' => $this->whenLoaded('campus'),
            'program' => $this->whenLoaded('program'),
            'guardians' => GuardianResource::collection($this->whenLoaded('guardians')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
