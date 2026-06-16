<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PendingFee extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'amount_payable' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'amount_pending' => 'decimal:2',
            'late_fee_amount' => 'decimal:2',
            'due_date' => 'date',
            'days_overdue' => 'integer',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function studentFeeAssignment(): BelongsTo
    {
        return $this->belongsTo(StudentFeeAssignment::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function feeReminders(): HasMany
    {
        return $this->hasMany(FeeReminder::class);
    }
}
