<?php

namespace App\Modules\Content\Requests;

class CreateTranscriptRequest extends BaseTranscriptRequest
{
    public function rules(): array
    {
        return array_merge($this->getCommonRules(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
    }

    public function messages(): array
    {
        return array_merge($this->getCommonMessages(), [
            'title.required' => 'Transcript title is required',
            'content.required' => 'Transcript content is required',
        ]);
    }
}
