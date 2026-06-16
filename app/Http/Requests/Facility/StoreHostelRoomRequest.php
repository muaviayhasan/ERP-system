<?php

namespace App\Http\Requests\Facility;

use Illuminate\Foundation\Http\FormRequest;

class StoreHostelRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hostel_id' => ['nullable', 'integer', 'exists:hostels,id'],
            'room_number' => ['required', 'string', 'max:255'],
            'floor' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:single,double,twin,quad,dormitory'],
            'capacity' => ['required', 'integer'],
            'available_beds' => ['nullable', 'integer'],
            'status' => ['nullable', 'string', 'max:255'],
            'room_rate' => ['nullable', 'numeric'],
            'rate_period' => ['nullable', 'string', 'max:255'],
        ];
    }
}
