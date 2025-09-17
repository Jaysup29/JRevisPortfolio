<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class JwtSessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (!session('jwt')) {
            return redirect()->route('login');
        }

        try {
            $user = JWTAuth::setToken(session('jwt'))->toUser();
            auth()->setUser($user); // inject user into Laravel's auth system
        } catch (\Exception $e) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
