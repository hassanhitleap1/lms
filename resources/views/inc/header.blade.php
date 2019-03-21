<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app'.ucfirst(Lang::getLocale()).'.css') }}" rel="stylesheet"/>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset(Lang::getLocale().'/js/lang.js')}}"></script>
    <script src="{{ asset('js/app.js')}}"></script>
    <script src="{{ asset('js/librarys.js')}}"></script>
    <script src="{{ asset('js/frontend.js')}}"></script>

    <link rel="stylesheet" type="text/css" href="/fonts/flaticon1/flaticon.css">
</head>
<body class="theme-manhalgreen overlay-open ls-closed">
{{--start popup--}}
<div class="modal fade" id="popup-container">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="hidepopupWithConfirm();"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">@yield('title')<span id="popup_header"></span></h4>
            </div>
            <div class="modal-body" id="popup_content">
            </div>
        </div>
    </div>
</div>
{{--end popup--}}
<!--Page Loader-->
<div class="page-loader-wrapper">
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
        <p>@lang('lang.Please_wait')</p>
    </div>
</div>
<!-- #END# Page Loader -->
<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<!-- #END# Overlay For Sidebars -->
<!-- Search Bar -->
<div class="search-bar">
    <div class="search-icon">
        <i class="material-icons">search</i>
    </div>
    <input type="text" placeholder="@lang('lang.START_TYPING')">
    <div class="close-search">
        <i class="material-icons">close</i>
    </div>
</div>
<!-- #END# Search Bar -->
<!-- Top Bar -->
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
            <a href="javascript:void(0);" class="bars" style="display: block"></a>
            <a class="navbar-brand logo" href="{{url('/')."/".Lang::getLocale()}}/home"></a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <!-- Call Search -->
                @if(Lang::getLocale() == 'en')
                    <li><a class="language-button" href="{{str_replace('/en','/ar',Request::fullUrl())}} ">عربي</a></li>
                @else(Lang::getLocale() == 'ar')
                    <li><a class="language-button" href="{{str_replace('/ar','/en',Request::fullUrl())}}">English</a></li>
                @endif
                {{--<li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>--}}
                <!-- #END# Call Search -
                ->
                <!-- Notifications -->
                <li class="dropdown keep-open">
                    <a href="javascript:void(0);" class="dr opdown-toggle" data-toggle="dropdown" role="button" id="notifications-hover">
                        <i class="material-icons">notifications</i>
                        <span class="label-count" id="label-count-notifications" ></span>
                    </a>
                    <ul class="dropdown-menu keep-open" >
                        <li class="header">@lang('lang.NOTIFICATIONS')</li>
                        <li class="body">
                            <ul class="menu" id="menu-notification">
                                <?php
                                $notifications=\App\Notification::getLastNotifications()->limit(5)->orderBy('created_at','DESC')->get();
                                $lasNoifId=\App\Notification::getLastNotifications()->orderBy('created_at','DESC')->pluck('notification_id')->first();
                                ?>
                                @foreach($notifications as $notification)
                                    <li>
                                        <a href="{{$notification->link}}">
                                            <div class="icon-circle {{$notification->color_type}}">
                                                <i class="material-icons">{{$notification->type}}</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>{{$notification->message}}</h4><p>
                                                    <i class="material-icons">access_time</i>
                                                    {{\App\Helper\DateTimeHelper::getdifDateTime($notification->date)}}
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <li><a  id="load-more-notif" lastId="{{$lasNoifId}}" >@lang('lang.No_Notification')</a></li>
                        <li class="footer">
                            <a href="{{url('/')."/".Lang::getLocale()}}/viewnotification">@lang('lang.View_All_Notifications')</a>
                        </li>
                    </ul>
                </li>
                <!-- #END# Notifications -->
                <!-- Tasks -->
                <li class="dropdown keep-open">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" id="messages-hover">
                        <i class="material-icons">message</i>
                        <span class="label-count" id="label-count-mess"></span>
                    </a>
                    <ul class="dropdown-menu keep-open">
                        <li class="header">@lang('lang.MESSAGES')</li>
                        <li class="body">
                            <ul class="menu" id="menu-mess">
                                <?php $messages=\App\Messages::getUserChatWithHim()->limit(5)->get();?>
                                    @foreach($messages as $message )
                                    <li>
                                        <a href="{{asset('messages/viewchat?with='.$message->userid)}}">
                                            <div class="icon-circle bg-light-green">
                                            <img src="/images/user.png" width="48" height="48" alt="User" />
                                            </div>
                                        <div class="menu-info">
                                            <h4>{{$message->fullname}}</h4>
                                             <p>
                                                <i class="material-icons">access_time</i>
                                                 {{\App\Helper\DateTimeHelper::getdifDateTime($message->time_mess)}} ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <li><a id="load-more-message" lastIdMess="{{isset($message->id)?$message->id:0}}">@lang('lang.No_Messages')</a></li>
                        <li class="footer">
                            <a href="{{url('/')."/".Lang::getLocale()}}/messages/show-all">@lang('lang.View_All_Messages')</a>
                        </li>
                    </ul>
                </li>
                <li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li>
            </ul>
        </div>
    </div>
