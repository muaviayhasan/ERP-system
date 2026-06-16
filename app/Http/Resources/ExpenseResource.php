<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference_no' => $this->reference_no,
            'title' => $this->title,
            'category_id' => $this->category_id,
            'amount' => $this->amount,
            'tax_percent' => $this->tax_percent,
            'currency' => $this->currency,
            'campus_id' => $this->campus_id,
            'status' => $this->status,
            'approver_id' => $this->approver_id,
            'payee' => $this->payee,
            'expense_date' => $this->expense_date,
            'receipt_path' => $this->receipt_path,
            'notes' => $this->notes,
            'created_by' => $this->created_by,
            'category' => $this->whenLoaded('category'),
            'campus' => $this->whenLoaded('campus'),
            'approver' => $this->whenLoaded('approver'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
