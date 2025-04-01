<?php

namespace App\Modules\Course\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends BaseCategoryRequest
{
    public function rules(): array
    {
        return array_merge($this->getCommonRules(), [
            'name' => self::NULLABLE_STRING . '|max:255',
            'description' => self::NULLABLE_STRING,
            'image' => 'nullable|' . self::IMAGE_RULES,
            'is_active' => self::NULLABLE_BOOLEAN,
        ]);
    }
}
