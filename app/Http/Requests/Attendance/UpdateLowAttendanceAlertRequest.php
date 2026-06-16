<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLowAttendanceAlertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'attendance_percentage' => ['sometimes', 'required', 'numeric'],
            'required_percentage' => ['nullable', 'numeric'],
            'risk_level' => ['sometimes', 'required', 'in:critical,high,moderate'],
            'absents_count' => ['nullable', 'integer'],
            'lates_count' => ['nullable', 'integer'],
            'trend' => ['nullable', 'numeric'],
            'scholarship_status' => ['nullable', 'string', 'max:255'],
            'exam_eligibility_restricted' => ['nullable', 'boolean'],
            'sms_warning_sent' => ['nullable', 'boolean'],
            'guardian_notified' => ['nullable', 'boolean'],
            'last_warning_sent_at' => ['nullable', 'date'],
        ];
    }
}
