@extends('layout.app')
@section('title',  __('lang.View_Notifications'))
@section('content')
<div class="block-header">
    <h2>@yield('title')</h2>
</div>
<div class="content">
    <div class="body">
        <div class="row clearfix view-messages-container">
            <ul class="menu">
                    @foreach($notifications as $notification )
                        <li>
                        <a href="{{$notification->link}}">
                            <div class="icon-circle bg-purple">
                                <img src="{{asset($notification->byUser->avatar)}}" width="48" height="48" alt="User" />
                            </div>
                            <div class="menu-info">
                                <h4>{{$notification->byUser->fullname}}</h4>
                                <p>
                                    {{$notification->message}}
                                </p>
                                <p>
                                    <i class="material-icons">access_time</i>   Yesterday
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
