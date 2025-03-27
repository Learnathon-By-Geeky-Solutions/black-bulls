<?php

namespace App\Modules\Course\Requests;

class CreateCourseRequest extends BaseCourseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge(
            $this->getCommonRules(),
            [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'duration' => 'required|integer|min:1',
                'status' => 'required|in:draft,published',
                'category_id' => 'required|exists:categories,id',
                'thumbnail' => 'required|string|max:255',
                'level' => 'required|in:beginner,intermediate,advanced',
                'language' => 'required|string|max:50',
                'what_you_will_learn' => 'required|string',
                'target_audience' => 'required|string',
            ]
        );
    }

    public function messages(): array
    {
        return array_merge(
            $this->getCommonMessages(),
            [
                'title.required' => 'Course title is required',
                'description.required' => 'Course description is required',
                'price.required' => 'Course price is required',
                'duration.required' => 'Course duration is required',
                'status.required' => 'Course status is required',
                'category_id.required' => 'Category is required',
                'thumbnail.required' => 'Course thumbnail is required',
                'level.required' => 'Course level is required',
                'language.required' => 'Course language is required',
                'what_you_will_learn.required' => 'Learning outcomes are required',
                'target_audience.required' => 'Target audience is required',
            ]
        );
    }
}
