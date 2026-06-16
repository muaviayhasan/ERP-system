<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'teacher_code' => ['required', 'string', 'max:255', 'unique:teachers,teacher_code'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'full_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'cnic' => ['nullable', 'string', 'max:50'],
            'photo_url' => ['nullable', 'string', 'max:2048'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'designation' => ['required', 'string', 'max:255'],
            'institute_type' => ['nullable', 'string', 'max:50'],
            'weekly_workload_hours' => ['nullable', 'numeric'],
            'max_workload_hours' => ['nullable', 'numeric'],
            'joining_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:active,inactive'],
        ];
    }
}
