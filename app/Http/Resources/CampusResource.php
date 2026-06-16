<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampusResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'institution_type' => $this->institution_type,
            'description' => $this->description,
            'street_address' => $this->street_address,
            'city' => $this->city,
            'state_province' => $this->state_province,
            'founded_year' => $this->founded_year,
            'status' => $this->status,
            'enable_online_admissions' => $this->enable_online_admissions,
            'centralized_fee_collection' => $this->centralized_fee_collection,
            'hostel_management' => $this->hostel_management,
            'primary_bank_name' => $this->primary_bank_name,
            'bank_account_number' => $this->bank_account_number,
            'bank_swift_code' => $this->bank_swift_code,
            'departments' => $this->whenLoaded('departments'),
            'programs' => $this->whenLoaded('programs'),
            'courses' => $this->whenLoaded('courses'),
            'sections' => $this->whenLoaded('sections'),
            'batches' => $this->whenLoaded('batches'),
            'semesters' => $this->whenLoaded('semesters'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
