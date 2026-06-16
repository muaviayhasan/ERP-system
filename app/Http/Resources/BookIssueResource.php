<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookIssueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'book_id' => $this->book_id,
            'borrower_type' => $this->borrower_type,
            'student_id' => $this->student_id,
            'issued_by' => $this->issued_by,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'return_date' => $this->return_date,
            'status' => $this->status,
            'fine_amount' => $this->fine_amount,
            'fine_paid' => $this->fine_paid,
            'renewal_count' => $this->renewal_count,
            'book' => $this->whenLoaded('book'),
            'student' => $this->whenLoaded('student'),
            'issued_by_user' => $this->whenLoaded('issuedBy'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
