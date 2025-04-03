<?php

namespace App\Modules\Auth\Services;

use App\Modules\Common\Contracts\RepositoryInterface;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;
use App\Modules\Common\Services\FileHandleService;

class ProfileService
{
    protected $repository;
    protected $fileHandleService;
    private const PROFILE_PATH = 'images/profiles';

    public function __construct(FileHandleService $fileHandleService)
    {
        $this->repository = app()->make(RepositoryInterface::class, ['model' => new User()]);
        $this->fileHandleService = $fileHandleService;
    }

    public function getProfile(): array
    {
        try {
            $userId = Auth::id();
            $user = $this->repository->getById($userId);
            $user->load('userDetails');
            
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
            $user = $this->repository->getById($userId);
            
            if (isset($data['profile_picture'])) {
                if ($user->profile_picture) {
                    $this->fileHandleService->deleteFile($user->profile_picture);
                }
                $data['profile_picture'] = $this->fileHandleService->storeFile($data['profile_picture'], self::PROFILE_PATH);
            }
            
            $userData = collect($data)->only(['name', 'phone', 'profile_picture'])->toArray();
            $user = $this->repository->update($userId, $userData);
            
            $userDetailsData = collect($data)->only([
                'designation',
                'institution',
                'dept',
                'address'
            ])->toArray();

            if (!empty($userDetailsData)) {
                $userDetails = $user->userDetails;
                if (!$userDetails) {
                    $userDetails = new UserDetail(['user_id' => $userId]);
                }
                
                $userDetails->fill($userDetailsData);
                $userDetails->save();
            }
            
            DB::commit();
            
            // Reload user with user details
            $user->load('userDetails');

            return [
                'is_success' => true,
                'message' => 'Profile updated successfully',
                'data' => $user,
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
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
            $user = $this->repository->getById($userId);
            
            if (!Hash::check($data['current_password'], $user->password)) {
                return [
                    'is_success' => false,
                    'message' => 'Current password is incorrect',
                    'status' => 422
                ];
            }
            
            $this->repository->update($userId, ['password' => Hash::make($data['new_password'])]);
            
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
