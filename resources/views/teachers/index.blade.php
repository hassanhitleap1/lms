@extends('layout.app')
@section('title', __('lang.Teachers'))

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
                                <form id="search-form-teacher" action="{{URL::to('/').'/'.Lang::getLocale().'/teachers'}}"  method="GET">
                                    <input id="orderby" type="hidden" name="orderby" >
                                    <input id="sort" type="hidden" name="descask" >
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 m-b-10 float-left">
                                        <div class="form-horizontal">
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="form-group search-container-a">
                                                        <div class="form-line float-left">
                                                            <input type="search" class="form-control input-sm" placeholder="@lang('lang.Search')" aria-controls="DataTables_Table_0" name="search" value="{{ isset($_GET['search']) ? $_GET['search']: null }}">
                                                        </div>
                                                        <button class="btn btn-primary waves-effect float-left search-absolute"><i class="material-icons">search</i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if(\App\Users::isManhalAdmin() OR  \App\Users::isSchoolManger() OR \App\Users::isSchoolAdmin())
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 m-b-10 float-right">
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
                                        <a class="btn btn-primary waves-effect float-right"  id="popup_addteacher">@lang('lang.Add_Teacher')</a>
                                    </div>
                                    @endif
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="sorting_asc TeacherSortingName">@lang('lang.Name')</th>
                                        <th class="sorting_asc TeacherSortingEmail">@lang('lang.Email')</th>
                                        <th class="sorting_asc TeacherSortingPhone"> @lang('lang.Phone')</th>
                                        <th >@lang('lang.Home_room_level')</th>
                                        <th >@lang('lang.Home_room_class')</th>
                                        <th class="sorting_asc TeacherSortingCreated_At">@lang('lang.Created_date')</th>
                                        <th>@lang('lang.Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                                                <?php
                                                                $i = $teachers->currentpage() * $teachers->perpage() - $teachers->perpage();
                                                                ?>
                                @foreach($teachers as $teacher)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td><img src="{{asset($teacher->avatar)}}" alt="User Avatar" class="img-circle avatar-table" width="400"><span class="name">{{$teacher->fullname}}</span></td>
                                        <td>{{$teacher->email}}</td>
                                        <td>{{$teacher->phone}}</td>
                                        <td>{{$teacher->userLevel['ltitle_'.App::getLocale()]}}</td>
                                        <td>{{$teacher->userClass['ctitle_'.App::getLocale()]}}</td>
                                        <td>{{$teacher->created_at}}</td>

                                        <td class="action">
                                            @if(\App\Users::isManhalAdmin() OR  \App\Users::isSchoolManger() OR \App\Users::isSchoolAdmin())
                                                <a class="" id="edit_teacher" data-id="{{$teacher->userid}}"><i class="material-icons" title="@lang('lang.Edit')">edit</i></a>
                                                <a class="jq_delete_user"  data-id="{{$teacher->userid}}" data-action="{{url('/')."/".Lang::getLocale()}}/teachers/{{$teacher->userid}}/delete"><i class="material-icons" title="@lang('lang.Delete')">delete</i></a>
                                                <a  data-id="{{$teacher->userid}}" href="{{url('/')."/".Lang::getLocale()}}/classes/?teacher={{$teacher->userid}}"><i class="flaticon1-theater" title="@lang('lang.Classes')"></i></a>
                                                <a  data-id="{{$teacher->userid}}" href="{{url('/')."/".Lang::getLocale()}}/groups/?teacher={{$teacher->userid}}"><i class="flaticon-multiple-users-silhouette fi" title="@lang('lang.Group')"></i></a>
                                                <a href="{{url('/')."/".Lang::getLocale().'/levels/?teacher='.$teacher->userid}}" ><i class="flaticon1-graph fi"  title="@lang('lang.Levels')"></i></a>
                                                <a href="{{url('/')."/".Lang::getLocale().'/homework/?teacher='.$teacher->userid}}" ><i class="flaticon1-note fi"  title="@lang('lang.Homeworks')"></i></a>
                                                <a href="{{url('/')."/".Lang::getLocale()}}/calender/?teacher={{$teacher->userid}}" ><i class="flaticon-calendar-with-a-clock-time-tools fi"  title="@lang('lang.Calender')"></i></a>
                                                <a id="rest_pass_teacher" data-id="{{$teacher->userid}}"><i class="material-icons" title="@lang('lang.Rest_Password')">vpn_key</i></a>
                                            @endif
                                            <a  userid="{{$teacher->userid}}" class="btn-sendMessgeToUser" title="@lang('lang.Send_Message')"><i class="flaticon1-null"></i></a>

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                            </table>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="dataTables_info" >
                                        <span class="pull-left">@lang('lang.Showing') </span><span class="pull-left">{{($teachers->currentpage()-1)*$teachers->perpage()+1}} </span> <span class="pull-left">@lang('lang.to') </span>
                                        <span class="pull-left">{{(($teachers->currentpage()-1)*$teachers->perpage())+$teachers->count()}} </span><span class="pull-left">@lang('lang.of')</span><span class="pull-left">{{$teachers->total()}}</span><span class="pull-left">@lang('lang.Teachers')</span>
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                    {{$teachers->links()}}
                                </div>
                            </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

@endsection
