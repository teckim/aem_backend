<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SocialMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $enabledServices = ['google', 'facebook'];
        if (!in_array(strtolower($request->service), $enabledServices)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid social service'
            ], 403);
        }
        return $next($request);
    }
}
