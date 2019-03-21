@extends('layout.app')
@section('title', __('lang.Students')." : ".Auth::user()->fullname )
@section('content')
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>
    <div class="row clearfix home">
        @if(count($Lessons)>0)
            @foreach($Lessons as $key=>$lesson)
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                    <div class="panel-group">
                        <div class="panel panel-col-manhalgreen">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a role="button">
                                        <i class="category-icon-container float-left" style="background-image:url(/images/cat2/{{$lesson['info']['title_en']}}.svg);"></i>
                                        <label class="float-left category-icon-container-label">{{$lesson['info']["title_".Lang::getLocale()] }}</label>
                                    </a>
                                </h4>
                            </div>
                            <div  class="panel-collapse collapse in">
                                <div class="panel-body">
                                    @foreach($lesson['lesson'] as $key=>$medias)
                                        @foreach($medias as $media)
                                            <div class="media lesson_{{$key}}">
                                                <div class="media-left">
                                                    <a href="{{url(Lang::getLocale().'/lessonsviewer/'.$media->id)}}" target="_blank">
                                                        <img class="media-object" src="{{$media->thumbnail}}" width="64" height="64">
                                                    </a>
                                                </div>
                                                <div class="media-body">
                                                    <a href="{{url(Lang::getLocale().'/lessonsviewer/'.$media->id)}}" target="_blank">
                                                        <h4 class="media-heading float-left">{{$media->title}}</h4>
                                                    </a>
                                                    <div class="media-mark">
                                                        <a class="c100 p{{isset($lesson['lesson']['result']['result'])?$lesson['lesson']['result']['result']:'0'}} small float-right progress-home">
                                                            <span>{{isset($lesson['lesson']['result']['result'])?$lesson['lesson']['result']['result']:'0'}}%</span>
                                                            <div class="slice">
                                                                a@extends('layout.app')
                                                                @section('title', __('lang.Students')." : ".Auth::user()->fullname )
                                                                @section('content')
                                                                    <div class="block-header">
                                                                        <h2>@yield('title')</h2>
                                                                    </div>
                                                                    <div class="row clearfix home">
                                                                        @if(count($Lessons)>0)
                                                                            @foreach($Lessons as $key=>$lesson)
                                                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                                                                    <div class="panel-group">
                                                                                        <div class="panel panel-col-manhalgreen">
                                                                                            <div class="panel-heading">
                                                                                                <h4 class="panel-title">
                                                                                                    <a role="button">
                                                                                                        <i class="category-icon-container float-left" style="background-image:url(/images/cat/{{$lesson['info']['category_id']}}.svg);"></i>
                                                                                                        <label class="float-left category-icon-container-label">{{$lesson['info']["title_".Lang::getLocale()] }}</label>
                                                                                                    </a>
                                                                                                </h4>
                                                                                            </div>
                                                                                            <div  class="panel-collapse collapse in">
                                                                                                <div class="panel-body">
                                                                                                    @foreach($lesson['homeworks'] as $homework)
                                                                                                        @if(isset($homework->media))

                                                                                                            <div class="media media-homework homework_{{$homework->homework_id}}">
                                                                                                                <div class="media-left">
                                                                                                                    <a href="{{url(Lang::getLocale().'/homework/'.$homework->homework_id)}}" target="_blank">
                                                                                                                        <img class="media-object" src="{{$homework->media->thumbnail}}" width="64" height="64">
                                                                                                                    </a>
                                                                                                                </div>
                                                                                                                <div class="media-body">
                                                                                                                    <a href="{{url(Lang::getLocale().'/homework/'.$homework->homework_id)}}" target="_blank">
                                                                                                                        <h4 class="media-heading">{{$homework->title}}</h4>
                                                                                                                        <p class="type">@lang('lang.Homework') </p>
                                                                                                                    </a>
                                                                                                                    <div class="media-mark">
                                                                                                                        <a class="c100 p{{intval($homework->result['result'])}} small float-right progress-home">
                                                                                                                            <span>{{$homework->result['percent']}} %</span>
                                                                                                                            <div class="slice">
                                                                                                                                <div class="bar"></div>
                                                                                                                                <div class="fill"></div>
                                                                                                                            </div>
                                                                                                                        </a>
                                                                                                                    </div>
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
                                                                                                                        <h4 class="media-heading">{{$quiz->title}}</h4>
                                                                                                                        <p class="type">@lang('lang.Quiz')</p>
                                                                                                                    </a>
                                                                                                                    <div class="media-mark">
                                                                                                                        <?php $parss =0?>
                                                                                                                        <a class="c100 p{{$parss}} small float-right progress-home">
                                                                                                                            <span>{{$parss}} %</span>
                                                                                                                            <div class="slice">
                                                                                                                                <div class="bar"></div>
                                                                                                                                <div class="fill"></div>
                                                                                                                            </div>
                                                                                                                        </a>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            @endif


                                                                                                        @endforeach

                                                                                                    @foreach($lesson['lesson'] as $key=>$medias)
                                                                                                            @foreach($medias as $media)
                                                                                                                <div class="media lesson_{{$key}}">
                                                                                                                    <div class="media-left">
                                                                                                                        <a href="{{url(Lang::getLocale().'/lessonsviewer/'.$media->id)}}" target="_blank">
                                                                                                                            <img class="media-object" src="{{$media->thumbnail}}" width="64" height="64">
                                                                                                                        </a>
                                                                                                                    </div>
                                                                                                                    <div class="media-body">
                                                                                                                        <a href="{{url(Lang::getLocale().'/lessonsviewer/'.$media->id)}}" target="_blank">
                                                                                                                            <h4 class="media-heading">{{$media->title}}</h4>
                                                                                                                            <p class="type">@lang('lang.Lessons')</p>
                                                                                                                        </a>
                                                                                                                        <div class="media-mark">
                                                                                                                            <?php $parss=\App\Helper\UserHelper::getAvgLessonOneByOne($media->id,Auth::user()->userid);?>
                                                                                                                                <a class="c100 p{{intval($parss['percent'])}} small float-right progress-home">
                                                                                                                                    <span>{{$parss['result']}} %</span>
                                                                                                                                    <div class="slice">
                                                                                                                                        <div class="bar"></div>
                                                                                                                                        <div class="fill"></div>
                                                                                                                                    </div>
                                                                                                                                </a>
                                                                                                                        </div>
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
                                                                        @endif
                                                                    </div>
                                                                @endsection
                                                                <div class="bar"></div>
                                                                <div class="fill"></div>
                                                            </div>
                                                        </a>
                                                    </div>
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
        @endif
    </div>
@endsection