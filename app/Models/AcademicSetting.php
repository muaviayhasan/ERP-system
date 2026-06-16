<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicSetting extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'pass_mark_threshold' => 'integer',
            'min_attendance_required' => 'integer',
            'attendance_grace_minutes' => 'integer',
            'attendance_warning_threshold' => 'integer',
            'attendance_critical_threshold' => 'integer',
            'weight_final' => 'integer',
            'weight_midterm' => 'integer',
            'weight_assignments_lab' => 'integer',
            'weight_quizzes' => 'integer',
            'approval_workflow' => 'array',
            'promotion_enabled' => 'boolean',
            'promotion_min_gpa' => 'decimal:2',
            'promotion_max_fail_subjects' => 'integer',
            'university_mode_enabled' => 'boolean',
            'min_credit_load' => 'integer',
            'max_credit_load' => 'integer',
        ];
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
