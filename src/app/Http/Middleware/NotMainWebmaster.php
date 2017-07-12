<?php

namespace Squashjedi\Basecamp\App\Http\Middleware;

use Closure;

class NotMainWebmaster
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
        if ($request->user()->id == 1) {
            return redirect(route('settings'));
        }

        return $next($request);
    }
}