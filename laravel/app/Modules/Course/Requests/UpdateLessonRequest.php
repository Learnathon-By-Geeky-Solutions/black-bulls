<?php

namespace App\Modules\Course\Requests;

class UpdateLessonRequest extends BaseLessonRequest
{
    public function rules(): array
    {
        return [
            'chapter_id' => 'nullable|exists:chapters,id',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_published' => 'nullable|boolean',
        ];
    }
}
