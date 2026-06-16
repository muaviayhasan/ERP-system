<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeeCategory extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'default_amount' => 'decimal:2',
            'applies_to_school' => 'boolean',
            'applies_to_college' => 'boolean',
            'applies_to_university' => 'boolean',
            'late_fee_enabled' => 'boolean',
            'late_fee_amount' => 'decimal:2',
            'grace_period_days' => 'integer',
            'tax_applicable' => 'boolean',
            'tax_percentage' => 'decimal:2',
            'scholarship_eligible' => 'boolean',
            'refundable' => 'boolean',
            'auto_generate_on_admission' => 'boolean',
        ];
    }

    public function feeStructureComponents(): HasMany
    {
        return $this->hasMany(FeeStructureComponent::class);
    }
}
