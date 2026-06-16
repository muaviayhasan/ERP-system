<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPromotion extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'attendance_percentage' => 'decimal:2',
            'gpa' => 'decimal:2',
            'fee_due_amount' => 'decimal:2',
            'manual_override' => 'boolean',
            'promoted' => 'boolean',
            'promoted_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function promotionBatch(): BelongsTo
    {
        return $this->belongsTo(StudentPromotionBatch::class, 'promotion_batch_id');
    }

    public function fromAcademicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'from_academic_year_id');
    }

    public function toAcademicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'to_academic_year_id');
    }

    public function fromSemester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'from_semester_id');
    }

    public function toSemester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'to_semester_id');
    }

    public function toProgram(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'to_program_id');
    }

    public function toSection(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'to_section_id');
    }

    public function toBatch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'to_batch_id');
    }

    public function toCampus(): BelongsTo
    {
        return $this->belongsTo(Campus::class, 'to_campus_id');
    }

    public function promotedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'promoted_by');
    }
}
