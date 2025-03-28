<?php

namespace App\Modules\Content\Requests;

class CreateVideoRequest extends BaseVideoRequest
{
    public function rules(): array
    {
        $videoableType = $this->input('videoable_type');
        $existsRule = $videoableType ? "exists:{$videoableType},id" : '';

        return [
            'videoable_type' => 'nullable|string|in:lessons,chapters,courses',
            'videoable_id' => 'nullable|integer|' . $existsRule,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|file|mimes:mp4,mov,ogg,qt|max:102400', // 100MB max
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            'duration' => 'required|integer|min:0',
            'is_published' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'title.required' => 'Video title is required',
            'duration.required' => 'Video duration is required',
            'is_published.required' => 'Publication status is required',
            'is_published.boolean' => 'Publication status must be true or false',
            'videoable_type.in' => 'Invalid videoable type',
            'videoable_id.exists' => 'Selected videoable does not exist',
            'url.required' => 'Video file is required',
            'url.file' => 'Video must be a valid file',
            'url.mimes' => 'Video must be in MP4, MOV, OGG, or QT format',
            'url.max' => 'Video size cannot exceed 100MB',
            'thumbnail.image' => 'Thumbnail must be a valid image',
            'thumbnail.mimes' => 'Thumbnail must be in JPEG, PNG, JPG, or GIF format',
            'thumbnail.max' => 'Thumbnail size cannot exceed 2MB',
        ]);
    }
}
