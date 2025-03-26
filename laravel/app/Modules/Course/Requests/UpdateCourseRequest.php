<?php

namespace App\Modules\Course\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'duration' => 'sometimes|integer|min:1',
            'status' => 'sometimes|in:draft,published',
            'category_id' => 'sometimes|exists:categories,id',
            'thumbnail' => 'sometimes|string|max:255',
            'level' => 'sometimes|in:beginner,intermediate,advanced',
            'language' => 'sometimes|string|max:50',
            'prerequisites' => 'nullable|string',
            'what_you_will_learn' => 'sometimes|string',
            'target_audience' => 'sometimes|string',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50'
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'Course title must be a string',
            'title.max' => 'Course title cannot exceed 255 characters',
            'price.numeric' => 'Price must be a number',
            'price.min' => 'Price cannot be negative',
            'duration.integer' => 'Duration must be a whole number',
            'duration.min' => 'Duration must be at least 1',
            'status.in' => 'Invalid course status',
            'category_id.exists' => 'Selected category does not exist',
            'level.in' => 'Invalid course level',
            'language.max' => 'Language cannot exceed 50 characters',
            'tags.array' => 'Tags must be an array',
            'tags.*.string' => 'Each tag must be a string',
            'tags.*.max' => 'Each tag cannot exceed 50 characters'
        ];
    }
}
