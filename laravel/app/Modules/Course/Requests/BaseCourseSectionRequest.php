<?php

namespace App\Modules\Course\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseCourseSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function getCommonRules(): array
    {
        return [
            'course_id' => 'exists:courses,id',
            'title' => 'string|max:255',
            'description' => 'string',
            'order' => 'integer|min:1',
            'is_published' => 'boolean',
        ];
    }

    protected function getCommonMessages(): array
    {
        return [
            'course_id.exists' => 'Selected course does not exist',
            'title.string' => 'Section title must be a string',
            'title.max' => 'Section title cannot exceed 255 characters',
            'order.integer' => 'Order must be a whole number',
            'order.min' => 'Order must be at least 1',
            'is_published.boolean' => 'Publication status must be true or false',
        ];
    }
} 