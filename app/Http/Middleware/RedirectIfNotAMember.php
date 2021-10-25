<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfNotAMember
{

	public function handle(Request $request, Closure $next)
	{
		if (!$request->user()->isMember()) {
			return redirect('/profile');
		}

		return $next($request);
	}
}
