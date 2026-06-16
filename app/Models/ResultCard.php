<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultCard extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'cumulative_gpa' => 'decimal:2',
            'rank_in_class' => 'integer',
            'class_size' => 'integer',
            'is_published' => 'boolean',
            'is_locked' => 'boolean',
            'allow_reevaluation' => 'boolean',
            'attendance_percent' => 'decimal:2',
            'generated_at' => 'datetime',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function classTeacher()
    {
        return $this->belongsTo(Teacher::class, 'class_teacher_id');
    }

    public function registrar()
    {
        return $this->belongsTo(User::class, 'registrar_id');
    }

    public function resultCardLines()
    {
        return $this->hasMany(ResultCardLine::class);
    }
}
