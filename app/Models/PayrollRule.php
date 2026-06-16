<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollRule extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'config' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
