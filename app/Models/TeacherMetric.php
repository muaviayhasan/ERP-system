<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherMetric extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'attendance_rate' => 'decimal:2',
            'student_rating' => 'decimal:2',
        ];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
