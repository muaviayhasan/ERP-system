<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamScheduleConflict extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_resolved' => 'boolean',
        ];
    }

    public function examSchedule()
    {
        return $this->belongsTo(ExamSchedule::class);
    }
}
