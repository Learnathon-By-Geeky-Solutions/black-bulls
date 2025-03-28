<?php

namespace App\Modules\Content\Requests;

class UpdateVideoRequest extends BaseVideoRequest
{
    public function rules(): array
    {
        return array_merge($this->getCommonRules(), [
            'title' => 'nullable|string|max:255',
            'url' => 'nullable|file|mimes:mp4,mov,ogg,qt|max:102400', // 100MB max
            'duration' => 'nullable|integer|min:0',
        ]);
    }

    public function messages(): array
    {
        return $this->getCommonMessages();
    }
}
