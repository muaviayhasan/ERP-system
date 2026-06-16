<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class StoreAcademicYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'scope' => ['nullable', 'in:all_campuses,specific_campuses'],
            'status' => ['nullable', 'string', 'max:255'],
            'link_fee_structure' => ['nullable', 'boolean'],
            'auto_roll_attendance' => ['nullable', 'boolean'],
            'fees_configured' => ['nullable', 'boolean'],
            'exams_configured' => ['nullable', 'boolean'],
            'attendance_enabled' => ['nullable', 'boolean'],
            'prevent_date_overlap' => ['nullable', 'boolean'],
        ];
    }
}
