@extends('layout.app')
@section('title',  __('lang.View_Messages'))
@section('content')
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>
    <div class="content">
        <div class="body">
            <div class="row clearfix view-messages-container">
                <ul class="menu" id="notficatton_chat">
                    @foreach($messages as $message)
                        <li>
                            <a href="{{url('/')."/".Lang::getLocale()}}/viewchat?with={{$message->userid}}">
                                <div class="icon-circle bg-light-green">
                                    <img src="/images/user.png" width="48" height="48" alt="User" />
                                </div>
                                <div class="menu-info">
                                    <h4>{{$message->fullname}}</h4>
                                    <p>
                                        <i class="material-icons">access_time</i>
                                        {{\App\Helper\DateTimeHelper::getdifDateTime($message->time_mess)}}
                                        @lang('lang.ago')
                                    </p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

@endsection
