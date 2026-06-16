<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SalaryPayment extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'basic' => 'decimal:2',
            'allowances' => 'decimal:2',
            'overtime_bonus' => 'decimal:2',
            'deductions' => 'decimal:2',
            'tax_deducted' => 'decimal:2',
            'net_salary' => 'decimal:2',
            'processed_at' => 'datetime',
        ];
    }

    public function employee(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'employee_type', 'employee_id');
    }

    public function salaryStructure(): BelongsTo
    {
        return $this->belongsTo(SalaryStructure::class);
    }
}
