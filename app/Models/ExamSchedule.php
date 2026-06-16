<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'exam_date' => 'date',
            'duration_hours' => 'decimal:2',
            'has_conflict' => 'boolean',
        ];
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function invigilator()
    {
        return $this->belongsTo(Teacher::class, 'invigilator_id');
    }

    public function examScheduleConflicts()
    {
        return $this->hasMany(ExamScheduleConflict::class);
    }
}
