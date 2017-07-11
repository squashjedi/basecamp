<?php

namespace Squashjedi\Basecamp\App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class NotWebmaster
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
        if (Auth::user()->id == 1) {
            return redirect(route('settings'));
        }

        return $next($request);
    }
}