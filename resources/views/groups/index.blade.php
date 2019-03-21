@extends('layout.app')
@section('title', __('lang.Groups'))
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
                            <form id="filter-search-group" action="{{URL::to('/').'/'.Lang::getLocale().'/groups'}}"  method="GET">
                                <input id="orderby" type="hidden" name="orderby" >
                                <input id="sort" type="hidden" name="descask" >
                                @if(\Illuminate\Support\Facades\Auth::user()->permession < 4)
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 m-b-10 float-left">
                                    <div class="form-horizontal">
                                        <div class="row clearfix">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                                <label class="float-left">@lang('lang.Teachers')</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 col-xs-9">
                                                <div class="form-group">
                                                    <div class="form-line float-left">
                                                        <select name="teacher" id="teacher" class="form-control show-tick ">
                                                            <option value="-1">------</option>
                                                            <?php
                                                            foreach($teachers as $teacher){
                                                            ?>
                                                            <option value="<?=$teacher["userid"];?>" <?php if(isset($_GET["teacher"]) && $_GET["teacher"]==$teacher["userid"]){echo 'selected="selected"';}?> ><?=$teacher["fullname"];?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </form>

                            @if(\App\Users::isManhalAdmin() OR  \App\Users::isSchoolManger() OR \App\Users::isSchoolAdmin() OR \App\Users::isTeacher() )
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 float-right">
                                <a class="btn btn-primary waves-effect float-right" id="popup_addgroup">@lang('lang.Add_Group')</a>
                            </div>
                            @endif
                        </div>
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th class="sorting_asc GroupsSortingName">@lang('lang.Group_Name')</th>
                                <th>@lang('lang.Teacher')</th>
                                <th>@lang('lang.Number_of_students')</th>
                                <th>@lang('lang.Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                            <?php
                            $i=$groups->currentpage()*$groups->perpage()-$groups->perpage();
                            ?>
                                @foreach ($groups as $group)
                                <tr>
                                    <td>{{++$i}}</td>
                                    <td class="name">{{$group['title_'.Lang::getLocale()]}}</td>
                                    <td>{{$group->teacherInfo['fullname']}}</td>
                                    <td>{{$group->assings->count()}}</td>
                                    <td class="action">
                                        @if(\App\Users::isManhalAdmin() OR  \App\Users::isSchoolManger() OR \App\Users::isSchoolAdmin() OR \App\Users::isTeacher() )
                                            <a  id="edit_group" data-id="{{$group->group_id}}"><i class="material-icons" title="@lang('lang.Edit')">edit</i></a>
                                            <a class="jq_delete_user"  data-id="{{$group->group_id}}" data-action="{{url('/')."/".Lang::getLocale()}}/groups/{{$group->group_id}}/delete"><i class="material-icons" title="@lang('lang.Delete')">delete</i></a>
                                            <a data-id="{{$group->group_id}}" id="assignStudent"  title="@lang('lang.Assign_Student')"><i class="flaticon1-add"></i></a>
                                        @endif
                                            <a href="{{url('/')."/".Lang::getLocale()}}/calender/?group={{$group->group_id}}" ><i class="flaticon-calendar-with-a-clock-time-tools fi"  title="@lang('lang.Calender')"></i></a>
                                            <a  groupid="{{$group->group_id}}" class="btn-sendMessgeToGroupUsers" title="@lang('lang.Send_Message')"><i class="flaticon1-null"></i></a>

                                        <a href="{{url('/')."/".Lang::getLocale()}}/students/?group={{$group->group_id}}" class="btn-show-Groups" title="@lang('lang.show_students')"><i class="flaticon1-university"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info" >
                                    <span class="pull-left">@lang('lang.Showing') </span><span class="pull-left">{{($groups->currentpage()-1)*$groups->perpage()+1}} </span> <span class="pull-left">@lang('lang.to') </span>
                                    <span class="pull-left">{{(($groups->currentpage()-1)*$groups->perpage())+$groups->count()}} </span><span class="pull-left">@lang('lang.of')</span><span class="pull-left">{{$groups->total()}}</span><span class="pull-left">@lang('lang.Groups')</span>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                        {{$groups->links()}}
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
            $("#teacher").change(function(){
                $(this).closest("form").submit();
            });
        });
    </script>
@endsection
