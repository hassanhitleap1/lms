@extends('layout.app')
@section('title', __('lang.Admins')." : ".Auth::user()->fullname )
@section('content')
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>

    <div class="row clearfix home">
        @if(count($Lessons)>0)
            @for($count=0;$count< count($Lessons);$count++)
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 m-b-30">
                    <div class="card">
                        <div class="header">
                            <h2> @lang('lang.Teacher_Name') : {{$Lessons[$count]['fullname']}}</h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                @foreach($Lessons[$count]['lessons'] as $key=>$lesson)
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                                        <div class="panel-group">
                                            <div class="panel panel-col-manhalgreen">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a role="button">
                                                            <i class="category-icon-container float-left" style="background-image:url(/images/cat2/{{$lesson['info']['title_en']}}.svg);"></i>
                                                            <label class="float-left category-icon-container-label">{{$lesson['info']["ctitle_".Lang::getLocale()] }} - {{$lesson['info']["title_".Lang::getLocale()] }}</label>
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div class="panel-collapse collapse in">
                                                    <div class="panel-body">
                                                        @foreach($lesson['homeworks'] as $homework)
                                                            @if($homework->media != null)
                                                                <div class="media media-homework">
                                                                    <div class="media-left">
                                                                        <a href="{{url(Lang::getLocale().'/homework/'.$homework->homework_id)}}" target="_blank">
                                                                            <img class="media-object" src="{{$homework->media->thumbnail}}" width="64" height="64">
                                                                        </a>
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <a href="{{url(Lang::getLocale().'/homework/'.$homework->homework_id)}}" target="_blank">
                                                                            <h4 class="media-heading small-media-heading" title="{{$homework->title}}">{{$homework->title}}</h4>
                                                                            <p class="type">@lang('lang.Homework')</p>
                                                                        </a>
                                                                        @if($key=="end")
                                                                            <div class="lesson_end" title="@lang('lang.Completed')"></div>
                                                                        @else
                                                                            <div class="lesson_str" title="@lang('lang.UnCompleted')"></div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                        @foreach($lesson['quiz'] as $quiz)
                                                            @if($quiz->media != null)
                                                                <div class="media media-quiz">
                                                                    <div class="media-left">
                                                                        <a href="{{url(Lang::getLocale().'/quiz/'.$quiz->quiz_id)}}" target="_blank">
                                                                            <img class="media-object" src="{{$quiz->media->thumbnail}}" width="64" height="64">
                                                                        </a>
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <a href="{{url(Lang::getLocale().'/quiz/'.$quiz->quiz_id)}}" target="_blank">
                                                                            <h4 class="media-heading small-media-heading" title="{{$quiz->title}}">{{$quiz->title}}</h4>
                                                                            <p class="type">@lang('lang.Quiz')</p>
                                                                        </a>
                                                                        @if($key=="end")
                                                                            <div class="lesson_end" title="@lang('lang.Completed')"></div>
                                                                        @else
                                                                            <div class="lesson_str" title="@lang('lang.UnCompleted')"></div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                        @foreach($lesson['lesson'] as $key=>$medias)
                                                            @foreach($medias as $media)
                                                                <div class="media media-lessons">
                                                                    <div class="media-left">
                                                                        <a href="{{url(Lang::getLocale().'/lessonsviewer/'.$media->id)}}" target="_blank">
                                                                            <img class="media-object" src="{{$media->thumbnail}}" width="64" height="64">
                                                                        </a>
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <a href="{{url(Lang::getLocale().'/lessonsviewer/'.$media->id)}}" target="_blank">
                                                                            <h4 class="media-heading small-media-heading" title="{{$media->title}}">{{$media->title}}</h4>
                                                                            <p class="type">@lang('lang.Lessons')</p>
                                                                        </a>
                                                                        @if($key=="end")
                                                                            <div class="lesson_end" title="@lang('lang.Completed')"></div>
                                                                        @else
                                                                            <div class="lesson_str" title="@lang('lang.UnCompleted')"></div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        @endif
    </div>
@endsection
