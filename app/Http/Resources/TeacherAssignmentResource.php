<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherAssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'teacher_id' => $this->teacher_id,
            'institute_type' => $this->institute_type,
            'department_id' => $this->department_id,
            'program_id' => $this->program_id,
            'class_id' => $this->class_id,
            'subject_id' => $this->subject_id,
            'course_id' => $this->course_id,
            'section_id' => $this->section_id,
            'semester_id' => $this->semester_id,
            'credits' => $this->credits,
            'lecture_hours' => $this->lecture_hours,
            'lab_hours' => $this->lab_hours,
            'weekly_hours' => $this->weekly_hours,
            'max_weekly_hours' => $this->max_weekly_hours,
            'timetable_status' => $this->timetable_status,
            'has_conflict' => $this->has_conflict,
            'conflict_note' => $this->conflict_note,
            'status' => $this->status,
            'teacher' => $this->whenLoaded('teacher'),
            'department' => $this->whenLoaded('department'),
            'program' => $this->whenLoaded('program'),
            'class' => $this->whenLoaded('class'),
            'subject' => $this->whenLoaded('subject'),
            'course' => $this->whenLoaded('course'),
            'section' => $this->whenLoaded('section'),
            'semester' => $this->whenLoaded('semester'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
