@extends('layout.app')
@section('title', __('lang.Lessons'))
@section('content')
    <script type="text/javascript">
        var url = "{{url(Lang::getLocale().'/lessons')}}";
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
                            <form id="lesson_form" method="get">
                                <input id="orderby" type="hidden" name="orderby" value=-1>
                                <input id="sort" type="hidden" name="descask" value=-1>
                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 m-b-10 float-left">
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
                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 float-left">
                                    <div class="row clearfix">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                            <label class="float-left">@lang('lang.Levels')</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                            <div class="form-group">
                                                <div class="form-line float-left">
                                                    <select class="form-control autosubmit show-tick" name="level">
                                                        <option value="-1">---------</option>
                                                        <?php
                                                        foreach($levels as $level){
                                                            if(isset($_GET["level"]) && $_GET["level"]==$level["level_id"] ){
                                                                $selected='selected="selected"';
                                                            }else{
                                                                $selected='';
                                                            }
                                                            echo '<option value="'.$level["level_id"].'" '.$selected.'>'.$level["ltitle_".Lang::getLocale()].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 float-left">
                                    <div class="row clearfix">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                            <label class="float-left">@lang('lang.Categories')</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                            <div class="form-group">
                                                <div class="form-line float-left">
                                                    <select class="form-control autosubmit show-tick" name="category">
                                                        <option value="-1">---------</option>
                                                        <?php
                                                        foreach($categories as $category){
                                                            if(isset($_GET["category"]) && $_GET["category"]==$category["category_id"] ){
                                                                $selected='selected="selected"';
                                                            }else{
                                                                $selected='';
                                                            }
                                                            echo '<option value="'.$category["category_id"].'" '.$selected.'>'.$category["title_".Lang::getLocale()].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 float-left">
                                    <div class="row clearfix">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                            <label class="float-left">@lang('lang.curricula')</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                            <div class="form-group">
                                                <div class="form-line float-left">
                                                    <select class="form-control autosubmit show-tick" name="curricula">
                                                        <option value="-1">---------</option>
                                                        <?php
                                                        foreach($curriculums as $curricula){
                                                            if(isset($_GET["curricula"]) && $_GET["curricula"]==$curricula["curriculumsid"] ){
                                                                $selected='selected="selected"';
                                                            }else{
                                                                $selected='';
                                                            }
                                                            echo '<option value="'.$curricula["curriculumsid"].'" '.$selected.' >'.$curricula["cu_title_".Lang::getLocale()].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(\Illuminate\Support\Facades\Auth::user()->permession <=3)
                                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 float-left">
                                        <div class="row clearfix">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                                <label class="float-left">@lang('lang.cerate_by')</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                                <div class="form-group">
                                                    <div class="form-line float-left">
                                                        <select class="form-control autosubmit show-tick" name="cerate_by">
                                                            <option value="-1">---------</option>
                                                            @foreach($creaters as $creater)
                                                                @if(isset($_GET['cerate_by']))
                                                                    @if($creater->userid==$_GET['cerate_by'])
                                                                        <option value="{{$creater->userid}}" selected>{{$creater->fullname }}</option>
                                                                    @else
                                                                        <option  value="{{$creater->userid}}" >{{$creater->fullname }}</option>
                                                                    @endif
                                                                @else
                                                                    <option  value="{{$creater->userid}}" >{{$creater->fullname}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </form>
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 float-right m-t-10">
                                <ul class="header-dropdown float-right">
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
                                @if(\Illuminate\Support\Facades\Auth::user()->permession <5 )
                                    <a class="btn btn-primary waves-effect float-right btn-addLessons">@lang('lang.Add_Lessons')</a>
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable js-exportable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="sorting_asc fixed-width">@lang('lang.Title')</th>
                                    <th class="sorting_asc LessonsSortingDescription fixed-width">@lang('lang.Description')</th>
                                    <th>@lang('lang.Curriculum')</th>
                                    <th>@lang('lang.Category')</th>
                                    <th>@lang('lang.Level')</th>
                                    <th>@lang('lang.Teacher')</th>
                                    <th>@lang('lang.Action')</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                $i=$Lessons->currentpage()*$Lessons->perpage()-$Lessons->perpage();
                                foreach($Lessons as $lesson){
                                ?>
                                <tr>
                                    <td>{{++$i}}</td>
                                    <td class="fixed-width name">{{$lesson->title}}</td>
                                    <td class="fixed-width" title="{{$lesson->description}}"><p>{{$lesson->description}}</p></td>
                                    <td>{{$lesson->{"cu_title_".Lang::getLocale()} }}</td>
                                    <td>{{$lesson->{"title_".Lang::getLocale()} }}</td>
                                    <td>{{$lesson->{"ltitle_".Lang::getLocale()} }}</td>
                                    <td>{{$lesson->fullname}}</td>
                                    <td class="action">
                                        @if(\Illuminate\Support\Facades\Auth::user()->permession <4 || \Illuminate\Support\Facades\Auth::user()->userid==$lesson->teacher)
                                            <a att_id="{{$lesson->id}}" class="btn-editLessons" title="@lang('lang.Edit')"><i class="material-icons">edit</i></a>
                                            <a att_id="{{$lesson->id}}" class="btn-deleteLessons" title="@lang('lang.Delete')"><i class=" material-icons">delete</i></a>
                                        @endif
                                        @if(\Illuminate\Support\Facades\Auth::user()->permession <4  || \Illuminate\Support\Facades\Auth::user()->userid==$lesson->teacher)
                                            <a target="_blank" href="<?php echo route('lesson.editor', ['lang'=>Lang::getLocale(),'id' => $lesson->id]); ?>" class="btn-assignlesson" title="@lang('lang.Media')"><i class="flaticon1-photo"></i></a>
                                        @endif

                                        <a target="_blank" href="<?php echo route('lesson.viewer', ['lang'=>Lang::getLocale(),'id' => $lesson->id]); ?>" class="btn-assignlesson" title="@lang('lang.View')"><i class="material-icons">remove_red_eye</i></a>

                                        @if(\Illuminate\Support\Facades\Auth::user()->permession <5 )
                                            <a att_id="{{$lesson->id}}" class="btn-assignToLessonGroup" title="@lang('lang.Assign_Lesson')"><i class="flaticon1-data"></i></a>
                                        @endif

                                    </td>

                                </tr>
                                <?php
                                }
                                ?>


                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                <div class="dataTables_info" >
                                    <span class="pull-left">@lang('lang.Showing') </span><span class="pull-left">{{($Lessons->currentpage()-1)*$Lessons->perpage()+1}} </span> <span class="pull-left">@lang('lang.to') </span>
                                    <span class="pull-left">{{(($Lessons->currentpage()-1)*$Lessons->perpage())+$Lessons->count()}} </span><span class="pull-left">@lang('lang.of')</span><span class="pull-left">{{$Lessons->total()}}</span><span class="pull-left">@lang('lang.Admins')</span>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                    {{$Lessons->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
