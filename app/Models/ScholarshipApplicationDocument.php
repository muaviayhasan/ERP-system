<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScholarshipApplicationDocument extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [];
    }

    public function scholarshipApplication(): BelongsTo
    {
        return $this->belongsTo(ScholarshipApplication::class);
    }
}
