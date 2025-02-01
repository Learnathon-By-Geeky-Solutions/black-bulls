<?php

namespace app\Servies;

use App\Contracts\AuthServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService implements AuthServiceInterface
{
    public function register(array $data){

        $user = new User();
        $user->name = $data['name'];
        $user->phone = $data['phone'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->phone = $data['phone'];

        if(isset($data['profile_picture'])){
            $picture = $data['profile_picture'];
            $pictureName = time(). '.' . $picture->getClientOriginalExtension();
            Storage::disk('profile_pictures')->put($pictureName,file_get_contents($picture));
            $user->profile_picture = $pictureName;
        }

        $user->save();

        $token = JWTAuth::fromUser($user);
        return ['user' =>$user, 'token'=> $this->formatTokenResponse($token)];
    }

    public function login(array $credentials){
        
        if(!$token = JWTAuth::attempt($credentials)){
            return null;
        }
        return $this->formatTokenResponse($token);
    }

    public function logout(){

        JWTAuth::invalidate(JWTAuth::getToken());
        return true;
    }

    public function refreshToken(){

        JWTAuth::refresh(JWTAuth::getToken());

    }

    public function formatTokenResponse($token){

        return [
            'access_token' => $token,
            'token_type' =>'bearer',
            'expires_in'=> JWTAuth::factory()->getTTL()*60
        ];
    }
}
