<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Batch extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'weekly_days' => 'array',
            'max_students' => 'integer',
            'allow_waitlist' => 'boolean',
            'installments_allowed' => 'boolean',
            'open_for_admissions' => 'boolean',
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

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function primaryInstructor(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'primary_instructor_id');
    }

    public function feePlan(): BelongsTo
    {
        return $this->belongsTo(FeePlan::class, 'fee_plan_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'batch_student');
    }
}
