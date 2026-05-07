<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\JwtHelper;

class JwtAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token missing'], 401);
        }

        try {
            $payload = JwtHelper::decode($token);
            auth()->loginUsingId($payload->sub);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token expired or invalid'], 401);
        }

        return $next($request);
    }
}
