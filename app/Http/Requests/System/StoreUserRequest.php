<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'avatar' => ['nullable', 'string', 'max:2048'],
            'phone' => ['nullable', 'string', 'max:50'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:255'],
            'residential_address' => ['nullable', 'string'],
            'employee_id' => ['nullable', 'string', 'max:255'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'employee_tier' => ['nullable', 'string', 'max:255'],
            'reporting_manager_id' => ['nullable', 'integer', 'exists:users,id'],
            'joining_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'max:255'],
            'two_factor_enabled' => ['nullable', 'boolean'],
            'preferred_language' => ['nullable', 'string', 'max:255'],
            'dark_mode' => ['nullable', 'boolean'],
            'email_alerts' => ['nullable', 'boolean'],
            'sms_notifications' => ['nullable', 'boolean'],
            'system_alerts' => ['nullable', 'boolean'],
            'oauth_provider' => ['nullable', 'string', 'max:255'],
            'oauth_id' => ['nullable', 'string', 'max:255'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string'],
        ];
    }
}
