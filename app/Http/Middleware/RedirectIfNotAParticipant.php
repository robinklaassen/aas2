<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfNotAParticipant
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()->isParticipant()) {
            return redirect('/profile');
        }

        return $next($request);
    }
}
