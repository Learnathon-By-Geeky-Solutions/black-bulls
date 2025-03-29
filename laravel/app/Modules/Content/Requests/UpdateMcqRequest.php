<?php

namespace App\Modules\Content\Requests;

class UpdateMcqRequest extends BaseMcqRequest
{
    private const NULLABLE_STRING = 'nullable|string';

    public function rules(): array
    {
        return array_merge($this->getCommonRules(), [
            'question' => self::NULLABLE_STRING,
            'options' => 'nullable|array|min:2',
            'options.*' => 'required|string',
            'correct_answer' => self::NULLABLE_STRING,
            'explanation' => self::NULLABLE_STRING,
        ]);
    }

    public function messages(): array
    {
        return $this->getCommonMessages();
    }
}
