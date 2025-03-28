<?php

namespace App\Modules\Course\Requests;

class UpdateCourseSectionRequest extends BaseCourseSectionRequest
{
    public function rules(): array
    {
        $commonRules = $this->getCommonRules();
        
        // Add sometimes modifier to all rules
        return array_map(function($rule) {
            return 'sometimes|' . $rule;
        }, $commonRules);
    }

    public function messages(): array
    {
        return $this->getCommonMessages();
    }
}
