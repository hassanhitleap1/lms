@extends("layout.app")

@section("content")
    <h1>@lang('lang.About')</h1>
@endsection

@section('sidebar')
    @parent
    <dive></dive>
@endsection