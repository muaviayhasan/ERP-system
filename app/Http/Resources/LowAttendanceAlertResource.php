<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LowAttendanceAlertResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'class_id' => $this->class_id,
            'attendance_percentage' => $this->attendance_percentage,
            'required_percentage' => $this->required_percentage,
            'risk_level' => $this->risk_level,
            'absents_count' => $this->absents_count,
            'lates_count' => $this->lates_count,
            'trend' => $this->trend,
            'scholarship_status' => $this->scholarship_status,
            'exam_eligibility_restricted' => $this->exam_eligibility_restricted,
            'sms_warning_sent' => $this->sms_warning_sent,
            'guardian_notified' => $this->guardian_notified,
            'last_warning_sent_at' => $this->last_warning_sent_at,
            'student' => $this->whenLoaded('student'),
            'school_class' => $this->whenLoaded('schoolClass'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
