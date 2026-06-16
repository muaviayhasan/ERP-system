<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Section extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'max_capacity' => 'integer',
            'current_enrollment' => 'integer',
            'enable_waitlist' => 'boolean',
            'is_active' => 'boolean',
            'allow_admissions' => 'boolean',
            'lock_structure' => 'boolean',
        ];
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function classTeacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'class_teacher_id');
    }
}
