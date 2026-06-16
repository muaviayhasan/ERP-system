<?php

namespace App\Http\Requests\Facility;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransportRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:transport_routes,code'],
            'start_point' => ['nullable', 'string', 'max:255'],
            'end_point' => ['nullable', 'string', 'max:255'],
            'vehicle_id' => ['nullable', 'integer', 'exists:vehicles,id'],
            'stops_count' => ['nullable', 'integer'],
            'students_count' => ['nullable', 'integer'],
            'duration_minutes' => ['nullable', 'integer'],
            'monthly_fee' => ['nullable', 'numeric'],
            'status' => ['nullable', 'string', 'max:255'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
        ];
    }
}
