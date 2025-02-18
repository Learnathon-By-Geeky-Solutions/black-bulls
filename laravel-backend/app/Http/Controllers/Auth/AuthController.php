<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\AuthServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $response = $this->authService->register($data);

        return response()->json([
            'user'=>$response['user'],
            'token'=>$response['token'],
            'message'=>'Registration Successful'
        ],201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $token = $this->authService->login($credentials);

        if(!$token){
            return response()->json(['message'=>'Invalid Credentials'],401);
        }

        return response()->json([
            'token'=>$token,
            'message'=>'Login Successful'
        ],201);
    }

    public function logout()
    {
        $this->authService->logout();

        return response()->json(['message' => 'Successfully logged out']);

    }

    public function refreshToken()
    {
        $refreshedToken = $this->authService->refreshToken();
        
        return response()->json($refreshedToken);
    }

}
