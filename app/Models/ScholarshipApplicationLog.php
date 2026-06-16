<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScholarshipApplicationLog extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'performed_at' => 'datetime',
        ];
    }

    public function scholarshipApplication(): BelongsTo
    {
        return $this->belongsTo(ScholarshipApplication::class);
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
