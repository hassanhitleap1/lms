@extends('layout.app')
@section('title', __('lang.Students'))
@section('content')
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <form id="filter-search-student" action="{{URL::to('/').'/'.Lang::getLocale().'/students'}}"  method="GET">
                                <input id="orderby" type="hidden" name="orderby" >
                                <input id="sort" type="hidden" name="descask" >
                                @if(! \App\Users::isStudent())
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 m-b-10 float-left">
                                    <div class="form-horizontal">
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group search-container-a">
                                                    <div class="form-line float-left">
                                                        <input type="search" class="form-control show-tick input-sm " placeholder="@lang('lang.Search')" aria-controls="DataTables_Table_0" id="search" name="search" value="{{ isset($_GET['search']) ? $_GET['search']: null }}" >
                                                    </div>
                                                    <button class="btn btn-primary waves-effect float-left search-absolute"><i class="material-icons">search</i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    @if(!\App\Users::isParent())
                                    <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12 m-b-10 float-left">
                                        <div class="form-horizontal">
                                            <div class="row clearfix">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 form-control-label">
                                                    <label class="float-left">@lang('lang.Level')</label>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="form-line float-left">
                                                            <select class="form-control show-tick jq_formdata level" name="level"id="level">
                                                                <option value="-1">-----</option>
                                                                @foreach($levels as $level)
                                                                    @if(isset($_GET['level']))

                                                                        @if($level->level_id==$_GET['level'])
                                                                            <option data-id="{{$level->level_id}}" value="{{$level->level_id}}" selected>{{$level["ltitle_".Lang::getLocale()] }}</option>

                                                                        @else
                                                                            <option data-id="{{$level->level_id}}" value="{{$level->level_id}}" >{{$level["ltitle_".Lang::getLocale()] }}</option>
                                                                        @endif
                                                                    @else
                                                                        <option data-id="{{$level->level_id}}" value="{{$level->level_id}}" >{{$level["ltitle_".Lang::getLocale()] }}</option>
                                                                    @endif

                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12 m-b-10 float-left">
                                        <div class="form-horizontal">
                                            <div class="row clearfix">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 form-control-label">
                                                    <label class="float-left">@lang('lang.Class')</label>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="form-line float-left">
                                                            <select class="form-control show-tick jq_formdata" name="class" id="class">
                                                                <option value="-1">-----</option>
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
                                @endif
                                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12 m-b-10 float-left">
                                        <div class="form-horizontal">
                                            <div class="row clearfix">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 form-control-label">
                                                    <label class="float-left">@lang('Groups')</label>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="form-line float-left">
                                                            <select class="form-control show-tick jq_formdata level" name="group"id="group">
                                                                <option value="-1">-----</option>

                                                                @foreach($groups as $group)
                                                                    @if(isset($_GET['group']))
                                                                        @if($group->group_id==$_GET['group'])
                                                                            <option value="{{$group->group_id}}" selected>{{$group["title_".Lang::getLocale()] }}</option>
                                                                        @else
                                                                            <option  value="{{$group->group_id}}" >{{$group["title_".Lang::getLocale()] }}</option>
                                                                        @endif
                                                                    @else
                                                                        <option value="{{$group->group_id}}" >{{$group["title_".Lang::getLocale()] }}</option>
                                                                    @endif

                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12 float-right">
                                    <ul class="header-dropdown float-right mobile-hide">
                                        <li class="dropdown">
                                            <a class="dropdown-toggle" style="cursor: pointer">
                                                <i class="material-icons fullscreen-icon">fullscreen</i>
                                                <i style="display: none" class="material-icons fullscreen-exit-icon">fullscreen_exit</i>
                                            </a>
                                            <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                                                <i class="material-icons">local_printshop</i>
                                            </a>
                                            <ul class="dropdown-menu pull-right"></ul>
                                        </li>
                                    </ul>
                                    @if(\App\Users::isManhalAdmin() OR  \App\Users::isSchoolManger() OR \App\Users::isSchoolAdmin() )
                                        <a class="btn btn-primary waves-effect float-right"  id="popup_addstudent" >@lang('lang.Add_Student')</a>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable js-exportable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th class="sorting_asc StudentSortingName">@lang('lang.Name')</th>
                                <th class="sorting_asc StudentSortingEmail">@lang('lang.Email')</th>
                                <th class="sorting_asc StudentSortingPhone">@lang('lang.Phone')</th>
                                <th class="sorting_asc StudentSortingBirth_of_Date">@lang('lang.Birth_of_Date')</th>
                                <th class="sorting_asc StudentSortingLevel">@lang('lang.Level')</th>
                                <th class="sorting_asc StudentSortingClass">@lang('lang.Class')</th>
                                <th>@lang('lang.Action')</th>

                            </tr>
                            </thead>
                            <tbody>
                                                     <?php
                                                    $i = $students->currentpage() * $students->perpage() - $students->perpage();
                                                    ?>
                            @foreach($students as $student)
                                <tr>
                                    <td>{{++$i}}</td>
                                    <td><img src="{{asset($student->avatar)}}" alt="User Avatar" class="img-circle avatar-table" width="400"><span class="name">{{$student->fullname}}</span></td>
                                    <td>{{$student->email}}</td>
                                    <td>{{$student->phone}}</td>
                                    <td>{{$student->birthdate}}</td>
                                    <?php $studentArray=(array) $student?>
                                    <td>{{$studentArray['ltitle_'.Lang::getLocale()]}}</td>
                                    <td>{{$studentArray['ctitle_'.Lang::getLocale()]}}</td>

                                    <td class="action">
                                        @if(\App\Users::isManhalAdmin() OR  \App\Users::isSchoolManger() OR \App\Users::isSchoolAdmin() )
                                            <a class="" id="edit_student" data-id="{{$student->userid}}"><i class="material-icons" title="@lang('lang.Edit')">edit</i></a>
                                            <a class="jq_delete_user"  data-id="{{$student->userid}}" data-action="{{url('/')."/".Lang::getLocale()}}/students/{{$student->userid}}/delete"><i class="material-icons" title="@lang('lang.Delete')">delete</i></a>
                                            <a id="rest_pass_student" data-id="{{$student->userid}}"><i class="material-icons" title="@lang('lang.Rest_Password')">vpn_key</i></a>
                                            <a  href="{{url('/')."/".Lang::getLocale()}}/classes/?class={{$student->class}}"><i class="flaticon1-theater" title="@lang('lang.Classes')"></i></a>
                                            <a  href="{{url('/')."/".Lang::getLocale()}}/groups/?student={{$student->userid}}"><i class="flaticon-multiple-users-silhouette fi" title="@lang('lang.Group')"></i></a>
                                            <a href="{{url('/')."/".Lang::getLocale().'/levels/?level='.$student->userlevel}}" ><i class="flaticon1-graph fi"  title="@lang('lang.Levels')"></i></a>
                                       @endif
                                        <a  userid="{{$student->userid}}" class="btn-sendMessgeToUser" title="@lang('lang.Send_Message')"><i class="flaticon1-null"></i></a>
                                        @if(! \App\Users::isStudent())
                                            <a href="{{url('/')."/".Lang::getLocale()}}/calender/?student={{$student->userid}}" ><i class="flaticon-calendar-with-a-clock-time-tools fi"  title="@lang('lang.Calender')"></i></a>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info" >
                                    <span class="pull-left">@lang('lang.Showing') </span><span class="pull-left">{{($students->currentpage()-1)*$students->perpage()+1}} </span> <span class="pull-left">@lang('lang.to') </span>
                                    <span class="pull-left">{{(($students->currentpage()-1)*$students->perpage())+$students->count()}} </span><span class="pull-left">@lang('lang.of')</span><span class="pull-left">{{$students->total()}}</span><span class="pull-left">@lang('lang.Students')</span>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                    {{$students->links()}}
                                </div>
                            </div>

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
        });


    </script>
@endsection




