<?php

namespace App\Modules\Course\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Common\Traits\ValidationTrait;

abstract class BaseCategoryRequest extends FormRequest
{
    use ValidationTrait;

    protected const REQUIRED_STRING = 'required|string';
    protected const REQUIRED_BOOLEAN = 'required|boolean';
    protected const NULLABLE_STRING = 'nullable|string';
    protected const NULLABLE_BOOLEAN = 'nullable|boolean';
    protected const IMAGE_RULES = 'image|mimes:jpeg,png,jpg,gif|max:2048';

    protected function getCommonRules(): array
    {
        return [
            'name' => 'string|max:255',
            'description' => 'string',
            'image' => self::IMAGE_RULES,
            'is_active' => 'boolean',
        ];
    }

    protected function getCommonMessages(): array
    {
        return [
            'name.string' => 'Category name must be a string',
            'name.max' => 'Category name cannot exceed 255 characters',
            'description.string' => 'Description must be a string',
            'image.image' => 'Image must be a valid image',
            'image.mimes' => 'Image must be in JPEG, PNG, JPG, or GIF format',
            'image.max' => 'Image size cannot exceed 2MB',
            'is_active.boolean' => 'Active status must be true or false'
        ];
    }
}
