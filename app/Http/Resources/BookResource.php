<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'author' => $this->author,
            'isbn' => $this->isbn,
            'category' => $this->category,
            'cover_image_url' => $this->cover_image_url,
            'total_copies' => $this->total_copies,
            'available_copies' => $this->available_copies,
            'availability_status' => $this->availability_status,
            'borrow_count' => $this->borrow_count,
            'campus_id' => $this->campus_id,
            'campus' => $this->whenLoaded('campus'),
            'book_issues' => BookIssueResource::collection($this->whenLoaded('bookIssues')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
