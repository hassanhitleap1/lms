<?php

namespace App\Http\Middleware;

use Closure;
use App\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class AdminsMiddleware
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
        if (Auth::check() && (Auth::user()->permession == Users::USER_MANHAL_ADMINISTRATOR or Auth::user()->permession == Users::USER_SCHOOL_MANGER)) {
            return $next($request);
        }

        return redirect(App::getLocale().'/home');
       
    }
}
