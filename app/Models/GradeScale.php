<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeScale extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'min_percent' => 'decimal:2',
            'max_percent' => 'decimal:2',
            'min_gpa' => 'decimal:2',
            'max_gpa' => 'decimal:2',
            'gpa_point' => 'decimal:2',
            'is_passing' => 'boolean',
        ];
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
