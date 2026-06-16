<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentGpa extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'credits' => 'integer',
            'gpa' => 'decimal:2',
            'cgpa' => 'decimal:2',
            'last_calculated_at' => 'datetime',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
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

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
