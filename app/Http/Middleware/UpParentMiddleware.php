<?php
/**
 * Created by PhpStorm.
 * User: Saleh
 * Date: 10/28/2018
 * Time: 11:13 AM
 */

namespace App\Http\Middleware;


use Closure;
use App\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class UpParentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user() && (Auth::user()->permession < 5 || Users::isParent())) {
            return $next($request);
        }

        return redirect(\Illuminate\Support\Facades\Lang::getLocale() . '/home');

    }

}