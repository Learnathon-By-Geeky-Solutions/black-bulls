<?php

namespace App\Modules\Content\Requests;

class CreateMcqRequest extends BaseMcqRequest
{
    private const REQUIRED_STRING = 'required|string';

    public function rules(): array
    {
        return array_merge($this->getCommonRules(), [
            'question' => self::REQUIRED_STRING,
            'options' => 'required|array|min:2',
            'options.*' => self::REQUIRED_STRING,
            'correct_answer' => self::REQUIRED_STRING,
            'explanation' => 'nullable|string',
        ]);
    }

    public function messages(): array
    {
        return array_merge($this->getCommonMessages(), [
            'question.required' => 'Question is required',
            'options.required' => 'Options are required',
            'options.array' => 'Options must be an array',
            'options.min' => 'At least 2 options are required',
            'options.*.required' => 'Each option is required',
            'correct_answer.required' => 'Correct answer is required',
        ]);
    }
}
