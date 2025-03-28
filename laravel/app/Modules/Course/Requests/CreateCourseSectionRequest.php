<?php

namespace App\Modules\Course\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateCourseSectionRequest extends BaseCourseSectionRequest
{
    public function rules(): array
    {
        $commonRules = $this->getCommonRules();
        
        // Add required modifier to all rules
        return array_map(function($rule) {
            return 'required|' . $rule;
        }, $commonRules);
    }

    public function messages(): array
    {
        $commonMessages = $this->getCommonMessages();
        
        // Add required messages
        return array_merge([
            'course_id.required' => 'Course is required',
            'title.required' => 'Section title is required',
            'description.required' => 'Section description is required',
            'order.required' => 'Section order is required',
            'is_published.required' => 'Publication status is required',
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
