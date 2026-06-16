<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'credits' => 'decimal:1',
            'total_marks' => 'integer',
            'weight_mid' => 'integer',
            'weight_final' => 'integer',
            'prerequisites_required' => 'boolean',
            'lock_structural_changes' => 'boolean',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function primaryTeacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'primary_teacher_id');
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'class_subject');
    }
}
