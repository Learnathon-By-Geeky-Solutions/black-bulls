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
            'mcqable_type' => 'nullable|string|in:lessons,chapters,courses',
            'mcqable_id' => 'nullable|integer|' . $existsRule,
            'is_published' => 'nullable|boolean',
            'points' => 'nullable|integer|min:1',
        ];
    }

    protected function getCommonMessages(): array
    {
        return [
            'mcqable_type.in' => 'Invalid mcqable type',
            'mcqable_id.exists' => 'Selected mcqable does not exist',
            'is_published.boolean' => 'Publication status must be true or false',
            'points.integer' => 'Points must be a whole number',
            'points.min' => 'Points must be at least 1',
        ];
    }
}
