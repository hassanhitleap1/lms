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
    <title>@lang('lang.Sign_in')</title>
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
<body class="login-page theme-manhalgreen">
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
            <a class="navbar-brand logo" style="margin-left: 0 !important;;margin-right: 0 !important;" href="{{url('/')."/".Lang::getLocale()}}/home"></a>
        </div>
    </div>
</nav>
<div class="login-box">
    <div class="logo1">
        <img src="/images/logoiphone.svg" class="logo-school" title="Almanhal schools" />
        <a class="col-main-color">@lang('lang.SchoolName')</a>
    </div>
    <div class="card">
        <div class="body">
            <form id="sign_in" action="{{URL::to('/').'/'.Lang::getLocale().'/login'}}"  method="POST">
                <div class="msg">@lang('lang.Sign_in')</div>
                <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
       
                    <div class="form-line">
                        <input type="text" class="form-control" name="username" placeholder="@lang('lang.Username') or  @lang('lang.Email') " value="{{isset($uname)?$uname:''}}" required autofocus>
                    </div>
                </div>
                <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                    <div class="form-line">
                        <input type="password" class="form-control" name="password" placeholder="@lang('lang.Password')"  value="{{isset($pass)?$pass:''}}" required>
                    </div>
                    @if ($errors->has('error'))
                        <script> swal('{{$errors->first('error')}}');</script>
                     @endif
                </div>
                <div class="row sinin-forget-buttons">
                    <div class="col-xs-8 p-t-5 pull-left">
                        <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-main-color" {{ old('remember') ? 'checked' : '' }}>
                        <label for="rememberme">@lang('lang.Remember_Me')</label>
                    </div>
                    <div class="col-xs-4 pull-left">
                        <button  class="btn btn-primary waves-effect pull-right" type="submit">@lang('lang.Sign_in')</button>
                        <input type="hidden" class="jq_formdata" name="_token" value="{{ csrf_token() }}">
                    </div>
                </div>
                {{--<div class="row m-t-15 m-b-15 m-b--20">--}}
                    {{--<div class="col-xs-12 align-center">--}}
                        {{--@if(Lang::getLocale() == 'en')--}}
                            {{--<a class="col-main-color" href="{{url('/ar/login')}}">عربي</a>--}}
                        {{--@else(Lang::getLocale() == 'ar')--}}
                            {{--<a class="col-main-color" href="{{url('/en/login')}}">English</a>--}}
                        {{--@endif--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="row m-t-15">
                    <div class="col-xs-6 align-left">
                        @if(Lang::getLocale() == 'en')
                            <a class="col-main-color align-left pull-left language-button waves-effect" href="{{url('/ar/login')}}">عربي</a>
                        @else(Lang::getLocale() == 'ar')
                            <a class="col-main-color align-left pull-left language-button waves-effect" href="{{url('/en/login')}}">English</a>
                        @endif
                    </div>
                    <div class="col-xs-6 align-right">
                        <a href="{{url('/')."/".Lang::getLocale()}}/forgotpassword" class="col-main-color pull-right forgot-password-button" type="submit">@lang('lang.Forgot_Password')</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
