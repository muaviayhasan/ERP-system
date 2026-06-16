<?php

namespace App\Http\Requests\Fee;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeeCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:fee_categories,code'],
            'code_assignment' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'fee_type' => ['required', 'in:one_time,monthly,annual,semester_based,quarterly'],
            'default_amount' => ['nullable', 'numeric'],
            'currency' => ['nullable', 'string', 'max:255'],
            'applies_to_school' => ['nullable', 'boolean'],
            'applies_to_college' => ['nullable', 'boolean'],
            'applies_to_university' => ['nullable', 'boolean'],
            'late_fee_enabled' => ['nullable', 'boolean'],
            'late_fee_type' => ['nullable', 'string', 'max:255'],
            'late_fee_amount' => ['nullable', 'numeric'],
            'grace_period_days' => ['nullable', 'integer'],
            'tax_applicable' => ['nullable', 'boolean'],
            'tax_percentage' => ['nullable', 'numeric'],
            'scholarship_eligible' => ['nullable', 'boolean'],
            'refundable' => ['nullable', 'boolean'],
            'auto_generate_on_admission' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }
}
