<?php

namespace App\Modules\Course\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function getCommonRules(): array
    {
        return [
            'title' => 'string|max:255',
            'description' => 'string',
            'price' => 'numeric|min:0',
            'duration' => 'integer|min:1',
            'status' => 'in:draft,published',
            'category_id' => 'exists:categories,id',
            'thumbnail' => 'string|max:255',
            'level' => 'in:beginner,intermediate,advanced',
            'language' => 'string|max:50',
            'prerequisites' => 'nullable|string',
            'what_you_will_learn' => 'string',
            'target_audience' => 'string',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50'
        ];
    }

    protected function getCommonMessages(): array
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

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'is_success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422));
    }
}
