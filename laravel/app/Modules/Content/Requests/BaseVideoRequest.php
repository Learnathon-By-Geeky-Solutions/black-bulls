<?php

namespace App\Modules\Content\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Common\Traits\ValidationTrait;

class BaseVideoRequest extends FormRequest
{
    use ValidationTrait;

    protected function getCommonRules(): array
    {
        $videoableType = $this->input('videoable_type');
        $existsRule = $videoableType ? "exists:{$videoableType},id" : '';

        return [
            'videoable_type' => 'nullable|string',
            'videoable_id' => 'nullable|integer|' . $existsRule,
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            'is_published' => 'nullable|boolean',
        ];
    }

    protected function getCommonMessages(): array
    {
        return [
            'title.max' => 'Video title cannot exceed 255 characters',
            'videoable_type.in' => 'Invalid videoable type',
            'videoable_id.exists' => 'Selected videoable does not exist',
            'is_published.boolean' => 'Publication status must be true or false',
            'url.file' => 'Video must be a valid file',
            'url.mimes' => 'Video must be in MP4, MOV, OGG, or QT format',
            'url.max' => 'Video size cannot exceed 100MB',
            'thumbnail.image' => 'Thumbnail must be a valid image',
            'thumbnail.mimes' => 'Thumbnail must be in JPEG, PNG, JPG, or GIF format',
            'thumbnail.max' => 'Thumbnail size cannot exceed 2MB',
            'duration.integer' => 'Duration must be a whole number',
            'duration.min' => 'Duration must be at least 0',
        ];
    }
}
