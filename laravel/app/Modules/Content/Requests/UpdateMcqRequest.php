<?php

namespace App\Modules\Content\Requests;

class UpdateMcqRequest extends BaseMcqRequest
{
    public function rules(): array
    {
        return array_merge($this->getCommonRules(), [
            'question' => 'nullable|string',
            'options' => 'nullable|array|min:2',
            'options.*' => 'required|string',
            'correct_answer' => 'nullable|string',
            'explanation' => 'nullable|string',
        ]);
    }

    public function messages(): array
    {
        return $this->getCommonMessages();
    }
}
