<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/lessonviewer'.ucfirst(Lang::getLocale()).'.css') }}" rel="stylesheet"/>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="/js/lang.js"></script>
    <script src="{{ asset('js/app.js')}}"></script>
    <script src="{{ asset('js/librarys.js')}}"></script>
    <script src="{{ asset('js/quizviwer.js')}}"></script>
</head>
<body class="theme-manhalgreen overlay-open ls-closed">
<!--Page Loader-->
<div class="page-loader-wrapper" style="display:none;">
    <div class="loader">
        <div class="preloader">
            <div class="spinner-layer pl-red">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
        <p>@lang('lang.pleasewait')</p>
    </div>
</div>
<!-- #END# Page Loader -->
<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<!-- #END# Overlay For Sidebars -->
<!-- Top Bar -->
<nav class="navbar">
    <div class="container-fluid " id="quiz-viewer" quizid="{{$quiz->quiz_id}}" >
        <div class="col-sm-12 col-lg-12 col-md-12">
            <div class="col-sm-5 col-lg-5 col-md-5 float-left">
                <?php $qui=(Array)$quiz;
                $arr=config('lms.Categories');
                ?>
                <div class="category-icon-container float-left" title="@lang('lang.Quiz')" style="background-image:url(/images/cat2/1.svg);">

                </div>
                <h1 class="lesson-title-container float-left" title="@lang('lang.Quiz')">@lang('lang.Quiz')</h1>
                <div class="separateing-line-container float-left"></div>
                <label class="level-title-container float-left" title="@lang('lang.Level')"><?php $qui['title'];?></label>
            </div>
            <div class="col-sm-7 col-lg-7 col-md-7 float-right">
                <div class="float-right">
                    <div class="points-awards-container float-left" title="@lang('lang.Points')">
                        <div class="imagepoints floating-left"></div>
                        <div class="num floating-left user-points">
                            {{$user_points}}%
                        </div>
                    </div>
                    <div class="separateing-line-container float-left"></div>
                    <div class="points-awards-container float-left" title="@lang('lang.Quiz_Viewer"')">
                        <div class="imageawards floating-left"></div>
                        <div class="num floating-left user-awards">
                            {{$awards}}
                        </div>
                    </div>
                    <div class="separateing-line-container float-left"></div>
                    <div class="rate-container float-left" title="@lang('lang.Progress_rate')">
                        <div class="imagerate floating-left"></div>
                        <div class="num floating-left user-progress">
                            {{$progress}}%
                        </div>
                    </div>
                    <div class="separateing-line-container float-left"></div>
                    <div class="user-container float-left">
                        <div class="imageuser floating-left" title="@lang('lang.Username')"></div>
                        <div class="name floating-left" title="{{Auth::user()->fullname }}">
                            {{Auth::user()->fullname }}
                        </div>
                    </div>
                    <div class="separateing-line-container float-left"></div>
                    <div class="exit-container float-left homework-exit"></div>
                </div>
            </div>
        </div>
    </div>
</nav>
<section class="content">
