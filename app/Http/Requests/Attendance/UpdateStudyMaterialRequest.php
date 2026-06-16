<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudyMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['sometimes', 'required', 'in:pdf,video,link,doc'],
            'subject_id' => ['nullable', 'integer', 'exists:subjects,id'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'folder_id' => ['nullable', 'integer', 'exists:study_material_folders,id'],
            'uploaded_by' => ['nullable', 'integer', 'exists:teachers,id'],
            'file_path' => ['nullable', 'string', 'max:255'],
            'external_url' => ['nullable', 'string', 'max:255'],
            'file_size' => ['nullable', 'integer'],
            'download_count' => ['nullable', 'integer'],
            'view_count' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
