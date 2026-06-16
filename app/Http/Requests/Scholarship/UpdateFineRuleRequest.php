<?php

namespace App\Http\Requests\Scholarship;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFineRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'type' => ['sometimes', 'required', 'in:library,discipline,attendance,attire'],
            'level' => ['nullable', 'string', 'max:255'],
            'calculation_method' => ['nullable', 'in:fixed,per_day,percentage_of_fee'],
            'amount' => ['sometimes', 'required', 'numeric'],
            'grace_period_days' => ['nullable', 'integer'],
            'enable_max_cap' => ['nullable', 'boolean'],
            'max_cap_amount' => ['nullable', 'numeric'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }
}
