<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hostel extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'total_rooms' => 'integer',
            'occupied_rooms' => 'integer',
        ];
    }

    public function warden(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'warden_id');
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(HostelRoom::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(HostelAllocation::class);
    }
}
