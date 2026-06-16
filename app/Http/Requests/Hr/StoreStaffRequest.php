<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'staff_code' => ['required', 'string', 'max:255', 'unique:staff,staff_code'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'full_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'photo_url' => ['nullable', 'string', 'max:2048'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'role' => ['required', 'string', 'max:255'],
            'shift' => ['nullable', 'string', 'max:50'],
            'reporting_to_id' => ['nullable', 'integer', 'exists:staff,id'],
            'joining_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:active,inactive'],
        ];
    }
}
