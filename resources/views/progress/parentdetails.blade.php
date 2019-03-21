@extends('layout.app')
@section('title', __('lang.Parent_Details'))
@section('content')
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body table-responsive parent-details">
                    <div class="dataTables_wrapper form-inline dt-bootstrap fixed-height">
                        <div class="row change-between-tablechart col-xs-12 ol-sm-6 col-md-6 col-lg-6 float-right m-t-0 m-b-10">
                            <a class="btn btn-primary waves-effect float-right active Table"><i class="material-icons" title="@lang('lang.Table')">view_list</i></a>
                            <a class="btn btn-primary waves-effect float-right Chart"><i class="material-icons" title="@lang('lang.Chart')">insert_chart</i></a>
                        </div>
                        <div class="col-xs-12 ol-sm-6 col-md-6 col-lg-6 float-left"><h4>@lang('lang.Name')  : {{$user->fullname}}</h4></div>
                        <div class="col-xs-12 ol-sm-6 col-md-12 col-lg-12 float-left">
                            <form class="form-horizontal" action="{{url(Lang::getLocale().'/progress/parentdetails')}}" method="GET">
                                <input id="tab" type="hidden" name="tab" value="{{$tab}}" >
                                <div class="row clearfix">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 m-b-10 float-left">
                                        <div class="form-horizontal">
                                            <div class="row clearfix">
                                                <div class="col-lg-3 col-md-3 col-sm-2 col-xs-3 col-xs-12 form-control-label">
                                                    <label class="float-left">@lang('lang.Students')</label>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="form-line float-left">
                                                            <select class="form-control show-tick" name="student" id="student">
                                                                @foreach($students as $student)
                                                                    @if(isset($_GET['student']))
                                                                        @if($student->userid==$_GET['student'])
                                                                            <option value="{{$student->userid}}" selected>{{$student->fullname }}</option>
                                                                        @else
                                                                            <option  value="{{$student->userid}}" >{{$student->fullname }}</option>
                                                                        @endif
                                                                    @else
                                                                        <option  value="{{$student->userid}}" >{{$student->fullname}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 m-b-10 float-left">
                                        <div class="form-horizontal">
                                            <div class="row clearfix">
                                                <div class="col-lg-3 col-md-3 col-sm-2 col-xs-3 col-xs-12 form-control-label">
                                                    <label class="float-left">@lang('lang.Curriculums')</label>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="form-line float-left">
                                                            <select class="form-control show-tick" name="curriculum" id="curriculum">
                                                                <option  value="-1">----------</option>
                                                                @foreach($curriculums as $curriculum)
                                                                    @if(isset($_GET['curriculum']))
                                                                        @if($curriculum->curriculumsid==$_GET['curriculum'])
                                                                            <option value="{{$curriculum->curriculumsid}}" selected>{{$curriculum["cu_title_".Lang::getLocale()]}}</option>
                                                                        @else
                                                                            <option  value="{{$curriculum->curriculumsid}}" >{{$curriculum["cu_title_".Lang::getLocale()]}}</option>
                                                                        @endif
                                                                    @else
                                                                        <option  value="{{$curriculum->curriculumsid}}" >{{$curriculum["cu_title_".Lang::getLocale()]}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 m-b-10 float-left">
                                        <div class="form-horizontal">
                                            <div class="row clearfix">
                                                <div class="col-lg-3 col-md-3 col-sm-2 col-xs-3 col-xs-12 form-control-label">
                                                    <label class="float-left">@lang('lang.Categories')</label>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="form-line float-left">
                                                            <select class="form-control show-tick" name="category" id="category">
                                                                <option  value="-1">----------</option>
                                                                @foreach($categories as $category)
                                                                   <option value="{{$category->category_id}}"
                                                                           {{(isset($_GET['category'])&&($category->category_id==$_GET['category']))?'selected':''}}>
                                                                       {{$category["title_".Lang::getLocale()]}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-parent-chart">
                            <div class="row clearfix">
                                <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                                    <div class="panel-group full-body" id="accordion_19" role="tablist" aria-multiselectable="true">
                                        <?php $i=0?>
                                        @if(!empty($lessons))
                                            @foreach($lessons as $lesson)
                                                <div class="panel panel-col-manhalgreen">
                                                    <div class="panel-heading " role="tab" id="heading_{{$lesson->id}}">
                                                        <h4 class="panel-title">
                                                            <a class="collapsed" role="button" data-toggle="collapse" href="#collapse_{{$lesson->id}}" aria-expanded="false" aria-controls="collapse_{{$lesson->id}}">
                                                                <div class="title float-left">Lesson {{++$i}} : {{$lesson->title}}</div>
                                                                <div class="category-icon-container float-right">@lang('lang.Categories') :{{$lesson->infoCategory['title_'.Lang::getLocale()]}}</div>
                                                                <?php $avg=\App\Helper\UserHelper::getAvgLessonOneByOne($lesson->id,$user->userid);?>
                                                                <div class="progress1 float-right">Progress : {{$avg['percent']}}%</div>
                                                                <div class="result float-right">Result : {{$avg['result']}}%</div>
                                                            </a>

                                                        </h4>
                                                    </div>
                                                    <div id="collapse_{{$lesson->id}}" class="panel-collapse collapse vertical-slider-progress" role="tabpanel" aria-labelledby="heading_{{$lesson->id}}">
                                                        <ul class="panel-body">
                                                            @foreach($lesson->media as $media)
                                                                <li class="media">
                                                                    <div class="media-left">
                                                                        <div class="item-header">
                                                                            <div class="item-category">{{$lesson->infoCategory['title_'.Lang::getLocale()]}}</div>
                                                                            <div class="item-seen selected float-right"></div>
                                                                        </div>
                                                                        <a class="thumbnail" href="{{asset(Lang::getLocale().'/lessonsviewer/'.$lesson->id.'/?student='.$user->userid)}}">
                                                                            <img class="media-object" src="{{$media->thumbnail}}" width="128" height="100">
                                                                        </a>
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <h4 class="media-heading">{{$media->title}}  </h4>
                                                                        <div class="media-heading result float-right">Result :  <?=(($media->result($user->userid,$lesson->id))== null)?'0': $media->result($user->userid,$lesson->id)['result']  ?>%  </div>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                            @if(!empty($homeworks))
                                                @foreach($homeworks as $homework)
                                                    <div class="panel panel-col-manhalgreen">
                                                        <div class="panel-heading " role="tab" id="heading_{{$homework->homework_id}}">
                                                            <h4 class="panel-title">
                                                                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse_{{$homework->homework_id}}" aria-expanded="false" aria-controls="collapse_{{$homework->homework_id}}">
                                                                    <div class="title float-left">homework {{++$i}} : {{$homework->title}}</div>
                                                                    <div class="category-icon-container float-right">@lang('lang.Categories') :{{$homework->infoCategory['title_'.Lang::getLocale()]}}</div>
                                                                    <?php $avg=\App\Helper\UserHelper::getAvgHomeworkOneByOne($homework->homework_id,$user->userid);?>
                                                                    <div class="progress1 float-right">Progress : {{$avg['percent']}}%</div>
                                                                    <div class="result float-right">Result : {{$avg['result']}}%</div>
                                                                </a>

                                                            </h4>
                                                        </div>

                                                        <div id="collapse_{{$homework->homework_id}}" class="panel-collapse collapse vertical-slider-progress" role="tabpanel" aria-labelledby="heading_{{$homework->homework_id}}">
                                                            <ul class="panel-body">
                                                                @foreach($homework->media as $media)
                                                                    @if(!empty($media))
                                                                    <li class="media">
                                                                        <div class="media-left">
                                                                            <div class="item-header">
                                                                                <div class="item-category">{{$homework->categoryInfo['title_'.Lang::getLocale()]}}</div>
                                                                                <div class="item-seen selected float-right"></div>
                                                                            </div>
                                                                            <a class="thumbnail" href="{{asset(Lang::getLocale().'/homework/'.$homework->homework_id.'/?student='.$user->userid)}}">
                                                                                <img class="media-object" src="{{$media->thumbnail}}" width="128" height="100">
                                                                            </a>
                                                                        </div>
                                                                        <div class="media-body">
                                                                            <h4 class="media-heading">{{$media['title']}}  </h4>
                                                                            <div class="media-heading result float-right">Result :  <?=(($media->result($user->userid,$homework->homework_id))== null)?'0': $media->result($user->userid,$homework->homework_id)['result']  ?>%  </div>
                                                                        </div>
                                                                    </li>
                                                                    @endif

                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                            @if(!empty($quiz))
                                                @foreach($quiz as $qu)
                                                    <div class="panel panel-col-manhalgreen">
                                                        <div class="panel-heading " role="tab" id="heading_{{$qu->quiz_id}}">
                                                            <h4 class="panel-title">
                                                                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse_{{$qu->quiz_id}}" aria-expanded="false" aria-controls="collapse_{{$qu->quiz_id}}">
                                                                    <div class="title float-left">Quiz {{++$i}} : {{$qu->title}}</div>
                                                                    <div class="category-icon-container float-right">@lang('lang.Categories') :{{$qu->infoCategory['title_'.Lang::getLocale()]}}</div>
                                                                    <?php $avg=\App\Helper\UserHelper::getAvgQuizOneByOne($qu->quiz_id,$user->userid);?>
                                                                    <div class="progress1 float-right">Progress : {{$avg['percent']}}%</div>
                                                                    <div class="result float-right">Result : {{$avg['result']}}%</div>
                                                                </a>

                                                            </h4>
                                                        </div>

                                                        <div id="collapse_{{$qu->quiz_id}}" class="panel-collapse collapse vertical-slider-progress" role="tabpanel" aria-labelledby="heading_{{$qu->quiz_id}}">
                                                            <ul class="panel-body">
                                                                @foreach($qu->media as $media)
                                                                    @if(!empty($media))
                                                                        <li class="media">
                                                                            <div class="media-left">
                                                                                <div class="item-header">
                                                                                    <div class="item-category">{{$qu->infoCategory['title_'.Lang::getLocale()]}}</div>
                                                                                    <div class="item-seen selected float-right"></div>
                                                                                </div>
                                                                                <a class="thumbnail" href="{{asset(Lang::getLocale().'/quiz/'.$qu->quiz_id.'/?student='.$user->userid)}}">
                                                                                    <img class="media-object" src="{{$media->thumbnail}}" width="128" height="100">
                                                                                </a>
                                                                            </div>
                                                                            <div class="media-body">
                                                                                <h4 class="media-heading">{{$media['title']}}  </h4>
                                                                                <div class="media-heading result float-right">Result :  <?=(($media->result($user->userid,$qu->quiz_id))== null)?'0': $media->result($user->userid,$qu->quiz_id)['result']  ?>%  </div>
                                                                            </div>
                                                                        </li>
                                                                    @endif

                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="chart_div1" style="width: 100%; height: 100%;min-height: 450px;display: none;overflow: hidden"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="application/javascript">
        function drawVisualization1() {
            // Some raw data (not necessarily accurate)
            var x='{{$arrProgressJson}}';
            x=x.replace(/&quot;/g,'"');
            var progress = JSON.parse(x);
            var arr=([progress['title']].concat(progress['lessons']));
            var data = google.visualization.arrayToDataTable(arr);
            var options = {
                title : '@lang('lang.StudentChartdetails')',
                vAxis: {title: '@lang('lang.percentage')'},
                hAxis: {title: '@lang('lang.Lessons')'},
                seriesType: 'bars',
                series: {10: {type: 'line'}},
                isStacked:'number'

            };
            var chart = new google.visualization.ComboChart(document.getElementById('chart_div1'));
            chart.draw(data, options);
        }
        $(document).ready(function (event) {
            tab=$('#tab').val();
            $("#student").change(function(){
                $(this).closest("form").submit();
            });
            $("#curriculum").change(function(){
                $(this).closest("form").submit();
            });
            $("#category").change(function(){
                $(this).closest("form").submit();
            });

            if(tab=='chart'){
                $(".table-parent-chart").hide();
                $("#chart_div1").show();
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawVisualization1);
            }
        })
    </script>
@endsection
