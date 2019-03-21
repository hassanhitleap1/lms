<?php

?>
@extends('layout.app')
@section('title', __('lang.Parent')." : ".Auth::user()->fullname )
@section('content')
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>
    <div class="row clearfix home">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs tab-nav-right" role="tablist">

            @if(count($childs)>0)
                @foreach($childs as $key=>$Son)
                    <?php $class=''?>
                    @if($key==0)<?php $class='active';?>@endif
                    <li role="presentation" class="{{$class}}" ><a href="#tab_{{$key}}" data-toggle="tab">{{$Son['fullname']}}</a></li>
                @endforeach
            @endif
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            @if(count($childs)>0)
                @foreach($childs as $key=>$Son)
                    <?php $class=''?>
                    @if($key==0)<?php $class='active';?>@endif
                    <div role="tabpanel" class="tab-pane animated fadeIn {{$class}}" id="tab_{{$key}}">
                        <div class="m-t-20">
                            @foreach($Lessons[$key] as $lessons)
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                    <div class="panel-group">
                                        <div class="panel panel-col-manhalgreen">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a role="button">
                                                        <i class="category-icon-container float-left" style="background-image:url(/images/cat2/{{$lessons['info']['title_en']}}.svg);"></i>
                                                        <label class="float-left category-icon-container-label">{{$lessons['info']["title_".Lang::getLocale()] }}</label>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div class="panel-collapse collapse in">
                                                <div class="panel-body">
                                                        @foreach($lessons["homeworks"] as $homework)
                                                            @if($homework->media != null)
                                                                <div class="media media-homework">
                                                            <div class="media-left">
                                                                <a href="{{url(Lang::getLocale().'/homework/'.$homework->homework_id)}}" target="_blank">
                                                                    <img class="media-object" src="{{$homework->media->thumbnail}}" width="64" height="64">
                                                                </a>
                                                            </div>
                                                            <div class="media-body">
                                                                <a href="{{url(Lang::getLocale().'/homework/'.$homework->homework_id)}}" target="_blank">
                                                                    <h4 class="media-heading">{{$homework->title}}</h4>
                                                                    <p class="type">@lang('lang.Homework')</p>
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
                                                            @foreach($lessons["lesson"]["str"] as $media)
                                                                <div class="media lesson_0">
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
                                                                            <?php $parss=\App\Helper\UserHelper::getAvgLessonOneByOne($media->id,$Son->userid);?>
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
                                                      <?PHP /*

                                                          @foreach($lessons["lesson"]["end"] as $media)
                                                            <div class="media lesson_0">
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
                                                                        <?php $parss=\App\Helper\UserHelper::getAvgLessonOneByOne($media->id,$Son->userid);?>
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

                                                                  */?>
                                                        @foreach($lessons["quiz"] as $quiz)
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
                                                                                <a class="c100 p0 small float-right progress-home">
                                                                                    <span>0 %</span>
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

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
