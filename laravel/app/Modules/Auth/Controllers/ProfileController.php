<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Requests\UpdateProfileRequest;
use App\Modules\Auth\Requests\UpdatePasswordRequest;
use App\Modules\Auth\Services\ProfileService;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function getProfile(): JsonResponse
    {
        $response = $this->profileService->getProfile();
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $response = $this->profileService->updateProfile($request->validated());
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $response = $this->profileService->updatePassword($request->validated());
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message']
        ], $response['status']);
    }
}
