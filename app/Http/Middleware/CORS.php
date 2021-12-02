<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;

class CORS
{
    public function handle($request, Closure $next)
    {
        if (! in_array(env('APP_ENV'), ['local', 'dev'], true)) {
            return $next($request);
        }

        return $next($request)
            ->header('Access-Control-Allow-Origin', $_SERVER['HTTP_ORIGIN'])
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Max-Age', '1000')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
    }
}
