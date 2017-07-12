<?php

namespace Squashjedi\Basecamp\App\Http\Middleware;

use Closure;

class Webmaster
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
        if (@$request->user()->roles()->first()->role != 'webmaster') {
            return redirect(route('settings'));
        }

        return $next($request);
    }
}