<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
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
            'profile_picture' =>'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'role' =>'required|string|in:admin,instructor,student'
        ];
    }

    protected function failedValidation(Validator $validator){
        $errors = $validator->errors();
        foreach($errors->keys() as $key){
            if($this->has($key) && $this->get($key)===["The $key field is required."]){
                $errors->forget($key);
            }
        }

        throw new ValidationException($validator , response()->json([
            'is_success' => false,
            'message' => 'Validation failed',
            'details' => 'Validation failed for one or more fields',
            'errors' => $errors
        ], 422));
    }
}
