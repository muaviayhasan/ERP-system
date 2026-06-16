<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vehicle_number' => $this->vehicle_number,
            'type' => $this->type,
            'capacity' => $this->capacity,
            'occupied_seats' => $this->occupied_seats,
            'route_id' => $this->route_id,
            'driver_id' => $this->driver_id,
            'status' => $this->status,
            'campus_id' => $this->campus_id,
            'last_service_km' => $this->last_service_km,
            'route' => $this->whenLoaded('route'),
            'driver' => $this->whenLoaded('driver'),
            'campus' => $this->whenLoaded('campus'),
            'maintenance_logs' => $this->whenLoaded('maintenanceLogs'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
