<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyMaterial extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'download_count' => 'integer',
            'view_count' => 'integer',
            'is_active' => 'boolean',
            'published_at' => 'date',
        ];
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function folder()
    {
        return $this->belongsTo(StudyMaterialFolder::class, 'folder_id');
    }

    public function uploadedBy()
    {
        return $this->belongsTo(Teacher::class, 'uploaded_by');
    }
}
