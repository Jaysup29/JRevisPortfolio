<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtSessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $token = session('jwt'); // or 'jwt_token'

        if (! $token) {
            return redirect()->route('login.web');
        }

        try {
            $user = JWTAuth::setToken($token)->authenticate();

            if (! $user) {
                return redirect()->route('login.web');
            }

            // Attach user to Laravel’s auth system
            auth()->setUser($user);

        } catch (TokenExpiredException $e) {
            return redirect()->route('login.web')->withErrors(['session' => 'Session expired. Please log in again.']);
        } catch (TokenInvalidException $e) {
            return redirect()->route('login.web')->withErrors(['session' => 'Invalid session.']);
        } catch (JWTException $e) {
            return redirect()->route('login.web')->withErrors(['session' => 'Authentication failed.']);
        }

        return $next($request);
    }
}
