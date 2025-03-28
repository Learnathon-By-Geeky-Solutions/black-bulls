<?php

namespace App\Modules\Content\Requests;

class CreateVideoRequest extends BaseVideoRequest
{
    public function rules(): array
    {
        return array_merge($this->getCommonRules(), [
            'title' => 'required|string|max:255',
            'url' => 'required|file|mimes:mp4,mov,ogg,qt|max:102400', // 100MB max
            'duration' => 'required|integer|min:0',
        ]);
    }

    public function messages(): array
    {
        return array_merge($this->getCommonMessages(), [
            'title.required' => 'Video title is required',
            'duration.required' => 'Video duration is required',
            'url.required' => 'Video file is required',
        ]);
    }
}
