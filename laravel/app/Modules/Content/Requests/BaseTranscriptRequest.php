<?php

namespace App\Modules\Content\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Common\Traits\ValidationTrait;

class BaseTranscriptRequest extends FormRequest
{
    use ValidationTrait;

    protected function getCommonRules(): array
    {
        $transcriptableType = $this->input('transcriptable_type');
        $existsRule = $transcriptableType ? "exists:{$transcriptableType},id" : '';

        return [
            'transcriptable_type' => 'nullable|string|in:lessons,chapters,courses',
            'transcriptable_id' => 'nullable|integer|' . $existsRule,
            'is_published' => 'nullable|boolean',
            'timestamps' => 'nullable|json',
        ];
    }

    protected function getCommonMessages(): array
    {
        return [
            'title.max' => 'Transcript title cannot exceed 255 characters',
            'transcriptable_type.in' => 'Invalid transcriptable type',
            'transcriptable_id.exists' => 'Selected transcriptable does not exist',
            'is_published.boolean' => 'Publication status must be true or false',
            'timestamps.json' => 'Timestamps must be a valid JSON array',
        ];
    }
}
