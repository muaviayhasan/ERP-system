<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'credit_hours' => 'integer',
            'total_marks' => 'integer',
            'passing_percentage' => 'integer',
            'weight_quiz' => 'integer',
            'weight_assignment' => 'integer',
            'weight_mid' => 'integer',
            'weight_final' => 'integer',
            'is_active' => 'boolean',
            'open_enrollment' => 'boolean',
        ];
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function primaryInstructor(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'primary_instructor_id');
    }

    public function semesters(): BelongsToMany
    {
        return $this->belongsToMany(Semester::class, 'course_semester');
    }
}
