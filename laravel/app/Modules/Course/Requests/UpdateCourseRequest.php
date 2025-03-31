<?php

namespace App\Modules\Course\Requests;

class UpdateCourseRequest extends BaseCourseRequest
{
    private const NULLABLE_STRING = 'sometimes|string';
    private const NULLABLE_BOOLEAN = 'sometimes|boolean';

    public function rules(): array
    {
        return array_merge($this->getCommonRules(), [
            'title' => self::NULLABLE_STRING . '|max:255',
            'description' => self::NULLABLE_STRING,
            'price' => 'sometimes|numeric|min:0',
            'thumbnail' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|in:active,inactive,draft',
            'instructor_id' => 'sometimes|exists:users,id',
            'is_published' => self::NULLABLE_BOOLEAN,
            'is_featured' => self::NULLABLE_BOOLEAN,
            'is_approved' => self::NULLABLE_BOOLEAN
        ]);
    }

    public function messages(): array
    {
        return $this->getCommonMessages();
    }
}
