<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_code' => ['required', 'string', 'max:255', 'unique:students,student_code'],
            'roll_number' => ['nullable', 'integer'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'full_name' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other'],
            'cnic' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'photo_url' => ['nullable', 'string', 'max:2048'],
            'institute_type' => ['nullable', 'string', 'max:50'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'enrollment_session' => ['nullable', 'string', 'max:255'],
            'current_credit_hours' => ['nullable', 'integer'],
            'scholarship_type' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:active,inactive'],
            'admission_status' => ['nullable', 'in:draft,submitted,enrolled'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'current_semester_id' => ['nullable', 'integer', 'exists:semesters,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'batch_id' => ['nullable', 'integer', 'exists:batches,id'],
            'advisor_id' => ['nullable', 'integer', 'exists:teachers,id'],
        ];
    }
}
