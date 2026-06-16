<?php

namespace App\Http\Requests\Scholarship;

use Illuminate\Foundation\Http\FormRequest;

class StoreScholarshipAssignmentRequest extends FormRequest
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
            'discount_amount' => ['nullable', 'numeric'],
            'status' => ['nullable', 'string', 'max:255'],
            'assigned_by' => ['nullable', 'integer', 'exists:users,id'],
            'expires_at' => ['nullable', 'date'],
        ];
    }
}
