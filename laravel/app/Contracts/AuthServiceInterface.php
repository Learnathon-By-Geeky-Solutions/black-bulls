<?php

namespace App\Contracts;

interface AuthServiceInterface
{
    public function register(array $user);
    public function login(array $credentials);
    public function logout();
    public function refreshToken();
    public function formatTokenResponse($token);

}


