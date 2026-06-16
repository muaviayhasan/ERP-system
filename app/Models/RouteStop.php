<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RouteStop extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'sequence' => 'integer',
            'arrival_time' => 'datetime:H:i:s',
            'stop_duration_minutes' => 'integer',
        ];
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(TransportRoute::class, 'route_id');
    }
}
