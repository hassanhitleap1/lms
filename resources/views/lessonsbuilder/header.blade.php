<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
          type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/lessonviewer'.ucfirst(Lang::getLocale()).'.css') }}" rel="stylesheet"/>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="/js/lang.js"></script>
    <script src="{{ asset('js/app.js')}}"></script>
    <script src="{{ asset('js/librarys.js')}}"></script>
    <script src="{{ asset('js/lessonviewer.js')}}"></script>
</head>
<body class="theme-manhalgreen overlay-open ls-closed">
{{--start popup--}}
<div class="modal fade" id="popup-container">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="hidepopup();"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">@yield('title')<span id="popup_header"></span></h4>
            </div>
            <div class="modal-body" id="popup_content">
            </div>
        </div>
    </div>
</div>
{{--end popup--}}
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
    <div class="container-fluid">
        <div class="col-sm-12 col-lg-12 col-md-12">
            <div class="col-sm-5 col-lg-5 col-md-5 float-left">
                <div category="<?=$Lesson->category;?>" class="category-icon-container float-left" title="@lang('lang.Category')"
                     style="background-image:url(/images/cat/cat1.svg);"></div>
                <h1 class="lesson-title-container float-left" title="@lang('lang.Lesson')"><?=$Lesson->title;?></h1>
                <div class="separateing-line-container float-left"></div>
                <label class="level-title-container float-left" title="@lang('lang.Level')"><?=$Lesson->{"ltitle_".Lang::getLocale()};?></label>
            </div>
            <div class="col-sm-7 col-lg-7 col-md-7 float-right">
                <div class="float-right">
                    <div class="user-container float-left">
                        <div class="imageuser floating-left" title="@lang('lang.Username')"></div>
                        <div class="name floating-left" title="{{Auth::user()->fullname }}">
                            {{Auth::user()->fullname }}
                        </div>
                    </div>
                    <div class="separateing-line-container float-left"></div>
                    <div class="save-container float-left"  id="save_lesson" title="@lang('lang.Save')"></div>
                    <div class="separateing-line-container float-left" ></div>
                    <?php
                    $actual_link = strtolower("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                    if(strpos($actual_link,"homework")===false){
                        ?>
                    <a class="view-container float-left" target="_blank" href="{{URL::to('/')."/".Lang::getLocale()."/lessonsviewer/".$Lesson->id}}" title="@lang('lang.View')"></a>
                    <?php
                    }else{
                        ?>
                    <a class="view-container float-left" target="_blank" href="{{URL::to('/')."/".Lang::getLocale()."/homework/".$Lesson->id}}" title="@lang('lang.View')"></a>
                    <?php
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
</nav>
<section class="content">





