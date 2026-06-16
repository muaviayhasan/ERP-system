<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoticeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'category' => $this->category,
            'type' => $this->type,
            'description' => $this->description,
            'priority' => $this->priority,
            'audience' => $this->audience,
            'publish_date' => $this->publish_date,
            'require_acknowledgment' => $this->require_acknowledgment,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'createdBy' => $this->whenLoaded('createdBy'),
            'acknowledgments' => $this->whenLoaded('acknowledgments'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
