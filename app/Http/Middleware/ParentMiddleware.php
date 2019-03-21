<?php

namespace App\Http\Middleware;

use Closure;
use App\Users;

class ParentMiddleware
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
        if (Auth::user() &&  Auth::user()->permession == Users::USER_PARENT) {
            return $next($request);
        }

        return redirect(Lang::getLocale().'/home');
       
    }
}
