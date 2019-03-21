@extends('layout.app')
@section('title', __('lang.Quiz'))
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
                            <form id="filter-search-quiz" action="{{URL::to('/').'/'.Lang::getLocale().'/quiz'}}"  method="GET">
                                <input id="orderby" type="hidden" name="orderby" >
                                <input id="sort" type="hidden" name="descask" >
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 m-b-10 float-left">
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
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 m-b-10 float-left">
                                    <div class="form-horizontal">
                                        <div class="row clearfix">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 form-control-label">
                                                <label class="float-left">@lang('lang.Level')</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                <div class="form-group">
                                                    <div class="form-line float-left">
                                                        <select class="form-control show-tick jq_formdata" name="level" id="level" >
                                                            <option value="-1">-----</option>
                                                            @foreach($levels as $level)
                                                                @if(isset($_GET['level']))
                                                                    @if($level->level_id==$_GET['level'])
                                                                        <option value="{{$level->level_id}}" selected>{{$level["ltitle_".Lang::getLocale()] }}</option>
                                                                    @else
                                                                        <option  value="{{$level->level_id}}" >{{$level["ltitle_".Lang::getLocale()] }}</option>
                                                                    @endif
                                                                @else
                                                                    <option value="{{$level->level_id}}" >{{$level["ltitle_".Lang::getLocale()] }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 m-b-10 float-left">
                                    <div class="form-horizontal">
                                        <div class="row clearfix">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                                <label class="float-left">@lang('lang.Category')</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 col-xs-9">
                                                <div class="form-group">
                                                    <div class="form-line float-left">
                                                        <select class="form-control show-tick jq_formdata" name="category" id="category">
                                                            <option value="-1">------</option>
                                                            @foreach($categories as $category)
                                                                @if(isset($_GET['category']))
                                                                    @if($category->category_id==$_GET['category'])
                                                                        <option value="{{$category->category_id}}" selected>{{$category["title_".Lang::getLocale()] }}</option>
                                                                    @else
                                                                        <option  value="{{$category->category_id}}" >{{$category["title_".Lang::getLocale()] }}</option>
                                                                    @endif
                                                                @else
                                                                    <option value="{{$category->category_id}}" >{{$category["title_".Lang::getLocale()] }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(\Illuminate\Support\Facades\Auth::user()->permession <5 )
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 float-right">
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
                                        <a class="btn btn-primary waves-effect float-right" id="popupAddQuiz">@lang('lang.Add_Quiz')</a>
                                    </div>
                                @endif
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable js-exportable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="sorting_asc fixed-width-title QuizSortingTitle">@lang('lang.Title')</th>
                                    <th class="sorting_asc fixed-width-a QuizSortingDescription">@lang('lang.Description')</th>
                                    <th>@lang('lang.Level')</th>
                                    <th>@lang('lang.Category')</th>
                                    <th>@lang('lang.Action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = $quizs->currentpage() * $quizs->perpage() - $quizs->perpage();
                                    ?>
                                    @foreach($quizs as $quiz)
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td class="fixed-width-title name">{{$quiz->title}}</td>
                                            <td class="fixed-width-a" title="{{$quiz->description}}"><p>{{$quiz->description}}</p></td>
                                            <td>{{$quiz->infoLevel['ltitle_'.Lang::getLocale()]}}</td>
                                            <td>{{$quiz->infoCategory['title_'.Lang::getLocale()]}}</td>

                                            <td class="action">
                                                @if(\Illuminate\Support\Facades\Auth::user()->permession <5 )
                                                <a  id="editQuiz" data-id="{{$quiz->quiz_id}}"><i class="material-icons" title="@lang('lang.Edit')">edit</i></a>
                                                <a class="jqDeleteQuiz"  data-id="{{$quiz->quiz_id}}" data-action="{{url('/')."/".Lang::getLocale()}}/quiz/{{$quiz->quiz_id}}"><i class="material-icons" title="@lang('lang.Delete')">delete</i></a>
                                                <a data-id="{{$quiz->quiz_id}}" id="assignQuizTo"  title="@lang('lang.Assign_To')"><i class="flaticon1-add"></i></a>
                                                <a  quiz_id="{{$quiz->quiz_id}}" class="btn-mediaQuiz" title="@lang('lang.Media')"><i class="flaticon1-photo"></i></a>
                                                <a href="{{url('/')."/".Lang::getLocale()}}/quiz/browseassignment?quiz={{$quiz->quiz_id}}" title="@lang('lang.Browse_Assignment')"><i class="flaticon1-see"></i></a>
                                                @endif
                                                <a target="_blank" href="{{url('/')."/".Lang::getLocale()}}/quiz/{{$quiz->quiz_id}}" class="btn-assignlesson" title="@lang('lang.View')"><i class="material-icons">remove_red_eye</i></a>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info">
                                    <span class="pull-left">@lang('lang.Showing') </span> <span class="pull-left"> {{($quizs->currentpage()-1)*$quizs->perpage()+1}} </span> <span class="pull-left">@lang('lang.to') </span>
                                    <span class="pull-left">{{(($quizs->currentpage()-1)*$quizs->perpage())+$quizs->count()}} </span><span class="pull-left">@lang('lang.of')</span><span class="pull-left">{{$quizs->total()}}</span><span class="pull-left">@lang('lang.Quiz')</span>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                    {{$quizs->links()}}
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
            $("#category").change(function(){
                $(this).closest("form").submit();
            });
        });
    </script>
@endsection
