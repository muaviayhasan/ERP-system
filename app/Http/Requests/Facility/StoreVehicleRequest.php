<?php

namespace App\Http\Requests\Facility;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_number' => ['required', 'string', 'max:255', 'unique:vehicles,vehicle_number'],
            'type' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer'],
            'occupied_seats' => ['nullable', 'integer'],
            'route_id' => ['nullable', 'integer', 'exists:transport_routes,id'],
            'driver_id' => ['nullable', 'integer', 'exists:staff,id'],
            'status' => ['nullable', 'string', 'max:255'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'last_service_km' => ['nullable', 'integer'],
        ];
    }
}
