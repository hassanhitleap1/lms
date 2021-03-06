@extends('layout.app')
@section('title', '404')

@section('content')
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>
    <div class="row clearfix">
        <div class="four-zero-four">
            <div class="four-zero-four-container">
                <div class="error-code">404</div>
                <div class="error-message">@lang('lang.This_page_doesnt_exist')</div>
                <div class="button-place">
                    <a href="{{url('/')."/".Lang::getLocale()}}/home" class="btn btn-default btn-lg waves-effect">@lang('lang.GO_TO_HOMEPAGE')</a>
                </div>
            </div>
        </div>
       404 error
    </div>
@endsection
