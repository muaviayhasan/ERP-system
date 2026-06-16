<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LedgerEntryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference_no' => $this->reference_no,
            'entry_date' => $this->entry_date,
            'type' => $this->type,
            'account_id' => $this->account_id,
            'debit' => $this->debit,
            'credit' => $this->credit,
            'status' => $this->status,
            'previous_balance' => $this->previous_balance,
            'adjusted_balance' => $this->adjusted_balance,
            'campus_id' => $this->campus_id,
            'description' => $this->description,
            'student_id' => $this->student_id,
            'invoice_no' => $this->invoice_no,
            'source_module' => $this->source_module,
            'created_by' => $this->created_by,
            'account' => $this->whenLoaded('account'),
            'campus' => $this->whenLoaded('campus'),
            'student' => $this->whenLoaded('student'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
