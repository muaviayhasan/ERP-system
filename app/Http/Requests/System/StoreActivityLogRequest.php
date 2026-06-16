<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'audit_ref' => ['nullable', 'string', 'max:255', 'unique:activity_logs,audit_ref'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'user_name' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'module' => ['required', 'string', 'max:255'],
            'action' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'changes' => ['nullable', 'array'],
            'ip_address' => ['nullable', 'string', 'max:255'],
            'device' => ['nullable', 'string', 'max:255'],
            'protocol' => ['nullable', 'string', 'max:255'],
            'origin_id' => ['nullable', 'string', 'max:255'],
            'mfa_status' => ['nullable', 'string', 'max:255'],
            'geo_lat' => ['nullable', 'numeric'],
            'geo_lng' => ['nullable', 'numeric'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }
}
