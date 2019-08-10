<?php

namespace App\Http\Middleware;

use Closure;

class CheckPrivacy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (Auth::user() && Auth::user()->privacy === null) {
            return route('/accept-privacy');
        }
        return $next($request);
    }
}
