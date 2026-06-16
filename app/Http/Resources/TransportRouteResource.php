<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransportRouteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'start_point' => $this->start_point,
            'end_point' => $this->end_point,
            'vehicle_id' => $this->vehicle_id,
            'stops_count' => $this->stops_count,
            'students_count' => $this->students_count,
            'duration_minutes' => $this->duration_minutes,
            'monthly_fee' => $this->monthly_fee,
            'status' => $this->status,
            'campus_id' => $this->campus_id,
            'vehicle' => $this->whenLoaded('vehicle'),
            'campus' => $this->whenLoaded('campus'),
            'route_stops' => $this->whenLoaded('routeStops'),
            'transport_assignments' => TransportAssignmentResource::collection($this->whenLoaded('transportAssignments')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
