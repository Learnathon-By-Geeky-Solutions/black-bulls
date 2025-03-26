<?php

namespace App\Modules\Course\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:draft,published',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'required|string|max:255',
            'level' => 'required|in:beginner,intermediate,advanced',
            'language' => 'required|string|max:50',
            'prerequisites' => 'nullable|string',
            'what_you_will_learn' => 'required|string',
            'target_audience' => 'required|string',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Course title is required',
            'title.max' => 'Course title cannot exceed 255 characters',
            'description.required' => 'Course description is required',
            'price.required' => 'Course price is required',
            'price.numeric' => 'Price must be a number',
            'price.min' => 'Price cannot be negative',
            'duration.required' => 'Course duration is required',
            'duration.integer' => 'Duration must be a whole number',
            'duration.min' => 'Duration must be at least 1',
            'status.required' => 'Course status is required',
            'status.in' => 'Invalid course status',
            'category_id.required' => 'Category is required',
            'category_id.exists' => 'Selected category does not exist',
            'thumbnail.required' => 'Course thumbnail is required',
            'level.required' => 'Course level is required',
            'level.in' => 'Invalid course level',
            'language.required' => 'Course language is required',
            'what_you_will_learn.required' => 'Learning outcomes are required',
            'target_audience.required' => 'Target audience is required',
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
