<?php

namespace App\Modules\Payment\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    private const STRING_RULE = 'required|string|max:255';

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'amount' => 'required|numeric',
            'customer_name' => self::STRING_RULE,
            'customer_mobile' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
            'address' => self::STRING_RULE,
            'country' => self::STRING_RULE,
            'state' => self::STRING_RULE,
            'zip' => 'required|string|max:20',
            'course_id' => 'required|exists:courses,id',
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => 'The total amount field is required.',
            'amount.numeric' => 'The total amount must be a number.',
            'amount.min' => 'The total amount must be at least 10.',
            'customer_name.required' => 'The full name field is required.',
            'customer_mobile.required' => 'The mobile number field is required.',
            'customer_email.required' => 'The email field is required.',
            'customer_email.email' => 'The email must be a valid email address.',
            'address.required' => 'The address field is required.',
            'country.required' => 'The country field is required.',
            'state.required' => 'The state field is required.',
            'zip.required' => 'The zip code field is required.',
        ];
    }
}
