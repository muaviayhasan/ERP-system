<?php

namespace App\Http\Requests\Scholarship;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScholarshipApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
            'scholarship_id' => ['nullable', 'integer', 'exists:scholarships,id'],
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'semester_id' => ['nullable', 'integer', 'exists:semesters,id'],
            'institute' => ['nullable', 'string', 'max:255'],
            'type' => ['sometimes', 'required', 'string', 'max:255'],
            'requested_discount_percent' => ['nullable', 'numeric'],
            'requested_value' => ['nullable', 'numeric'],
            'original_fee' => ['nullable', 'numeric'],
            'final_payable' => ['nullable', 'numeric'],
            'reason' => ['nullable', 'string'],
            'cgpa' => ['nullable', 'numeric'],
            'documents_count' => ['nullable', 'integer'],
            'gpa_check_passed' => ['nullable', 'boolean'],
            'policy_compliance_passed' => ['nullable', 'boolean'],
            'no_duplicate_passed' => ['nullable', 'boolean'],
            'priority' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:pending,under_review,approved,rejected,changes_requested'],
            'decision_notes' => ['nullable', 'string'],
            'reviewed_by' => ['nullable', 'integer', 'exists:users,id'],
            'application_date' => ['sometimes', 'required', 'date'],
        ];
    }
}
