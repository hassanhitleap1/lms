<?php
/**
 * Created by PhpStorm.
 * User: Odai
 * Date: 4/15/2018
 * Time: 3:59 PM
 */
?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>@lang('lang.Confirm_Token') </title>
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
            <form id="confirm_token"  action="{{URL::to('/').'/'.Lang::getLocale().'/confirmed'}}"  method="POST">
                <div class="msg">
                    @lang('lang.Confirm_Token')
                </div>
                @if(\Session::has('confim_code'))
                    <div class="alert alert-danger" role="alert">
                        {{Session::get('confim_code')}}
                        <?php Session::flush('confim_code')?>
                    </div>
                @endif
                <div class="input-group">
                    <div class="form-line">
                        <input type="text" class="form-control" name="token_code"  value="{{@$_GET['token_code']}}">
                    </div>
                </div>
                <button  class="btn btn-block btn-primary waves-effect" type="submit">@lang('lang.Send')</button>
                <input type="hidden" class="jq_formdata" name="_token" value="{{ csrf_token() }}">
                <div class="row m-t-15">
                    <div class="col-xs-6 align-left">
                        @if(Lang::getLocale() == 'en')
                            <a class="col-main-color align-left pull-left language-button waves-effect" href="{{url('/ar/login')}}">عربي</a>
                        @else(Lang::getLocale() == 'ar')
                            <a class="col-main-color align-left pull-left language-button" href="{{url('/en/login')}}">English</a>
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
