<?php

namespace App\Http\Requests\Facility;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTransportRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('transportRoute')?->id ?? $this->route('transportRoute');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'code' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('transport_routes', 'code')->ignore($id)],
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
