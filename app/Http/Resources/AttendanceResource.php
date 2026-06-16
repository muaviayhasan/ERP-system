<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'subject_id' => $this->subject_id,
            'teacher_id' => $this->teacher_id,
            'campus_id' => $this->campus_id,
            'date' => $this->date,
            'session' => $this->session,
            'status' => $this->status,
            'lecture_no' => $this->lecture_no,
            'room' => $this->room,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'remarks' => $this->remarks,
            'marked_by' => $this->marked_by,
            'marked_method' => $this->marked_method,
            'marked_at' => $this->marked_at,
            'student' => $this->whenLoaded('student'),
            'school_class' => $this->whenLoaded('schoolClass'),
            'section' => $this->whenLoaded('section'),
            'subject' => $this->whenLoaded('subject'),
            'teacher' => $this->whenLoaded('teacher'),
            'campus' => $this->whenLoaded('campus'),
            'marked_by_user' => $this->whenLoaded('markedBy'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
