<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultReevaluation extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'requested_at' => 'datetime',
        ];
    }

    public function resultCard()
    {
        return $this->belongsTo(ResultCard::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function recheckedBy()
    {
        return $this->belongsTo(Teacher::class, 'rechecked_by');
    }
}
