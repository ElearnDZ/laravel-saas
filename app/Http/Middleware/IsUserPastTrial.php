<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

class IsUserPastTrial
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
        if (auth()->user() && !auth()->user()->subscription('main') && auth()->user()->isPastTrial()) {
            if(!$request->is('settings/billing') && !$request->is('logout')) {
                return redirect()->route('settings.billing');
            }
        }

        return $next($request);
    }
}
