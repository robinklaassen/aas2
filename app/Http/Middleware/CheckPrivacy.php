<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

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
        $isPrivacyRoute = strpos($request->route()->getName(), "privacy") !== false;

        if (!$isPrivacyRoute && Auth::user() && Auth::user()->privacy === null) {
            return redirect()->route('show-accept-privacy', [
                "origin" => $request->path()
            ]);
        }
        return $next($request);
    }
}
