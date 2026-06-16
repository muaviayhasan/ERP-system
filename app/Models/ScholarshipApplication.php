<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScholarshipApplication extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'requested_discount_percent' => 'decimal:2',
            'requested_value' => 'decimal:2',
            'original_fee' => 'decimal:2',
            'final_payable' => 'decimal:2',
            'cgpa' => 'decimal:2',
            'documents_count' => 'integer',
            'gpa_check_passed' => 'boolean',
            'policy_compliance_passed' => 'boolean',
            'no_duplicate_passed' => 'boolean',
            'application_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ScholarshipApplicationDocument::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ScholarshipApplicationLog::class);
    }
}
