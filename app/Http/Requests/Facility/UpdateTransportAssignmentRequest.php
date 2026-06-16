<?php

namespace App\Http\Requests\Facility;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransportAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
            'route_id' => ['nullable', 'integer', 'exists:transport_routes,id'],
            'pickup_stop_id' => ['nullable', 'integer', 'exists:route_stops,id'],
            'dropoff_stop_id' => ['nullable', 'integer', 'exists:route_stops,id'],
            'monthly_fee' => ['nullable', 'numeric'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }
}
