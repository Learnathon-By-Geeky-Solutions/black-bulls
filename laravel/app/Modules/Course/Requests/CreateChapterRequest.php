<?php

namespace App\Modules\Course\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateChapterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_section_id' => 'required|exists:course_sections,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'course_section_id.required' => 'Course section is required',
            'course_section_id.exists' => 'Selected course section does not exist',
            'title.required' => 'Chapter title is required',
            'title.max' => 'Chapter title cannot exceed 255 characters',
            'order.required' => 'Chapter order is required',
            'order.integer' => 'Order must be a whole number',
            'order.min' => 'Order must be at least 0',
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
