<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HostelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'block' => $this->block,
            'type' => $this->type,
            'warden_id' => $this->warden_id,
            'total_rooms' => $this->total_rooms,
            'occupied_rooms' => $this->occupied_rooms,
            'occupancy_status' => $this->occupancy_status,
            'campus_id' => $this->campus_id,
            'warden' => $this->whenLoaded('warden'),
            'campus' => $this->whenLoaded('campus'),
            'rooms' => HostelRoomResource::collection($this->whenLoaded('rooms')),
            'allocations' => HostelAllocationResource::collection($this->whenLoaded('allocations')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
