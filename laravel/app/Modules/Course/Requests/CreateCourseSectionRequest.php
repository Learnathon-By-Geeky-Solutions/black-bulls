<?php

namespace App\Modules\Course\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateCourseSectionRequest extends BaseCourseSectionRequest
{
    private const REQUIRED_RULE = 'required|';

    public function rules(): array
    {
        $commonRules = $this->getCommonRules();

        // Add required modifier only for title, description, and order
        return array_merge($commonRules, [
            'title' => self::REQUIRED_RULE . ($commonRules['title'] ?? ''),
            'description' => self::REQUIRED_RULE . ($commonRules['description'] ?? ''),
            'order' => self::REQUIRED_RULE . ($commonRules['order'] ?? ''),
        ]);
    }

    public function messages(): array
    {
        $commonMessages = $this->getCommonMessages();
        
        // Add required messages
        return array_merge([
            'course_id.required' => 'Course is required',
            'title.required' => 'Section title is required',
            'order.required' => 'Section order is required',
        ], $commonMessages);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'is_success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422));
    }
}
