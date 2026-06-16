<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeePlan extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'number_of_payments' => 'integer',
            'start_date' => 'date',
        ];
    }

    public function feeStructure(): BelongsTo
    {
        return $this->belongsTo(FeeStructure::class);
    }

    public function studentFeeAssignments(): HasMany
    {
        return $this->hasMany(StudentFeeAssignment::class);
    }
}
