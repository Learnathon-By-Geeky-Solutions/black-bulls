<?php

namespace App\Modules\Course\Requests;

class CreateLessonRequest extends BaseLessonRequest
{
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
        return array_merge(parent::messages(), [
            'chapter_id.required' => 'Chapter is required',
            'title.required' => 'Lesson title is required',
            'order.required' => 'Lesson order is required',
            'is_published.required' => 'Publication status is required',
        ]);
    }
}
