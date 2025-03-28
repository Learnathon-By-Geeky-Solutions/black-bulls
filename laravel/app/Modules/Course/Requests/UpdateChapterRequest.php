<?php

namespace App\Modules\Course\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChapterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_section_id' => 'sometimes|exists:course_sections,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'order' => 'sometimes|integer|min:0',
            'is_published' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'course_section_id.exists' => 'Selected course section does not exist',
            'title.string' => 'Chapter title must be a string',
            'title.max' => 'Chapter title cannot exceed 255 characters',
            'order.integer' => 'Order must be a whole number',
            'order.min' => 'Order must be at least 0',
            'is_published.boolean' => 'Publication status must be true or false',
        ];
    }
}
