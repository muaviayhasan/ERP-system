<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimetableResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'campus_id' => $this->campus_id,
            'program_id' => $this->program_id,
            'semester_id' => $this->semester_id,
            'institute_type' => $this->institute_type,
            'week_start_date' => $this->week_start_date,
            'week_end_date' => $this->week_end_date,
            'campus' => $this->whenLoaded('campus'),
            'program' => $this->whenLoaded('program'),
            'semester' => $this->whenLoaded('semester'),
            'slots' => $this->whenLoaded('slots'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
