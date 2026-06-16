<?php

namespace App\Http\Requests\Facility;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookIssueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'book_id' => ['nullable', 'integer', 'exists:books,id'],
            'borrower_type' => ['nullable', 'string', 'max:255'],
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
            'issued_by' => ['nullable', 'integer', 'exists:users,id'],
            'issue_date' => ['sometimes', 'required', 'date'],
            'due_date' => ['sometimes', 'required', 'date'],
            'return_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:issued,returned,overdue'],
            'fine_amount' => ['nullable', 'numeric'],
            'fine_paid' => ['nullable', 'boolean'],
            'renewal_count' => ['nullable', 'integer'],
        ];
    }
}
