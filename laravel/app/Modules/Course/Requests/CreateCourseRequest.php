<?php

namespace App\Modules\Course\Requests;

class CreateCourseRequest extends BaseCourseRequest
{
    private const REQUIRED_STRING = 'required|string';

    public function rules(): array
    {
        return array_merge($this->getCommonRules(), [
            'title' => self::REQUIRED_STRING . '|max:255',
            'description' => self::REQUIRED_STRING,
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,draft',
        ]);
    }

    public function messages(): array
    {
        return array_merge($this->getCommonMessages(), [
            'title.required' => 'Course title is required',
            'description.required' => 'Course description is required',
            'price.required' => 'Course price is required',
            'status.required' => 'Course status is required',
        ]);
    }
}
