<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Contracts\AuthServiceInterface;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Requests\RegisterRequest;
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

        $responseData = [
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'details' => $response['details'],
            'user' => $response['is_success'] ? $response['user'] : null,
            'token' => $response['is_success'] ? $response['token'] : null
        ];

        return response()->json($responseData, $response['status']);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $response = $this->authService->login($credentials);

        $responseData = [
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'details' =>$response['details'],
            'token' => $response['is_success'] ? $response['token'] : null
        ];

        return response()->json($responseData, $response['status']);
    }

    public function logout()
    {
        $this->authService->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refreshToken()
    {
        $refreshedToken = $this->authService->refreshToken();
        return response()->json([
            'token' => $refreshedToken,
            'message' => 'Token Refreshed Successfully'
        ],200);
    }
}
