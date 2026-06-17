<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('teacher')?->id ?? $this->route('teacher');

        return [
            'teacher_code' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('teachers', 'teacher_code')->ignore($id)],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'last_name' => ['sometimes', 'required', 'string', 'max:255'],
            'full_name' => ['nullable', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'cnic' => ['nullable', 'string', 'max:50'],
            'photo_url' => ['nullable', 'string', 'max:2048'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'designation' => ['sometimes', 'required', 'string', 'max:255'],
            'institute_type' => ['nullable', 'string', 'max:50'],
            'weekly_workload_hours' => ['nullable', 'numeric'],
            'max_workload_hours' => ['nullable', 'numeric'],
            'joining_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:active,inactive'],
            'programs' => ['nullable', 'array'],
            'programs.*' => ['integer', 'exists:programs,id'],
        ];
    }
}
