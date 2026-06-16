<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarksEntrySession extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'total_students' => 'integer',
            'marks_entered_count' => 'integer',
            'pending_count' => 'integer',
            'hod_review_required' => 'boolean',
            'submitted_for_approval' => 'boolean',
            'auto_publish_on_release' => 'boolean',
            'highest_mark' => 'decimal:2',
            'average_mark' => 'decimal:2',
            'lowest_mark' => 'decimal:2',
            'last_synced_at' => 'datetime',
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

    public function evaluator()
    {
        return $this->belongsTo(Teacher::class, 'evaluator_id');
    }
}
