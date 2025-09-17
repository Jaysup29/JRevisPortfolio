<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class GuestJwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (session('jwt')) {
            try {
                $user = JWTAuth::setToken(session('jwt'))->toUser();
                if ($user) {
                    return redirect()->route('dashboard');
                }
            } catch (\Exception $e) {
                // invalid/expired token, just let them log in
            }
        }

        return $next($request);
    }
}
