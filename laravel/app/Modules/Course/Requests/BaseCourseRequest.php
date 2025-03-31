<?php

namespace App\Modules\Course\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Common\Traits\ValidationTrait;

abstract class BaseCourseRequest extends FormRequest
{
    use ValidationTrait;

    protected function getCommonRules(): array
    {
        return [
            'title' => 'string|max:255',
            'description' => 'string',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'is_approved' => 'boolean',
            'status' => 'in:active,inactive,draft',
            'instructor_id' => 'exists:users,id'
        ];
    }

    protected function getCommonMessages(): array
    {
        return [
            'title.string' => 'Course title must be a string',
            'title.max' => 'Course title cannot exceed 255 characters',
            'price.numeric' => 'Price must be a number',
            'price.min' => 'Price cannot be negative',
            'discount_price.numeric' => 'Discount price must be a number',
            'discount_price.min' => 'Discount price cannot be negative',
            'status.in' => 'Invalid course status. Must be one of: active, inactive, draft',
            'instructor_id.exists' => 'Selected instructor does not exist',
            'thumbnail.image' => 'Thumbnail must be a valid image',
            'thumbnail.mimes' => 'Thumbnail must be in JPEG, PNG, JPG, or GIF format',
            'thumbnail.max' => 'Thumbnail size cannot exceed 2MB',
            'is_published.boolean' => 'Publication status must be true or false',
            'is_featured.boolean' => 'Featured status must be true or false',
            'is_approved.boolean' => 'Approval status must be true or false'
        ];
    }
}
