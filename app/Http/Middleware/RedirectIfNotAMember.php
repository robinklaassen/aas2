<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfNotAMember
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
        if (!\Auth::user()->profile_type == "App\Member") {
            return redirect('/profile');
        }

        return $next($request);
    }
}
