<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('staff')?->id ?? $this->route('staff');

        return [
            'staff_code' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('staff', 'staff_code')->ignore($id)],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'last_name' => ['sometimes', 'required', 'string', 'max:255'],
            'full_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'photo_url' => ['nullable', 'string', 'max:2048'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'role' => ['sometimes', 'required', 'string', 'max:255'],
            'shift' => ['nullable', 'string', 'max:50'],
            'reporting_to_id' => ['nullable', 'integer', 'exists:staff,id'],
            'joining_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:active,inactive'],
        ];
    }
}
