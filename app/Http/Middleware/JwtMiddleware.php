<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasCookie('jwt_token')) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        try {
            $token = $request->cookie('jwt_token');
            JWTAuth::setToken($token);
            $user = JWTAuth::authenticate();
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        }

        return $next($request);
    }
}
