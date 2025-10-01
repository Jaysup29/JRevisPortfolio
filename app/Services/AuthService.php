<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function attemptLogin(array $credentials)
    {
        if (! $token = JWTAuth::attempt($credentials)) {
            return null;
        }

        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => JWTAuth::factory()->getTTL() * 60,
            'user'         => auth()->user(),
        ];
    }
}
