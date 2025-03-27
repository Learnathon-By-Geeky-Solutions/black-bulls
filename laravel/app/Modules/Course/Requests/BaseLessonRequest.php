<?php

namespace App\Modules\Course\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'chapter_id.exists' => 'Selected chapter does not exist',
            'title.max' => 'Lesson title cannot exceed 255 characters',
            'order.integer' => 'Order must be a whole number',
            'order.min' => 'Order must be at least 0',
            'is_published.boolean' => 'Publication status must be true or false',
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