<?php


namespace App\Http\Middleware;


use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Lang;

class AuthMiddleware
{
    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next)
    {


        if (auth()->guest()) {
            if(! ('en/messages/get-all'==$request->path()) &&
                ! ('en/notifications/get'==$request->path())&&
                ! ('ar/messages/get-all'==$request->path()) &&
                ! ('en/login'==$request->path()) &&
                ! ('ar/login'==$request->path()) &&
                ! (''==$request->path()) &&
                ! ('ar/notifications/get'==$request->path())){

                Cookie::queue('redirection', $request->path(), 10);

            }

            return redirect(Lang::getLocale().'/login');
        }

        return $next($request);
    }



}
