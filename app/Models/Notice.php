<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notice extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'audience' => 'array',
            'publish_date' => 'date',
            'require_acknowledgment' => 'boolean',
        ];
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function acknowledgments(): HasMany
    {
        return $this->hasMany(NoticeAcknowledgment::class);
    }
}
