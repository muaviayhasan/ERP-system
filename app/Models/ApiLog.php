<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status_code' => 'integer',
            'latency_ms' => 'integer',
            'called_at' => 'datetime',
        ];
    }
}