</nav>
<script type="text/javascript">
    var page = 1;
    $(document).ready(function () {
        $('.dropdown.keep-open').on({

            "shown.bs.dropdown": function() { this.closable = false; },
            "click":             function() { return this.closable;  },
            "hide.bs.dropdown":  function() { this.closable = false; }

        });
        $('.dropdown-menu.keep-open').on('click', function (e) {
            e.stopPropagation();
        });

        $("#load-more-notif").click(function () {
            page++; //page number increment
            load_more_notif(page); //load content
        });
        $("#load-more-message").click(function () {
            page++; //page number increment
            load_more_mess(page); //load content
        });
    });

    function load_more_notif(page)
    {
        $.ajax({
            url: '/en/notifications/load-more?page=' + page,
            type: "get",
            dataType: 'json',
            beforeSend: function()
            {
                $(".page-loader-wrapper").show();
            }
        }).done(function(data){
            var data = jQuery.parseJSON(JSON.stringify(data));

            if(data.notifications.data.length == 0){
                //notify user if nothing to load
                $('#load-more-notif').html("No more records!");
                $(".page-loader-wrapper").hide();
                return;
            }
            printLoadMoreNotif(data.notifications.data);
            $(".page-loader-wrapper").hide();
        }).fail(function(jqXHR, ajaxOptions, thrownError){
            alert('No response from server');
        });
    }

    function printLoadMoreNotif(data) {
        var contant='';
        $.each(data, function( key, value ) {
            contant+=
                '<li>' +
                '<a href="'+value.link+'" class=" waves-effect waves-block">\n' +
                '<div class="icon-circle '+value.color_type+'">\n' +
                '<i class="material-icons">'+value.type+'</i></div>\n' +
                '<div class="menu-info">\n' +
                '<h4>'+value.message+'</h4><p>\n' +
                    '<i class="material-icons">access_time</i>'+diffBtweenDate(value.date)+ '</p></div>\</a>\n' +
                '</li>';

        });
        $("#menu-notification").append(contant);

    }

    function load_more_mess(page)
    {
        $.ajax({
            url: '/en/messages/load-more?page=' + page,
            type: "get",
            dataType: 'json',
            beforeSend: function()
            {
               $(".page-loader-wrapper").show();
            }
        }).done(function(data){
            var data = jQuery.parseJSON(JSON.stringify(data));

            if(data.messages.data.length == 0){
                //notify user if nothing to load
                $('#load-more-message').html("No more records!");
               $(".page-loader-wrapper").hide();
                return;
            }
            printLoadMoreMess(data.messages.data);
            $(".page-loader-wrapper").hide();
        }).fail(function(jqXHR, ajaxOptions, thrownError){
            alert('@lang('lang.serverNotResponse')');
        });
    }

    function printLoadMoreMess(data) {
        var content='';
        $.each(data, function( key, value ) {
            content+='<li>\n' +
                '<a href="'+SITE_URL+Language+'/messages/show-all?message='+ value.userid+'" class=" waves-effect waves-block">\n' +
                ' <div class="icon-circle bg-light-green">\n' +
                '<img src="/images/user.png" width="48" height="48" alt="User" />\n' +
                '</div>\n' +
                '<div class="menu-info">\n' +
                '<h4>'+value.fullname+'</h4>\n' +
                '<p>\n' +
                '<i class="material-icons">access_time</i> '+diffBtweenDate(value.time_mess)+' \n' +
                '</p>\n' +
                '</div>\n' +
                '</a>\n' +
                '</li>';


        });
        $("#menu-mess").append(content);

    }
</script>
<section class="content">
    <div class="container-fluid">

