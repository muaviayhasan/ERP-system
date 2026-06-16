<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeeStructure extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'total_fee' => 'decimal:2',
            'scholarship_available' => 'boolean',
            'installments_enabled' => 'boolean',
            'installment_count' => 'integer',
            'billing_day_of_month' => 'integer',
            'students_count' => 'integer',
        ];
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function feeStructureComponents(): HasMany
    {
        return $this->hasMany(FeeStructureComponent::class);
    }

    public function feePlans(): HasMany
    {
        return $this->hasMany(FeePlan::class);
    }

    public function studentFeeAssignments(): HasMany
    {
        return $this->hasMany(StudentFeeAssignment::class);
    }
}
