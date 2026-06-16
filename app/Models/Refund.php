<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refund extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'total_paid' => 'decimal:2',
            'actual_due' => 'decimal:2',
            'max_eligible_refund' => 'decimal:2',
            'requested_amount' => 'decimal:2',
            'approved_amount' => 'decimal:2',
            'payment_verified' => 'boolean',
            'ledger_reconciled' => 'boolean',
            'payout_date' => 'date',
            'request_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
