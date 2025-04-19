<?php

namespace App\Modules\Course\Requests;

class UpdateCourseRequest extends BaseCourseRequest
{
    private const NULLABLE_STRING = 'sometimes|string';

    public function rules(): array
    {
        return array_merge($this->getCommonRules(), [
            'title' => self::NULLABLE_STRING . '|max:255',
            'description' => self::NULLABLE_STRING,
            'price' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:active,inactive,draft',
        ]);
    }

    public function messages(): array
    {
        return $this->getCommonMessages();
    }
}
