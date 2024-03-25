<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;

class JsonAcceptMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $request->headers->add(['accept' => 'application/json']);
        return $next($request);
    }
}
