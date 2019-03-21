<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Users;
use Illuminate\Support\Facades\Lang;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->redirectTo= Lang::getLocale().'/home';
    }


    public function postLogin($lang,Request $request){
        $userOrEamil=request('username');
        $remember = request('rememberme') ;
        $remember=($remember==null)?false:true;
        $query = Users::where(function($query) use ($userOrEamil){
            $query->orwhere('email',$userOrEamil )
                ->orwhere('uname', $userOrEamil);
        })
        ->where('password',md5(request('password')));
        $user=$query->first();
        if($user){

            Auth::login($user,$remember);

            if($remember){
                setcookie('remember_me', encrypt(request('username')).'&&&'.encrypt(request('password')), time() + (86400 * 30), "/"); // 86400 = 1 day
            }

            if (Cookie::has('redirection')){
                $this->redirectTo=Cookie::get('redirection');
                \Cookie::forget('redirection');
                return redirect($this->redirectTo);
            }
            return redirect($this->redirectTo);
        }else{
            $errors=['error'=>'username or email or password error'];
            return view('login.index')->withErrors($errors);
        }
    }

    public function login($lang){
        $uname='';
        $pass='';
        if (isset($_COOKIE['remember_me']) ){
           $str= $_COOKIE['remember_me'];
           $arrStr=explode("&&&", $str);
           $uname=decrypt($arrStr[0]);
           $pass=decrypt($arrStr[1]);
        }
        return view('login.index')->withPass($pass)->withUname($uname);
    }
    
    public function logout($lang,Request $request) {
        Auth::logout();
        return redirect(Lang::getLocale().'/login');
      }


      public function forgotpassword($lang){
          return view('login.forgotpassword');
      }
}
