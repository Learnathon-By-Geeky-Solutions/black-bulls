<?php

namespace App\Modules\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Common\Traits\ValidationTrait;

class UpdatePasswordRequest extends FormRequest
{
    use ValidationTrait;

    private const REQUIRED_STRING_MIN_2 = 'required|string|min:2';

    public function rules(): array
    {
        return [
            'current_password' => self::REQUIRED_STRING_MIN_2,
            'new_password' => self::REQUIRED_STRING_MIN_2 . '|confirmed',
            'new_password_confirmation' => self::REQUIRED_STRING_MIN_2
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Current password is required',
            'current_password.string' => 'Current password must be a string',
            'current_password.min' => 'Current password must be at least 2 characters',
            'new_password.required' => 'New password is required',
            'new_password.string' => 'New password must be a string',
            'new_password.min' => 'New password must be at least 2 characters',
            'new_password.confirmed' => 'New password confirmation does not match',
            'new_password_confirmation.required' => 'Password confirmation is required',
            'new_password_confirmation.string' => 'Password confirmation must be a string',
            'new_password_confirmation.min' => 'Password confirmation must be at least 2 characters'
        ];
    }
}
