<?php
/**
 * Created by PhpStorm.
 * User: Odai
 * Date: 4/15/2018
 * Time: 3:59 PM
 */
?>

        <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>@lang('lang.Reset_Your_Password')</title>
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app'.ucfirst(Lang::getLocale()).'.css') }}" rel="stylesheet"/>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="/js/lang.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/librarys.js')}}"></script>
    <script src="{{ asset('js/frontend.js')}}"></script>
</head>
<body class="fp-page theme-manhalgreen">
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
            <a class="navbar-brand logo" style="margin-left: 0 !important;;margin-right: 0 !important;" href="{{url('/')."/".Lang::getLocale()}}/home"></a>
        </div>
    </div>
</nav>
<div class="fp-box">
    <div class="logo1">
        <img src="/images/logoiphone.svg" class="logo-school" title="Almanhal schools" />
        <a class="col-main-color">@lang('lang.SchoolName')</a>
    </div>
    <div class="card">
        <div class="body">
            <form id="forgot_password"  action="{{URL::to('/').'/'.Lang::getLocale().'/rest-pass'}}"  method="POST">
                <div class="msg">
                    @lang('lang.Reset_Your_Password')
                    <span>@lang('lang.Enter_your_email_reset')</span>
                </div>
                @if(\Session::has('send_email'))
                    <div class="alert alert-info btn-primary" role="alert">
                        {{Session::get('send_email')}}
                        <?php Session::flush('send_email')?>
                    </div>
                @else
                    @if ($errors->any())
                        <script> swal('{{$errors->first()}}');</script>
                    @endif
                    <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">email</i>
                            </span>
                        <div class="form-line">
                            <input type="email" class="form-control" name="email" placeholder="@lang('lang.Email')"  autofocus>
                        </div>
                    </div>
                    <button  class="btn btn-block btn-primary waves-effect" type="submit">@lang('lang.Send')</button>
                    <input type="hidden" class="jq_formdata" name="_token" value="{{ csrf_token() }}">
                @endif
                <div class="row m-t-15">
                    <div class="col-xs-6 align-left">
                        @if(Lang::getLocale() == 'en')
                            <a class="col-main-color align-left pull-left language-button waves-effect" href="{{url('/ar/forgotpassword')}}">عربي</a>
                        @else(Lang::getLocale() == 'ar')
                            <a class="col-main-color align-left pull-left language-button" href="{{url('/en/forgotpassword')}}">English</a>
                        @endif
                    </div>
                    <div class="col-xs-6 align-right">
                        <a class="col-main-color pull-right forgot-password-button waves-effect" href="{{url('/')."/".Lang::getLocale()}}/login">@lang('lang.Sign_in')</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
