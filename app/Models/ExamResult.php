<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'marks_obtained' => 'decimal:2',
            'total_marks' => 'integer',
            'percentage' => 'decimal:2',
            'is_flagged' => 'boolean',
        ];
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(Teacher::class, 'evaluator_id');
    }
}
