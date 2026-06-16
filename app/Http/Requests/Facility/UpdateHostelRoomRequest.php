<?php

namespace App\Http\Requests\Facility;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHostelRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hostel_id' => ['nullable', 'integer', 'exists:hostels,id'],
            'room_number' => ['sometimes', 'required', 'string', 'max:255'],
            'floor' => ['nullable', 'string', 'max:255'],
            'type' => ['sometimes', 'required', 'in:single,double,twin,quad,dormitory'],
            'capacity' => ['sometimes', 'required', 'integer'],
            'available_beds' => ['nullable', 'integer'],
            'status' => ['nullable', 'string', 'max:255'],
            'room_rate' => ['nullable', 'numeric'],
            'rate_period' => ['nullable', 'string', 'max:255'],
        ];
    }
}
