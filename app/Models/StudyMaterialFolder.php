<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyMaterialFolder extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [];
    }

    public function parent()
    {
        return $this->belongsTo(StudyMaterialFolder::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(StudyMaterialFolder::class, 'parent_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function studyMaterials()
    {
        return $this->hasMany(StudyMaterial::class, 'folder_id');
    }
}
