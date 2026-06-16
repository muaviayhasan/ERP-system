<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'exam_id' => $this->exam_id,
            'subject_id' => $this->subject_id,
            'program_id' => $this->program_id,
            'class_label' => $this->class_label,
            'exam_date' => $this->exam_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'duration_hours' => $this->duration_hours,
            'venue' => $this->venue,
            'invigilator_id' => $this->invigilator_id,
            'exam_type' => $this->exam_type,
            'status' => $this->status,
            'has_conflict' => $this->has_conflict,
            'conflict_severity' => $this->conflict_severity,
            'conflict_note' => $this->conflict_note,
            'exam' => $this->whenLoaded('exam'),
            'subject' => $this->whenLoaded('subject'),
            'program' => $this->whenLoaded('program'),
            'invigilator' => $this->whenLoaded('invigilator'),
            'exam_schedule_conflicts' => $this->whenLoaded('examScheduleConflicts'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
