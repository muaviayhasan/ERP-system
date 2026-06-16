<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuardianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'relationship' => ['nullable', 'in:father,mother,guardian,sibling'],
            'cnic' => ['nullable', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'residential_address' => ['nullable', 'string'],
            'photo_url' => ['nullable', 'string', 'max:2048'],
            'is_primary_fee_payer' => ['nullable', 'boolean'],
            'is_emergency_authorized' => ['nullable', 'boolean'],
            'phone_verified' => ['nullable', 'boolean'],
            'status' => ['nullable', 'in:active,inactive'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
