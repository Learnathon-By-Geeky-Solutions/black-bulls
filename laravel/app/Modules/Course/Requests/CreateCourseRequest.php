<?php

namespace App\Modules\Course\Requests;

class CreateCourseRequest extends BaseCourseRequest
{
    private const REQUIRED_STRING = 'required|string';
    private const REQUIRED_BOOLEAN = 'required|boolean';

    public function rules(): array
    {
        return array_merge($this->getCommonRules(), [
            'title' => self::REQUIRED_STRING . '|max:255',
            'description' => self::REQUIRED_STRING,
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive,draft',
            'instructor_id' => 'required|exists:users,id',
            'is_published' => self::REQUIRED_BOOLEAN,
            'is_featured' => self::REQUIRED_BOOLEAN,
            'is_approved' => self::REQUIRED_BOOLEAN
        ]);
    }

    public function messages(): array
    {
        return array_merge($this->getCommonMessages(), [
            'title.required' => 'Course title is required',
            'description.required' => 'Course description is required',
            'price.required' => 'Course price is required',
            'thumbnail.required' => 'Course thumbnail is required',
            'status.required' => 'Course status is required',
            'instructor_id.required' => 'Instructor is required',
            'is_published.required' => 'Publication status is required',
            'is_featured.required' => 'Featured status is required',
            'is_approved.required' => 'Approval status is required'
        ]);
    }
}
