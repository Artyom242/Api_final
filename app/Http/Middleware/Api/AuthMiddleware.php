<?php

namespace App\Http\Middleware\Api;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        $user = User::query()->firstWhere('api_token', $token);

        if (!isset($user)){
            return response()->json([
                'message' => 'Login failed'
            ]);
        }

        auth()->login($user);
        return $next($request);
    }
}
