<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudyMaterialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'subject_id' => $this->subject_id,
            'class_id' => $this->class_id,
            'folder_id' => $this->folder_id,
            'uploaded_by' => $this->uploaded_by,
            'file_path' => $this->file_path,
            'external_url' => $this->external_url,
            'file_size' => $this->file_size,
            'download_count' => $this->download_count,
            'view_count' => $this->view_count,
            'is_active' => $this->is_active,
            'published_at' => $this->published_at,
            'subject' => $this->whenLoaded('subject'),
            'school_class' => $this->whenLoaded('schoolClass'),
            'folder' => $this->whenLoaded('folder'),
            'uploaded_by_teacher' => $this->whenLoaded('uploadedBy'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
