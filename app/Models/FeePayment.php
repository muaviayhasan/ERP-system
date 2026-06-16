<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeePayment extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'amount_payable' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'balance' => 'decimal:2',
            'late_fee_amount' => 'decimal:2',
            'auto_allocate_installments' => 'boolean',
            'paid_at' => 'datetime',
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

    public function feeInstallment(): BelongsTo
    {
        return $this->belongsTo(FeeInstallment::class);
    }

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(FeeReceipt::class, 'receipt_id');
    }

    public function collectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function feeReceipts(): HasMany
    {
        return $this->hasMany(FeeReceipt::class, 'fee_payment_id');
    }
}
