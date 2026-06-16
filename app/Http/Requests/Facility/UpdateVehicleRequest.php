<?php

namespace App\Http\Requests\Facility;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('vehicle')?->id ?? $this->route('vehicle');

        return [
            'vehicle_number' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('vehicles', 'vehicle_number')->ignore($id)],
            'type' => ['sometimes', 'required', 'string', 'max:255'],
            'capacity' => ['sometimes', 'required', 'integer'],
            'occupied_seats' => ['nullable', 'integer'],
            'route_id' => ['nullable', 'integer', 'exists:transport_routes,id'],
            'driver_id' => ['nullable', 'integer', 'exists:staff,id'],
            'status' => ['nullable', 'string', 'max:255'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'last_service_km' => ['nullable', 'integer'],
        ];
    }
}
