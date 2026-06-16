<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:campuses,code'],
            'institution_type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'street_address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state_province' => ['nullable', 'string', 'max:255'],
            'founded_year' => ['nullable', 'integer'],
            'status' => ['nullable', 'string', 'max:255'],
            'enable_online_admissions' => ['nullable', 'boolean'],
            'centralized_fee_collection' => ['nullable', 'boolean'],
            'hostel_management' => ['nullable', 'boolean'],
            'primary_bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:255'],
            'bank_swift_code' => ['nullable', 'string', 'max:255'],
        ];
    }
}
