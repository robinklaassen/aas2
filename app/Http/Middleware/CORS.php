<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;

class CORS
{
    public function handle($request, Closure $next)
    {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? 'dev';
        $allowedOrigins = config('cors.allowed-origins');

        if (in_array($origin, $allowedOrigins, true)) {
            return $next($request)
                ->header('Access-Control-Allow-Origin', $_SERVER['HTTP_ORIGIN'])
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        }

        return $next($request);
    }
}
