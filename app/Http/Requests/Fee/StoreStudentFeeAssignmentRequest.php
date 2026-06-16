<?php

namespace App\Http\Requests\Fee;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentFeeAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
            'fee_structure_id' => ['nullable', 'integer', 'exists:fee_structures,id'],
            'fee_plan_id' => ['nullable', 'integer', 'exists:fee_plans,id'],
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'semester_id' => ['nullable', 'integer', 'exists:semesters,id'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'scholarship_id' => ['nullable', 'integer', 'exists:scholarships,id'],
            'scholarship_amount' => ['nullable', 'numeric'],
            'total_fee' => ['nullable', 'numeric'],
            'final_payable' => ['nullable', 'numeric'],
            'total_paid' => ['nullable', 'numeric'],
            'total_pending' => ['nullable', 'numeric'],
            'next_due_date' => ['nullable', 'date'],
            'late_fee_enabled' => ['nullable', 'boolean'],
            'email_notifications_enabled' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }
}
