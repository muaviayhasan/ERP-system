<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultCardLine extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'max_marks' => 'integer',
            'marks_obtained' => 'decimal:2',
        ];
    }

    public function resultCard()
    {
        return $this->belongsTo(ResultCard::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
