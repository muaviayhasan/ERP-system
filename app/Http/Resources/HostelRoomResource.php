<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HostelRoomResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hostel_id' => $this->hostel_id,
            'room_number' => $this->room_number,
            'floor' => $this->floor,
            'type' => $this->type,
            'capacity' => $this->capacity,
            'available_beds' => $this->available_beds,
            'status' => $this->status,
            'room_rate' => $this->room_rate,
            'rate_period' => $this->rate_period,
            'hostel' => $this->whenLoaded('hostel'),
            'beds' => $this->whenLoaded('beds'),
            'allocations' => HostelAllocationResource::collection($this->whenLoaded('allocations')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
