<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;

class UnderConstruction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return response(view('errors.under-construction'));
    }
}
