<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'staff_id' => ['nullable', 'integer', 'exists:staff,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'attendance_date' => ['sometimes', 'required', 'date'],
            'shift' => ['nullable', 'string', 'max:50'],
            'check_in' => ['nullable', 'date_format:H:i:s'],
            'check_out' => ['nullable', 'date_format:H:i:s'],
            'work_hours' => ['nullable', 'numeric'],
            'status' => ['nullable', 'in:Present,Absent,Late,Leave,Holiday'],
            'is_overtime' => ['nullable', 'boolean'],
            'needs_correction' => ['nullable', 'boolean'],
            'marked_by' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
