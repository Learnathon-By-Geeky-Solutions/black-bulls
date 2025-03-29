<?php

namespace App\Modules\Content\Requests;

class CreateMcqRequest extends BaseMcqRequest
{
    public function rules(): array
    {
        return array_merge($this->getCommonRules(), [
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_answer' => 'required|string',
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
