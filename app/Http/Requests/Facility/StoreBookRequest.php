<?php

namespace App\Http\Requests\Facility;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:255', 'unique:books,isbn'],
            'category' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'cover_image_url' => ['nullable', 'string', 'max:2048'],
            'total_copies' => ['nullable', 'integer'],
            'available_copies' => ['nullable', 'integer'],
            'availability_status' => ['nullable', 'string', 'max:255'],
            'borrow_count' => ['nullable', 'integer'],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
        ];
    }
}
