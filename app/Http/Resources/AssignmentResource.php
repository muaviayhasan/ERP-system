<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'code' => $this->code,
            'description' => $this->description,
            'subject_id' => $this->subject_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'teacher_id' => $this->teacher_id,
            'due_date' => $this->due_date,
            'total_marks' => $this->total_marks,
            'expected_submissions' => $this->expected_submissions,
            'status' => $this->status,
            'subject' => $this->whenLoaded('subject'),
            'school_class' => $this->whenLoaded('schoolClass'),
            'section' => $this->whenLoaded('section'),
            'teacher' => $this->whenLoaded('teacher'),
            'submissions' => $this->whenLoaded('submissions'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
