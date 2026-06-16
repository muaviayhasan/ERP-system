<?php

namespace App\Http\Requests\Facility;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('book')?->id ?? $this->route('book');

        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'author' => ['sometimes', 'required', 'string', 'max:255'],
            'isbn' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('books', 'isbn')->ignore($id)],
            'category' => ['sometimes', 'required', 'string', 'max:255'],
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
