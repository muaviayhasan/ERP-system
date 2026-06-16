<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransportRoute extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'stops_count' => 'integer',
            'students_count' => 'integer',
            'duration_minutes' => 'integer',
            'monthly_fee' => 'decimal:2',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function routeStops(): HasMany
    {
        return $this->hasMany(RouteStop::class, 'route_id');
    }

    public function transportAssignments(): HasMany
    {
        return $this->hasMany(TransportAssignment::class, 'route_id');
    }
}
