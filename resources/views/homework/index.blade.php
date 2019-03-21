@extends('layout.app')
@section('title', __('lang.Homework'))
@section('content')
    <script type="text/javascript">
        var url = "{{url(Lang::getLocale().'/homework')}}";
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
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 float-left">
                                <div class="form-horizontal">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 m-b-10 col-xs-12">
                                            <form action="{{url(Lang::getLocale().'/homework' )}}" method="GET" id="homework-filter">
                                                <input id="orderby" type="hidden" name="orderby" >
                                                <input id="sort" type="hidden" name="descask" >
                                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 m-b-10 float-left">
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
                                                @if( ! \App\Users::isStudent())
                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 m-b-10 float-left">
                                                    <div class="form-horizontal">
                                                        <div class="row clearfix">
                                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-control-label">
                                                                <label class="float-left">@lang('lang.Levels')</label>
                                                            </div>
                                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                                <div class="form-group">
                                                                    <div class="form-line float-left">
                                                                        <select class="form-control show-tick jq_formdata level" name="level"id="level">
                                                                            <option value="-1">-----</option>
                                                                            @foreach($levels as $level)
                                                                                @if(isset($_GET['level']))
                                                                                    @if($level->level_id==$_GET['level'])
                                                                                        <option  value="{{$level->level_id}}" selected>{{$level["ltitle_".Lang::getLocale()] }}</option>

                                                                                    @else
                                                                                        <option value="{{$level->level_id}}" >{{$level["ltitle_".Lang::getLocale()] }}</option>
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
                                                 @endif
                                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 m-b-10 float-left">
                                                    <div class="form-horizontal">
                                                        <div class="row clearfix">
                                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-control-label">
                                                                <label class="float-left">@lang('lang.Category')</label>
                                                            </div>
                                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                                <div class="form-group">
                                                                    <div class="form-line float-left">
                                                                        <select class="form-control show-tick jq_formdata " name="category"id="category">
                                                                            <option value="-1">-----</option>
                                                                            @foreach($categories as $category)
                                                                                @if(isset($_GET['category']))
                                                                                    @if($category->category_id==$_GET['category'])
                                                                                        <option  value="{{$category->category_id}}" selected>{{$category["title_".Lang::getLocale()] }}</option>

                                                                                    @else
                                                                                        <option value="{{$category->category_id}}" >{{$category["title_".Lang::getLocale()] }}</option>
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
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(\App\Users::isManhalAdmin() OR  \App\Users::isSchoolManger() OR \App\Users::isSchoolAdmin() OR \App\Users::isTeacher())
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12  float-right">
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
                                    <a class="btn btn-primary waves-effect float-right btn-addhomework" onclick="showpopup();">@lang('lang.Add_Homework')</a>
                                </div>
                            @endif
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable js-exportable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="fixed-width sorting_desc HomeworkSortingTitle">@lang('lang.Title')</th>
                                    <th class="fixed-width sorting_desc HomeworkSortingDescription">@lang('lang.Description')</th>
                                    <th>@lang('lang.Category')</th>
                                    <th>@lang('lang.Teachers')</th>
                                    <th class="sorting_desc  HomeworkSortingName_Date ">@lang('lang.Created_date')</th>
                                    <th>@lang('lang.Action')</th>
                                </tr>
                                </thead>
                                <tbody class="tbody">
                                @if(count($homework)>0)
                                    @foreach($homework as $key=>$hom)
                                        <tr>
                                            <td>{{$key}}</td>
                                            <td class="fixed-width name"><span title="{{$hom->title}}" class="name">{{$hom->title}}</span></td>
                                            <td class="fixed-width"><span title="{{$hom->description}}" class="name"><p>{{$hom->description}}</p></span></td>
                                            <td><span title="{{$hom->category}}" class="name"><?=$hom->categoryInfo["title_".Lang::getLocale()]?></span></td>
                                            <td>{{$hom->createBy->fullname}}</td>
                                            <td><span title="{{$hom->created_at}}" class="name">{{$hom->created_at}}</span></td>
                                            <td class="action">
                                                @if(\App\Users::isManhalAdmin() OR  \App\Users::isSchoolManger() OR \App\Users::isSchoolAdmin() OR \App\Users::isTeacher())
                                                    <a class="btn-edithomework" att="{{$hom->homework_id}}" onclick="showpopup();" title="@lang('lang.Edit')"><i class="material-icons">edit</i></a>
                                                    <a class="btn-deletehomeworks" att="{{$hom->homework_id}}" title="@lang('lang.Delete')"> <i class="material-icons">delete</i></a>
                                                    <a target="_blank" href="{{url('/')."/".Lang::getLocale()}}/homework/{{$hom->homework_id}}/edit" att="{{$hom->homework_id}}" class="btn-mediahomework" title="@lang('lang.Media')"><i class="flaticon1-photo"></i></a>
                                                    <a onclick="showpopup();" att="{{$hom->homework_id}}" class="btn-assignhomework" title="@lang('lang.Assign_homework')"><i class="flaticon1-data"></i></a>
                                                    <a href="{{url('/')."/".Lang::getLocale()}}/homework/browseassignment?homework={{$hom->homework_id}}" title="@lang('lang.Browse_Assignment')"><i class="flaticon1-see"></i></a>
                                                @endif
                                                <a target="_blank" href="{{url('/')."/".Lang::getLocale()}}/homework/{{$hom->homework_id}}" class="btn-assignlesson" title="@lang('lang.View')"><i class="material-icons">remove_red_eye</i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info" >
                                    <span class="pull-left">@lang('lang.Showing') </span><span class="pull-left">{{($homework->currentpage()-1)*$homework->perpage()+1}} </span> <span class="pull-left">@lang('lang.to') </span>
                                    <span class="pull-left">{{(($homework->currentpage()-1)*$homework->perpage())+$homework->count()}} </span><span class="pull-left">@lang('lang.of')</span><span class="pull-left">{{$homework->total()}}</span><span class="pull-left">@lang('lang.Homework')</span>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                    <?= str_replace('page-link','page-link-2' ,$homework->links())?>
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

        $(document).on('click', '.GroupsSortingName', function (e) {
            e.preventDefault();
            var orderby='title_'+Language;
            var sorting = getUrlParameter('descask');
            if (sorting == 'ASC') {
                sorting = 'DESC';
            } else if (sorting == 'DESC') {
                sorting = 'ASC';
            } else {
                sorting = 'ASC';
            }

            if ($(this).hasClass('GroupsSortingName')) {
                orderby='title_'+Language;
            }
            $('#orderby').attr({
                value: orderby
            }).appendTo('#orderby');
            $('#sort').attr({
                value: sorting
            }).appendTo('#sort');

            document.getElementById('filter-search-group').submit();
        });

    </script>
@endsection