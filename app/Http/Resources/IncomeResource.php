<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncomeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference_no' => $this->reference_no,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'category_id' => $this->category_id,
            'amount' => $this->amount,
            'tax_percent' => $this->tax_percent,
            'campus_id' => $this->campus_id,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'module_link' => $this->module_link,
            'income_date' => $this->income_date,
            'notes' => $this->notes,
            'created_by' => $this->created_by,
            'category' => $this->whenLoaded('category'),
            'campus' => $this->whenLoaded('campus'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
