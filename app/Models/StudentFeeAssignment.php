<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentFeeAssignment extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'scholarship_amount' => 'decimal:2',
            'total_fee' => 'decimal:2',
            'final_payable' => 'decimal:2',
            'total_paid' => 'decimal:2',
            'total_pending' => 'decimal:2',
            'next_due_date' => 'date',
            'late_fee_enabled' => 'boolean',
            'email_notifications_enabled' => 'boolean',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function feeStructure(): BelongsTo
    {
        return $this->belongsTo(FeeStructure::class);
    }

    public function feePlan(): BelongsTo
    {
        return $this->belongsTo(FeePlan::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function feeInstallments(): HasMany
    {
        return $this->hasMany(FeeInstallment::class);
    }

    public function feePayments(): HasMany
    {
        return $this->hasMany(FeePayment::class);
    }

    public function feeLedgerEntries(): HasMany
    {
        return $this->hasMany(FeeLedgerEntry::class);
    }
}
