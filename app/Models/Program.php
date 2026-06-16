<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'multi_department_access' => 'boolean',
            'total_years' => 'decimal:1',
            'total_semesters' => 'integer',
            'total_credits' => 'integer',
            'allow_admissions' => 'boolean',
            'lock_structure' => 'boolean',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function coordinatorUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordinator_user_id');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    public function semesters(): HasMany
    {
        return $this->hasMany(Semester::class);
    }

    public function campuses(): BelongsToMany
    {
        return $this->belongsToMany(Campus::class, 'campus_program');
    }
}
