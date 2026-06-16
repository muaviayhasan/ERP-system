<?php

namespace App\Http\Requests\Scholarship;

use Illuminate\Foundation\Http\FormRequest;

class StoreFineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
            'fine_rule_id' => ['nullable', 'integer', 'exists:fine_rules,id'],
            'reason' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric'],
            'date_applied' => ['required', 'date'],
            'status' => ['nullable', 'in:pending,overdue,paid,waived'],
            'collected_by' => ['nullable', 'integer', 'exists:users,id'],
            'collected_at' => ['nullable', 'date'],
            'waived_by' => ['nullable', 'integer', 'exists:users,id'],
            'waived_at' => ['nullable', 'date'],
        ];
    }
}
