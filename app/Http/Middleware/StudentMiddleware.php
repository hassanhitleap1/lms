<?php

namespace App\Http\Middleware;

use Closure;
use App\Users;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class StudentMiddleware
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
        if (Auth::user()->permession == Users::USER_MANHAL_ADMINISTRATOR or
            Auth::user()->permession == Users::USER_SCHOOL_ADMINISTRATOR or
            Auth::user()->permession == Users::USER_SCHOOL_MANGER or
            Auth::user()->permession == Users::USER_TEACHER or
            Auth::user()->permession == Users::USER_STUDENT) {
            return $next($request);
        }

        return redirect(App::getLocale().'/home');
       
    }
}
