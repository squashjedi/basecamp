<?php

namespace Squashjedi\Basecamp\App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

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
        if (!Auth::Check()) {
            $isWebmaster = false;
        } elseif (!Auth::user()->roles()->first()) {
            $isWebmaster = false;
        } elseif (Auth::user()->roles()->first()->role != 'webmaster') {
            $isWebmaster = false;
        } else {
            $isWebmaster = true;
        }
        if (!$isWebmaster) {
            return redirect(route('settings'));
        }

        return $next($request);
    }
}