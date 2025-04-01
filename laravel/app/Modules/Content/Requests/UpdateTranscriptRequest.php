<?php

namespace App\Modules\Content\Requests;

class UpdateTranscriptRequest extends BaseTranscriptRequest
{
    public function rules(): array
    {
        return array_merge($this->getCommonRules(), [
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ]);
    }

    public function messages(): array
    {
        return $this->getCommonMessages();
    }
}
