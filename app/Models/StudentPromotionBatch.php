<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentPromotionBatch extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'min_attendance_rule' => 'boolean',
            'clear_fee_arrears_rule' => 'boolean',
            'manual_override_allowed' => 'boolean',
            'total_students' => 'integer',
            'passed_count' => 'integer',
            'failed_count' => 'integer',
            'conditional_count' => 'integer',
            'min_attendance_threshold' => 'integer',
            'executed_at' => 'datetime',
        ];
    }

    public function fromAcademicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'from_academic_year_id');
    }

    public function toAcademicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'to_academic_year_id');
    }

    public function sourceProgram(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'source_program_id');
    }

    public function toProgram(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'to_program_id');
    }

    public function toSection(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'to_section_id');
    }

    public function toCampus(): BelongsTo
    {
        return $this->belongsTo(Campus::class, 'to_campus_id');
    }

    public function executedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'executed_by');
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(StudentPromotion::class, 'promotion_batch_id');
    }
}
