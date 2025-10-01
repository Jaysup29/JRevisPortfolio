<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $auth;

    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;
    }
    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        $result = $this->auth->attemptLogin($credentials);

        if (! $result) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // API should return JSON (not redirect)
        return response()->json($result);
    }

    public function me()
    {
        return response()->json(JWTAuth::user());
    }

    public function logout()
    {
        // Invalidate JWT
        JWTAuth::invalidate(JWTAuth::getToken());

        // Logout from session
        Auth::guard('web')->logout();
        
        return redirect('/');
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => JWTAuth::factory()->getTTL() * 60, // ✅ now works
        ]);
    }
}
