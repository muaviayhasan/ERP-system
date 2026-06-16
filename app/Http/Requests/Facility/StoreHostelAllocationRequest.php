<?php

namespace App\Http\Requests\Facility;

use Illuminate\Foundation\Http\FormRequest;

class StoreHostelAllocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
            'hostel_id' => ['nullable', 'integer', 'exists:hostels,id'],
            'room_id' => ['nullable', 'integer', 'exists:hostel_rooms,id'],
            'bed_id' => ['nullable', 'integer', 'exists:hostel_beds,id'],
            'check_in_date' => ['nullable', 'date'],
            'check_out_date' => ['nullable', 'date'],
            'room_rate' => ['nullable', 'numeric'],
            'rate_period' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }
}
