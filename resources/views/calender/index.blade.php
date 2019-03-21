@extends('layout.app')
@section('title', __('lang.Calendar'))
@section('content')
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body table-responsive">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">

                            <div class="row">
                                @if( ! \App\Users::isStudent())
                                    <form  id="filter-form" method="GET" action="{{url(URL::to('/').'/'.Lang::getLocale().'/calender')}}" >
                                        @if( ! \App\Users::isParent())
                                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 float-left">
                                                <div class="form-horizontal">
                                                    <div class="row clearfix">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                                            <label class="float-left">@lang('lang.Level')</label>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                            <div class="form-group">
                                                                <div class="form-line float-left">
                                                                    <select class="form-control show-tick level" name="level" id="level">
                                                                        <option  value=-1>-----</option>

                                                                        @foreach($levels as $level)
                                                                            @if(isset($_GET['level']))

                                                                                @if($level->level_id==$_GET['level'])
                                                                                    <option value="{{$level->level_id}}" selected>{{$level["ltitle_".Lang::getLocale()] }}</option>

                                                                                @else
                                                                                    <option  value="{{$level->level_id}}" >{{$level["ltitle_".Lang::getLocale()] }}</option>
                                                                                @endif
                                                                            @else
                                                                                <option  value="{{$level->level_id}}" >{{$level["ltitle_".Lang::getLocale()] }}</option>
                                                                            @endif

                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 float-left">
                                                <div class="form-horizontal">
                                                    <div class="row clearfix">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                                            <label class="float-left">@lang('lang.Class')</label>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                            <div class="form-group">
                                                                <div class="form-line float-left">
                                                                    <select class="form-control show-tick" name="class" id="class">
                                                                        <option value=-1>-----</option>
                                                                        @if($modelClasses != '' and $modelClasses!=null)
                                                                            @foreach($modelClasses as $modelClass)
                                                                                @if(isset($_GET['class']))
                                                                                    @if($modelClass->class_id==$_GET['class'])
                                                                                        <option data-id="{{$modelClass->class_id}}" value="{{$modelClass->class_id}}" selected>{{$modelClass["ctitle_".Lang::getLocale()] }}</option>
                                                                                    @else
                                                                                        <option data-id="{{$modelClass->class_id}}" value="{{$modelClass->class_id}}" >{{$modelClass["ctitle_".Lang::getLocale()] }}</option>
                                                                                    @endif
                                                                                @else
                                                                                    <option data-id="{{$modelClass->class_id}}" value="{{$modelClass->class_id}}" >{{$modelClass["ctitle_".Lang::getLocale()] }}</option>
                                                                                @endif

                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </form>
                                @endif
                                    <form  id="filter-form" method="GET" action="{{url(URL::to('/').'/'.Lang::getLocale().'/calender')}}" >

                                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 float-left">
                                            <div class="form-horizontal">
                                                <div class="row clearfix">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                                        <label class="float-left">@lang('lang.Group')</label>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                        <div class="form-group">
                                                            <div class="form-line float-left">
                                                                <select class="form-control show-tick" name="group" id="group">
                                                                    <option value=-1>-----</option>
                                                                    @foreach($groups as $group)
                                                                        @if(isset($_GET['group']))
                                                                            @if($group->group_id==$_GET['group'])
                                                                                <option value="{{$group->group_id}}" selected>{{$group["title_".Lang::getLocale()] }}</option>
                                                                            @else
                                                                                <option  value="{{$group->group_id}}" >{{$group["title_".Lang::getLocale()] }}</option>
                                                                            @endif
                                                                        @else
                                                                            <option  value="{{$group->group_id}}" >{{$group["title_".Lang::getLocale()] }}</option>
                                                                        @endif

                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                    @if( ! \App\Users::isStudent())
                                        <form  id="filter-form" method="GET" action="{{url(URL::to('/').'/'.Lang::getLocale().'/calender')}}" >
                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 float-left">
                                            <div class="form-horizontal">
                                                <div class="row clearfix">
                                                    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                                                        <label class="float-left">@lang('lang.Students')</label>
                                                    </div>
                                                    <div class="col-lg-8 col-md-8 col-sm-9 col-xs-9">
                                                        <div class="form-group">
                                                            <div class="form-line float-left">
                                                                <select class="form-control show-tick" name="student" id="student-event">
                                                                    <option value="-1">--------</option>
                                                                    @foreach($students as $student)
                                                                        @if(isset($_GET['student']))
                                                                            <?php $firstStudent=null?>
                                                                            @if($student->userid==$_GET['student'])
                                                                                <option value="{{$student->userid}}" selected>{{$student->fullname }}</option>
                                                                            @else
                                                                                <option value="{{$student->userid}}" >{{$student->fullname }}</option>
                                                                            @endif
                                                                        @else
                                                                            <option  @if($firstStudent != null && $student->userid==$firstStudent)  selected @endif value="{{$student->userid}}" >{{$student->fullname}}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    @endif
                                    @if(\App\Users::isSchoolAdmin() or \App\Users::isSchoolManger() or \App\Users::isManhalAdmin() or \App\Users::isTeacher() && !\App\Users::isStudent())
                                    <form  id="filter-form" method="GET" action="{{url(URL::to('/').'/'.Lang::getLocale().'/calender')}}" >
                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 float-left">
                                            <div class="form-horizontal">
                                                <div class="row clearfix">
                                                    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                                                        <label class="float-left">@lang('lang.Parents')</label>
                                                    </div>
                                                    <div class="col-lg-8 col-md-8 col-sm-9 col-xs-9">
                                                        <div class="form-group">
                                                            <div class="form-line float-left">
                                                                <select class="form-control show-tick" name="parent" id="parent">
                                                                    <option value="-1">--------</option>
                                                                    @foreach($parents as $parent)
                                                                        @if(isset($_GET['parent']))
                                                                            @if($parent->userid==$_GET['parent'])
                                                                                <option value="{{$parent->userid}}" selected>{{$parent->fullname }}</option>
                                                                            @else
                                                                                <option  value="{{$parent->userid}}" >{{$parent->fullname }}</option>
                                                                            @endif
                                                                        @else
                                                                            <option  value="{{$parent->userid}}" >{{$parent->fullname}}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    @if(!\App\Users::isTeacher())
                                        <form  id="filter-form" method="GET" action="{{url(URL::to('/').'/'.Lang::getLocale().'/calender')}}" >
                                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 float-left">
                                        <div class="form-horizontal">
                                            <div class="row clearfix">
                                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-control-label">
                                                    <label class="float-left">@lang('lang.Teachers')</label>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-9">
                                                    <div class="form-group">
                                                        <div class="form-line float-left">
                                                            <select class="form-control show-tick" name="teacher" id="teacher">
                                                                <option value="-1">--------</option>
                                                                @foreach($teachers as $teacher)
                                                                    @if(isset($_GET['teacher']))
                                                                        @if($teacher->userid==$_GET['teacher'])
                                                                            <option value="{{$teacher->userid}}" selected>{{$teacher->fullname }}</option>
                                                                        @else
                                                                            <option  value="{{$teacher->userid}}" >{{$teacher->fullname }}</option>
                                                                        @endif
                                                                    @else
                                                                        <option  value="{{$teacher->userid}}" >{{$teacher->fullname}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                    @endif
                                @endif

                            </div>


                        <div class="row calender-main-container">
                            <div id='calendar'></div>
                        </div>
                        <div class="row">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $("#level").change(function(){
                $(this).closest("form").submit();
            });
            $("#class").change(function(){
                $(this).closest("form").submit();
            });
            $("#group").change(function(){
                $(this).closest("form").submit();
            });
            $("#student-event").change(function(){
                $(this).closest("form").submit();
            });
            $("#parent").change(function(){
                $(this).closest("form").submit();
            });
            $("#teacher").change(function(){
                $(this).closest("form").submit();
            });

            getEvent();
        });
    </script>
@endsection
