<?php
/**
 * Created by PhpStorm.
 * User: Saleh
 * Date: 11/18/2018
 * Time: 11:01 AM
 */

namespace App\Helper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use URL;

class SendEmail
{
    public static  function  sendEmailRestPass($user,$password){
        if(!empty($user->email)){
            $to = $user->email;
            $message=Self::messageRestPass($password);
            $subject = 'manhal.com - reset password';
            $headers = "From: support@manhal.com \r\n";
            $headers .= "Reply-To: support@manhal.com \r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            mail($to, $subject, $message, $headers);
        }

    }

    private  static function messageRestPass($password){
        return '<!DOCTYPE html><html><head><meta charset="utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1"></head><body>
                    <style></style>
                        <div class="box">
                                New Password Is: - ' .$password.'
                        </div></body></html>';
    }


    public static  function  sendForgotPassword($email,$token){
        if(!empty($email)){
            $to = $email;
            $message=Self::messageForgotPassword($token);
            $subject = 'manhal.com - reset password';
            $headers = "From: support@manhal.com \r\n";
            $headers .= "Reply-To: support@manhal.com \r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            mail($to, $subject, $message, $headers);
        }

    }

    private  static function messageForgotPassword($token){
        return '<!DOCTYPE html><html><head><meta charset="utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1"></head><body>
                    <style></style>
                        <div class="box"><h1>
                            <a href="'.URL::to(App::getLocale()."/confim-rest?token_code=".$token).'">'.Lang::get('lang.click_here_to_change_your_password').'</a>
                        </h1>
                        </div></body></html>';
    }
}