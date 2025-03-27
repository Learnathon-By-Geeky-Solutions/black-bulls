<?php

namespace App\Modules\Course\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'chapter_id' => 'required|exists:chapters,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'is_published' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'chapter_id.required' => 'Chapter is required',
            'chapter_id.exists' => 'Selected chapter does not exist',
            'title.required' => 'Lesson title is required',
            'title.max' => 'Lesson title cannot exceed 255 characters',
            'order.required' => 'Lesson order is required',
            'order.integer' => 'Order must be a whole number',
            'order.min' => 'Order must be at least 0',
            'is_published.required' => 'Publication status is required',
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
