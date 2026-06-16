<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campus extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'founded_year' => 'integer',
            'enable_online_admissions' => 'boolean',
            'centralized_fee_collection' => 'boolean',
            'hostel_management' => 'boolean',
        ];
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function schoolClasses(): HasMany
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    public function semesters(): HasMany
    {
        return $this->hasMany(Semester::class);
    }

    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'campus_program');
    }

    public function departmentsPivot(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'campus_department');
    }

    public function academicYears(): BelongsToMany
    {
        return $this->belongsToMany(AcademicYear::class, 'academic_year_campus');
    }
}
