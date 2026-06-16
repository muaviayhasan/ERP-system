<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LowAttendanceAlert extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'attendance_percentage' => 'decimal:2',
            'required_percentage' => 'decimal:2',
            'trend' => 'decimal:2',
            'exam_eligibility_restricted' => 'boolean',
            'sms_warning_sent' => 'boolean',
            'guardian_notified' => 'boolean',
            'last_warning_sent_at' => 'datetime',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
