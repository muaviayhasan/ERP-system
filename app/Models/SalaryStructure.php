<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SalaryStructure extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'basic_salary' => 'decimal:2',
            'transport_allowance' => 'decimal:2',
            'medical_allowance' => 'decimal:2',
            'housing_allowance' => 'decimal:2',
            'overtime_rate' => 'decimal:2',
            'performance_bonus' => 'decimal:2',
            'effective_from' => 'date',
        ];
    }

    public function employee(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'employee_type', 'employee_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SalaryPayment::class);
    }
}
