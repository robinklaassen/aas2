<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfNotAMember
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()->isMember()) {
            return redirect('/profile');
        }

        return $next($request);
    }
}
