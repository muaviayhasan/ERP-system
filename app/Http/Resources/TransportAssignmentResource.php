<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransportAssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'route_id' => $this->route_id,
            'pickup_stop_id' => $this->pickup_stop_id,
            'dropoff_stop_id' => $this->dropoff_stop_id,
            'monthly_fee' => $this->monthly_fee,
            'status' => $this->status,
            'student' => $this->whenLoaded('student'),
            'route' => $this->whenLoaded('route'),
            'pickup_stop' => $this->whenLoaded('pickupStop'),
            'dropoff_stop' => $this->whenLoaded('dropoffStop'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
