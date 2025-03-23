<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Contracts\AuthServiceInterface;
use App\Modules\Auth\Repositories\Interfaces\AuthRepositoryInterface;
use App\Modules\Auth\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthService implements AuthServiceInterface
{
    public function register(array $data)
    {
        try{
            if ($data['role'] == 'admin' && (!Auth::check() || !Auth::user()->role != 'admin')) {
                return [
                    'is_success' => false,
                    'message' => 'Unauthorized',
                    'details' => 'Only admin can create admin',
                    'status' => 403
                ];
            }

            DB::beginTransaction();

            $user = new User();
            $user->name = $data['name'];
            $user->phone = $data['phone'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
    
            if (isset($data['profile_picture'])) {
                $picture = $data['profile_picture'];
                $picturePath = $picture->store('images/profile_pictures', 'public');
                $user->profile_picture = $picturePath;
            }
    
            $user->save();
            $user->assignRole($data['role']);
    
            $token = JWTAuth::fromUser($user);

            DB::commit();
            
            return [
                'is_success' => true,
                'message' => 'User created successfully',
                'details' => 'User created successfully',
                'user' => $user,
                'token' => $this->formatTokenResponse($token),
                'status' => 201,
            ];
        }
        catch(\Exception $e){
            DB::rollBack();

            return [
                'is_success' => false,
                'message' => 'User creation failed',
                'details' => $e->getMessage(),
                'status' => 500
            ];
        }
    }

    public function login(array $credentials){
        try{
            if(!$token = JWTAuth::attempt($credentials)){
                return [
                    'is_success' => false,
                    'message' => 'Invalid email or password',
                    'details' => 'Invalid email or password',
                    'status' => 401
                ];
            }

            return [
                'is_success' => true,
                'message' => 'Login successful',
                'details' => 'Login successful',
                'token' => $this->formatTokenResponse($token),
                'status' => 200
            ];
        }
        catch(\Exception $e){
            Log::error('Login Error: '.$e->getMessage());

            return [
                'is_success' => false,
                'message' => 'Login failed',
                'details' => $e->getMessage(),
                'status' => 500
            ];
        }
    }

    public function logout(){
        JWTAuth::invalidate(JWTAuth::getToken());
        return true;
    }

    public function refreshToken(){
        $newToken = JWTAuth::refresh(JWTAuth::getToken());
        return $this->formatTokenResponse($newToken);
    }

    public function formatTokenResponse($token){
        return [
            'access_token' => $token,
            'token_type' =>'bearer',
            'expires_in'=> JWTAuth::factory()->getTTL()*60
        ];
    }
}
