<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'total_marks' => 'integer',
            'passing_marks' => 'integer',
            'is_online' => 'boolean',
            'multi_set_papers' => 'boolean',
            'subjects_count' => 'integer',
            'students_count' => 'integer',
        ];
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function examSchedules()
    {
        return $this->hasMany(ExamSchedule::class);
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function resultCards()
    {
        return $this->hasMany(ResultCard::class);
    }
}
