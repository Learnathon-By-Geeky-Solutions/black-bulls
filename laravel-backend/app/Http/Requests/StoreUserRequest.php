<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'phone' =>'required|string|max:14',
            'email' =>'required|string|email|unique:users',
            'password' =>'required|string|min:2|confirmed',
            'profile_picture' =>'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
