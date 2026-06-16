<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FineRule extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'grace_period_days' => 'integer',
            'enable_max_cap' => 'boolean',
            'max_cap_amount' => 'decimal:2',
        ];
    }

    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class);
    }
}
