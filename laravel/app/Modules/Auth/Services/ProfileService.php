<?php

namespace App\Modules\Auth\Services;

use App\Modules\Common\Contracts\RepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;
use App\Modules\Common\Services\FileHandleService;

class ProfileService
{
    protected $userRepository;
    protected $fileHandleService;
    private const PROFILE_PICTURE_PATH = 'profile-pictures';

    public function __construct(FileHandleService $fileHandleService)
    {
        $this->userRepository = app()->make(RepositoryInterface::class, ['model' => new User()]);
        $this->fileHandleService = $fileHandleService;
    }

    public function getProfile(): array
    {
        try {
            $userId = Auth::id();
            $user = $this->userRepository->getById($userId);
            
            return [
                'is_success' => true,
                'message' => 'Profile retrieved successfully',
                'data' => $user,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve profile: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function updateProfile(array $data): array
    {
        try {
            DB::beginTransaction();
            
            $userId = Auth::id();
            $user = $this->userRepository->getById($userId);
            
            // Handle profile picture upload
            if (isset($data['profile_picture'])) {
                // Delete old profile picture
                if ($user->profile_picture) {
                    $this->fileHandleService->deleteFile($user->profile_picture);
                }
                $data['profile_picture'] = $this->fileHandleService->storeFile($data['profile_picture'], self::PROFILE_PICTURE_PATH);
            }
            
            $user = $this->userRepository->update($userId, $data);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Profile updated successfully',
                'data' => $user,
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            // Clean up uploaded profile picture if there was an error
            if (isset($data['profile_picture'])) {
                $this->fileHandleService->deleteFile($data['profile_picture']);
            }
            
            return [
                'is_success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function updatePassword(array $data): array
    {
        try {
            DB::beginTransaction();
            
            $userId = Auth::id();
            $user = $this->userRepository->getById($userId);
            
            // Verify current password
            if (!Hash::check($data['current_password'], $user->password)) {
                return [
                    'is_success' => false,
                    'message' => 'Current password is incorrect',
                    'status' => 422
                ];
            }
            
            // Update password
            $this->userRepository->update($userId, [
                'password' => Hash::make($data['new_password'])
            ]);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Password updated successfully',
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to update password: ' . $e->getMessage(),
                'status' => 500
            ];
        }
    }
}
