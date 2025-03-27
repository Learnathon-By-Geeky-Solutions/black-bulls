<?php

namespace App\Modules\Course\Requests;

class UpdateCourseRequest extends BaseCourseRequest
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
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'price' => 'sometimes|numeric|min:0',
                'duration' => 'sometimes|integer|min:1',
                'status' => 'sometimes|in:draft,published',
                'category_id' => 'sometimes|exists:categories,id',
                'thumbnail' => 'sometimes|string|max:255',
                'level' => 'sometimes|in:beginner,intermediate,advanced',
                'language' => 'sometimes|string|max:50',
                'what_you_will_learn' => 'sometimes|string',
                'target_audience' => 'sometimes|string',
            ]
        );
    }

    public function messages(): array
    {
        return $this->getCommonMessages();
    }
}
