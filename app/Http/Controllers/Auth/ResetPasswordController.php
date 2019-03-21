<?php

namespace App\Http\Controllers\Auth;

use App\Helper\SendEmail;
use App\Http\Controllers\Controller;
use App\Users;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Url;
use Session;

class ResetPasswordController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    /**
     * sending email for erst password
     * @returm void
     */
    public function  restPassword($lang,Request $request){
        $rules=['email','required|email'];

        $validator = Validator::make($request->all(), $rules);

        $user=Users::where('email',$request->email)->first();
//        if ($validator->fails()) {
////            $errors=$validator->errors()->all();
////           return view('login.forgotpassword')->withErrors($errors);
//        }

        if(empty($user)){
            $errors=[Lang::get('lang.email_not_found')];
            return view('login.forgotpassword')->withErrors($errors);
        }

        $token=md5(uniqid(rand(), true));
        DB::insert('insert into password_rest (token, user_id) values (?, ?)', [$token, $user->userid]);
        if( ! in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ) ) ){
            SendEmail::sendForgotPassword($request->email,$token);
        }
        Session::put('send_email', Lang::get('lang.sucess_send_pl_ch_email'));
        return redirect(App::getLocale().'/forgotpassword');
    }



    public function confrimTockenRestPass($lang,Request $request){
        if(isset($request->token_code)){
            $token=$request->token_code;

            $tokenConfrm = DB::table('password_rest')
                ->select('user_id', 'token')->where('token',$token)->first();
            if(!empty($tokenConfrm)){
                Session::put('accept_rest', $tokenConfrm->token);
                return redirect(App::getLocale().'/new-password');
            }
            Session::put('confim_code','the code token is incurect');
            return redirect(App::getLocale().'/confim-rest?token_code='.$token);
        }
        return view('login.confrim_token');
    }


    public function confirmed($lang,Request $request){

        $token=$request->token_code;

        $tokenConfrm = DB::table('password_rest')
            ->select('user_id', 'token')->where('token',$token)->first();
        if(!empty($tokenConfrm)){
            Session::put('accept_rest', $tokenConfrm->token);
            return redirect(App::getLocale().'/new-password');
        }
        Session::put('confim_code','the code token is incurect');
        return redirect(App::getLocale().'/confim-rest?token_code='.$token);
    }

    public function newPassword($lang){
        if(Session::has('accept_rest')){
            return view('login.new_pass');
        }
        return redirect(App::getLocale().'/home');
    }



    public function newPasswordUser($lang,Request $request){
        $rules=[
            'password' => 'required',
            'conf_pass' => 'required_with:password|same:password'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors=$validator->errors()->all();
             return view('login.new_pass')->withErrors($errors);
        }
        if(Session::has('accept_rest')){
            $user= DB::table('password_rest')
                ->select('user_id')->where('token',Session::get('accept_rest'))->first();
        }
       $user= Users::find($user->user_id);
        $user->password=md5($request->password);
        $user->save();
        Session::flush('accept_rest');
        Auth::login($user);
        return redirect(App::getLocale().'/home');

    }
}

