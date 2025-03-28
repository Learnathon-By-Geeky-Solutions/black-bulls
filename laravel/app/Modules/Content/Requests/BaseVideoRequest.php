<?php

namespace App\Modules\Content\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Common\Traits\ValidationTrait;

class BaseVideoRequest extends FormRequest
{
    use ValidationTrait;

    public function messages(): array
    {
        return [
            'title.max' => 'Video title cannot exceed 255 characters',
            'url.required' => 'Video URL is required',
            'url.url' => 'Please provide a valid video URL',
            'duration.integer' => 'Duration must be a whole number',
            'duration.min' => 'Duration must be at least 0',
            'is_published.boolean' => 'Publication status must be true or false',
        ];
    }
}
