<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Str;

class RateLimiter
{
    /**
     * Handle incoming request
     */
    public function handle(Request $request, Closure $next, $limit = 60, $minutes = 1)
    {
        $rateLimiter = app(\Illuminate\Cache\RateLimiter::class);
        $key = $this->resolveRequestSignature($request);

        if ($rateLimiter->tooManyAttempts($key, $limit, $minutes * 60)) {
            return response()->json([
                'error' => 'Too many requests',
                'retry_after' => $rateLimiter->availableIn($key),
            ], 429);
        }

        $rateLimiter->hit($key, $minutes * 60);

        return $next($request)->header(
            'RateLimit-Limit',
            $limit
        );
    }

    /**
     * Resolve request signature for rate limiting
     */
    protected function resolveRequestSignature(Request $request): string
    {
        return Str::lower($request->method() . '|' . $request->path() . '|' . $request->ip());
    }
}
