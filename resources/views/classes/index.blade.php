@extends('layout.app')
@section('title', __('lang.Class'))
@section('content')
    <script type="text/javascript">
        var url = "{{url(Lang::getLocale().'/classes')}}";
        var _token = "{{ csrf_token() }}";
        var lang="{{Lang::getLocale()}}";
    </script>
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <form id="search_class" action="{{URL::to('/').'/'.Lang::getLocale().'/classes/'}}" method="get">
                                <input id="orderby" type="hidden" name="orderby" >
                                <input id="sort" type="hidden" name="descask" >
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 m-b-10 float-left">
                                    <div class="form-horizontal">
                                        <div class="row clearfix">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                                <label class="float-left">@lang('lang.Level')</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                <div class="form-group">
                                                    <div class="form-line float-left">
                                                        <select class="form-control show-tick jq_formdata" name="level"id="level">
                                                                <option value="-1" selected >----</option>
                                                                @foreach($levels as $level)
                                                                    @if(isset($_GET['level']))
                                                                        @if($_GET['level']==$level->level_id)
                                                                            <option selected  value="{{$level->level_id}}">{{$level['ltitle_'.App::getLocale()]}}</option>
                                                                        @else
                                                                            <option value="{{$level->level_id}}">{{$level['ltitle_'.App::getLocale()]}}</option>
                                                                        @endif
                                                                    @else
                                                                        <option value="{{$level->level_id}}">{{$level['ltitle_'.App::getLocale()]}}</option>
                                                                    @endif
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(\Illuminate\Support\Facades\Auth::user()->permession < 4)
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 m-b-10 float-left">
                                    <div class="form-horizontal">
                                        <div class="row clearfix">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                                <label class="float-left">@lang('lang.Teacher')</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                <div class="form-group">
                                                    <div class="form-line float-left">
                                                        <select class="form-control show-tick jq_formdata" name="teacher"id="teacher">
                                                            <option value="-1" selected >----</option>
                                                            @foreach($teachers as $teacher)
                                                                @if(isset($_GET['teacher']))
                                                                    @if($_GET['teacher']==$teacher->userid)
                                                                        <option selected  value="{{$teacher->userid}}">{{$teacher->fullname}}</option>
                                                                    @else
                                                                        <option value="{{$teacher->userid}}">{{$teacher->fullname}}</option>
                                                                    @endif
                                                                @else
                                                                    <option value="{{$teacher->userid}}">{{$teacher->fullname}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if(\App\Users::isManhalAdmin() OR  \App\Users::isSchoolManger() OR \App\Users::isSchoolAdmin())
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 float-right">
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
                                    <a class="btn btn-primary waves-effect float-right btn-addclass"  id="popup_addclass">@lang('lang.Add_Class')</a>
                                </div>
                                @endif
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable js-exportable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th class="sorting_asc ClassSortingLevel">@lang('lang.Level')</th>
                                <th class="sorting_asc ClassSortingName">@lang('lang.Name_'.ucfirst(App::getLocale()))</th>
                                <th>@lang('lang.Home_Room_Teacher')</th>
                                <th>@lang('lang.Number_of_students')</th>
                                <th>@lang('lang.Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i=$classes->currentpage()*$classes->perpage()-$classes->perpage();
                                ?>                                
                            @foreach($classes as $class)
                            <tr>
                                <td>{{++$i}}</td>
                                <?php $cl=(array) $class;?>
                                <td>{{$cl['ltitle_'.App::getLocale()]}}</td>
                                <td class="name">{{$cl['ctitle_'.App::getLocale()]}}</td>
                                    <td>{{$class->homeroom}}</td>
                                <td>{{$class->students_count}}</td>
                                <td class="action">
                                    @if(\App\Users::isManhalAdmin() OR  \App\Users::isSchoolManger()  OR \App\Users::isSchoolAdmin() )
                                        <a id="edit_class" data-id="{{$class->class_id}}"> <i class="material-icons" title="@lang('lang.Edit')">edit</i> </a>
                                        <a class="jq_delete_user"  data-id="{{$class->class_id}}" data-action="{{url('/')."/".Lang::getLocale()}}/classes/{{$class->class_id}}/delete"> <i class="material-icons" title="@lang('lang.Delete')">delete</i> </a>
                                    @endif
                                        <a href="{{url('/')."/".Lang::getLocale()}}/calender/?level={{$class->level_id}}&class={{$class->class_id}}" ><i class="flaticon-calendar-with-a-clock-time-tools fi"  title="@lang('lang.Calender')"></i></a>
                                    @if(Auth::user()->permession < 4)
                                        <a href="{{url('/')."/".Lang::getLocale()}}/teachers/?class={{$class->class_id}}" ><i class="flaticon1-teacher   fi"  title="@lang('lang.Teacher')"></i></a>
                                    @endif
                                        <a href="{{url('/')."/".Lang::getLocale()}}/students/?&class={{$class->class_id}}" class="btn-show-Groups" title="@lang('lang.show_students')"><i class="flaticon1-university"></i></a>
                                     <a  classid="{{$class->class_id}}" class="btn-sendMessgeToClassUsers" title="@lang('lang.Send_Message')"><i class="flaticon1-null"></i></a>


                                </td>
                              </tr>   
                            @endforeach

                            </tbody>
                        </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info" >
                                    <span class="pull-left">@lang('lang.Showing') </span><span class="pull-left">{{($classes->currentpage()-1)*$classes->perpage()+1}} </span> <span class="pull-left">@lang('lang.to') </span>
                                    <span class="pull-left">{{(($classes->currentpage()-1)*$classes->perpage())+$classes->count()}} </span><span class="pull-left">@lang('lang.of')</span><span class="pull-left">{{$classes->total()}}</span><span class="pull-left">@lang('lang.Classes')</span>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                        {{$classes->links()}}
                                    </div>
                            </div>
                        </div>

    </div>

    <script>
        $(document).ready(function () {
            $("#level").change(function(){
                $(this).closest("form").submit();
            });
            $("#teacher").change(function(){
                $(this).closest("form").submit();
            });
        });


        $(document).on('click', '.ClassSortingLevel,.ClassSortingName', function (e) {
            e.preventDefault();
            var orderby='ctitle_'+Language;
            var sorting = getUrlParameter('descask');
            if (sorting == 'ASC') {
                sorting = 'DESC';
            } else if (sorting == 'DESC') {
                sorting = 'ASC';
            } else {
                sorting = 'ASC';
            }

            if ($(this).hasClass('ClassSortingLevel')) {
                orderby = 'ltitle_'+Language;
            } else if ($(this).hasClass('ClassSortingName')) {
                orderby='ctitle_'+Language;
            }
            $('#orderby').attr({
                value: orderby
            }).appendTo('#orderby');
            $('#sort').attr({
                value: sorting
            }).appendTo('#sort');

            document.getElementById('search_class').submit();
        });

    </script>
@endsection
