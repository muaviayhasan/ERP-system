<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'link_fee_structure' => 'boolean',
            'auto_roll_attendance' => 'boolean',
            'fees_configured' => 'boolean',
            'exams_configured' => 'boolean',
            'attendance_enabled' => 'boolean',
            'prevent_date_overlap' => 'boolean',
        ];
    }

    public function semesters(): HasMany
    {
        return $this->hasMany(Semester::class);
    }

    public function academicSettings(): HasMany
    {
        return $this->hasMany(AcademicSetting::class);
    }

    public function campuses(): BelongsToMany
    {
        return $this->belongsToMany(Campus::class, 'academic_year_campus');
    }
}
