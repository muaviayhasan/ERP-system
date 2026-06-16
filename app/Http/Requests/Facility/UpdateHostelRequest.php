<?php

namespace App\Http\Requests\Facility;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHostelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'block' => ['nullable', 'string', 'max:255'],
            'type' => ['sometimes', 'required', 'in:boys,girls,faculty_staff'],
            'warden_id' => ['nullable', 'integer', 'exists:staff,id'],
            'total_rooms' => ['nullable', 'integer'],
            'occupied_rooms' => ['nullable', 'integer'],
            'occupancy_status' => ['nullable', 'string', 'max:255'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
        ];
    }
}
