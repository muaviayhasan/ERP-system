<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademicYearResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'scope' => $this->scope,
            'status' => $this->status,
            'link_fee_structure' => $this->link_fee_structure,
            'auto_roll_attendance' => $this->auto_roll_attendance,
            'fees_configured' => $this->fees_configured,
            'exams_configured' => $this->exams_configured,
            'attendance_enabled' => $this->attendance_enabled,
            'prevent_date_overlap' => $this->prevent_date_overlap,
            'semesters' => $this->whenLoaded('semesters'),
            'academic_settings' => $this->whenLoaded('academicSettings'),
            'campuses' => $this->whenLoaded('campuses'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
