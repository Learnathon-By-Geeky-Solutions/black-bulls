<?php

namespace App\Modules\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Common\Traits\ValidationTrait;

class UpdateProfileRequest extends FormRequest
{
    use ValidationTrait;

    private const STRING_MAX_255 = 'nullable|string|max:255';
    private const IMAGE = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';

    public function rules(): array
    {
        return [
            'name' => self::STRING_MAX_255,
            'phone' => 'string|max:20',
            'profile_picture' => self::IMAGE,
            'designation' => self::STRING_MAX_255,
            'institution' => self::STRING_MAX_255,
            'dept' => self::STRING_MAX_255,
            'address' => 'nullable|string|max:1000'
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Name must be a string',
            'name.max' => 'Name cannot exceed 255 characters',
            'phone.string' => 'Phone must be a string',
            'phone.max' => 'Phone cannot exceed 20 characters',
            'profile_picture.image' => 'Profile picture must be a valid image',
            'profile_picture.mimes' => 'Profile picture must be in JPEG, PNG, JPG, or GIF format',
            'profile_picture.max' => 'Profile picture size cannot exceed 2MB',
            'designation.string' => 'Designation must be a string',
            'designation.max' => 'Designation cannot exceed 255 characters',
            'institution.string' => 'Institution must be a string',
            'institution.max' => 'Institution cannot exceed 255 characters',
            'dept.string' => 'Department must be a string',
            'dept.max' => 'Department cannot exceed 255 characters',
            'address.string' => 'Address must be a string',
            'address.max' => 'Address cannot exceed 1000 characters'
        ];
    }
}
