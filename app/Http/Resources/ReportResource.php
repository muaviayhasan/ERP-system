<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'format' => $this->format,
            'parameters' => $this->parameters,
            'generated_by' => $this->generated_by,
            'generated_at' => $this->generated_at,
            'generatedBy' => $this->whenLoaded('generatedBy'),
            'scheduledReports' => $this->whenLoaded('scheduledReports'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
