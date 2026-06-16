<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceAlertRule extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'threshold_percentage' => 'decimal:2',
            'is_enabled' => 'boolean',
        ];
    }
}
