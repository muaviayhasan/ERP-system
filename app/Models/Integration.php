<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            // Encrypted at rest so API keys/secrets are never stored in plaintext.
            'credentials' => 'encrypted:array',
        ];
    }
}
