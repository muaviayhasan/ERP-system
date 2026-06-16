<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'exam_id' => $this->exam_id,
            'student_id' => $this->student_id,
            'subject_id' => $this->subject_id,
            'evaluator_id' => $this->evaluator_id,
            'attendance_status' => $this->attendance_status,
            'marks_obtained' => $this->marks_obtained,
            'total_marks' => $this->total_marks,
            'percentage' => $this->percentage,
            'grade' => $this->grade,
            'remarks' => $this->remarks,
            'is_flagged' => $this->is_flagged,
            'validation_error' => $this->validation_error,
            'entry_status' => $this->entry_status,
            'exam' => $this->whenLoaded('exam'),
            'student' => $this->whenLoaded('student'),
            'subject' => $this->whenLoaded('subject'),
            'evaluator' => $this->whenLoaded('evaluator'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
