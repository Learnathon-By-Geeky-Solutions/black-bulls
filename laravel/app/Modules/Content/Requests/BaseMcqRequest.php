<?php

namespace App\Modules\Content\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Common\Traits\ValidationTrait;

class BaseMcqRequest extends FormRequest
{
    use ValidationTrait;

    protected function getCommonRules(): array
    {
        $mcqableType = $this->input('mcqable_type');
        $existsRule = $mcqableType ? "exists:{$mcqableType},id" : '';

        return [
            'mcqable_type' => 'required|string|in:lessons,chapters,courses',
            'mcqable_id' => 'required|integer|' . $existsRule,
            'is_published' => 'nullable|boolean',
            'points' => 'nullable|integer|min:1',
        ];
    }

    protected function getCommonMessages(): array
    {
        return [
            'mcqable_type.required' => 'MCQ type is required',
            'mcqable_type.in' => 'Invalid MCQ type. Must be one of: lessons, chapters, courses',
            'mcqable_id.required' => 'MCQ parent ID is required',
            'mcqable_id.exists' => 'Selected MCQ parent does not exist',
            'is_published.boolean' => 'Publication status must be true or false',
            'points.integer' => 'Points must be a whole number',
            'points.min' => 'Points must be at least 1',
        ];
    }
}
