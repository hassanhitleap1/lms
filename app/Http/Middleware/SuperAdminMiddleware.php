<?php

namespace App\Http\Middleware;

use Closure;
use App\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class SuperAdminMiddleware
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
        if (Auth::check() && Users::isManhalAdmin()) {
            return $next($request);
        }

        return redirect(App::getLocale().'/home');

    }
}
