<?php

namespace App\Modules\Course\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends BaseCategoryRequest
{
    public function rules(): array
    {
        return array_merge($this->getCommonRules(), [
            'name' => self::REQUIRED_STRING . '|max:255',
            'description' => self::REQUIRED_STRING,
            'image' => 'required|' . self::IMAGE_RULES,
            'is_active' => self::REQUIRED_BOOLEAN,
        ]);
    }

    public function messages(): array
    {
        return array_merge($this->getCommonMessages(), [
            'name.required' => 'Category name is required',
            'description.required' => 'Category description is required',
            'image.required' => 'Category image is required',
            'is_active.required' => 'Active status is required'
        ]);
    }
}
