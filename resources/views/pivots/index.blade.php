@extends('layout.app')
@section('title', __('lang.Pivots'))
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
                            <form id="filter-pivot-search" action="{{URL::to('/').'/'.Lang::getLocale().'/pivots'}}"  method="GET">
                                <input id="orderby" type="hidden" name="orderby" >
                                <input id="sort" type="hidden" name="descask" >
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 m-b-10 float-left">
                                    <div class="form-horizontal">
                                        <div class="row clearfix">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                                <label class="float-left">@lang('lang.Category')</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                <div class="form-group">
                                                    <div class="form-line float-left">
                                                        <select class="form-control show-tick jq_formdata" name="category"id="category">
                                                            <option value="-1">-----</option>
                                                            @foreach($categories as $category)
                                                                @if(isset($_GET['category']))

                                                                    @if($category->category_id==$_GET['category'])
                                                                        <?php $domains=$category->infoDomains;?>
                                                                        <option data-id="{{$category->category_id}}" value="{{$category->category_id}}" selected>{{$category["title_".Lang::getLocale()] }}</option>
                                                                    @else
                                                                        <option data-id="{{$category->category_id}}" value="{{$category->category_id}}" >{{$category["title_".Lang::getLocale()] }}</option>
                                                                    @endif
                                                                @else
                                                                    <option data-id="{{$category->category_id}}" value="{{$category->category_id}}" >{{$category["title_".Lang::getLocale()] }}</option>
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
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                                <label class="float-left">@lang('lang.Domain')</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                <div class="form-group">
                                                    <div class="form-line float-left">
                                                        <select class="form-control show-tick jq_formdata" name="domain"id="domain">
                                                            <option value="-1">-----</option>
                                                            @foreach($domains as $domain)
                                                                @if(isset($_GET['domain']))

                                                                    @if($domain->domain_id==$_GET['domain'])
                                                                        <option data-id="{{$domain->domain_id}}" value="{{$domain->domain_id}}" selected>{{$domain["title_".Lang::getLocale()] }}</option>
                                                                    @else
                                                                        <option data-id="{{$domain->domain_id}}" value="{{$domain->domain_id}}" >{{$domain["title_".Lang::getLocale()] }}</option>
                                                                    @endif
                                                                @else
                                                                    <option data-id="{{$domain->domain_id}}" value="{{$domain->domain_id}}" >{{$domain["title_".Lang::getLocale()] }}</option>
                                                                @endif

                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(\Illuminate\Support\Facades\Auth::user()->permission <5)
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
                                    @if(\App\Users::isManhalAdmin())
                                    <a class="btn btn-primary waves-effect float-right btn-addPivots" id="popup_addpivot">@lang('lang.Add_Pivots')</a>
                                    @endif
                                </div>
                                @endif
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable js-exportable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th class="sorting_asc PivotSortingNumber">@lang('lang.Pivot_Number')</th>
                                <th class="sorting_asc PivotSortingTitle">@lang('lang.Title')</th>
                                <th class="sorting_asc PivotSortingDescription">@lang('lang.Description')</th>
                                <th class="sorting_asc PivotSortingCategory">@lang('lang.Category')</th>
                                <th class="sorting_asc PivotSortingDomain">@lang('lang.Domain')</th>
                                <th >@lang('lang.Action')</th>
                            </tr>
                            </thead>
                                <?php
                                $i = $pivots->currentpage() * $pivots->perpage() - $pivots->perpage();
                                ?>
                            <tbody>
                                @foreach ($pivots as $pivot)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$pivot->pivotnumber }}</td>
                                        <td class="name">{{$pivot['title_'.App::getLocale()]}}</td>
                                        <td>{{$pivot['description_'.App::getLocale()]}}</td>
                                        <td>{{$pivot->categoryInfo['title_'.App::getLocale()]}}</td>
                                        <td>{{$pivot->domainInfo['title_'.App::getLocale()]}}</td>
                                        <td class="action">
                                            @if(\App\Users::isManhalAdmin()  )
                                            <a id="edit_pivot" data-id="{{$pivot->pivot_id}}"><i class="material-icons" title="@lang('lang.Edit')">edit</i></a>
                                            <a class="jq_delete_user"  data-id="{{$pivot->pivot_id}}" data-action="{{url('/')."/".Lang::getLocale()}}/pivots/{{$pivot->pivot_id}}/delete"><i class="material-icons" title="@lang('lang.Delete')">delete</i></a>
                                            @endif
                                            <a href="{{asset(Lang::getLocale().'/standards?pivot='.$pivot->pivot_id)}}" title="@lang('lang.Show_Standards')"><i class="flaticon1-done"></i></a>
                                            <a href="{{asset(Lang::getLocale().'/competencies?pivot='.$pivot->pivot_id.'')}}" title="@lang('lang.Show_Competencies')"><i class="flaticon1-list"></i></a>
                                            <a href="{{asset(Lang::getLocale().'/curriculums?pivot='.$pivot->pivot_id)}}" title="@lang('lang.Curriculums')"><i class="flaticon1-book"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info">
                                    <span class="pull-left">@lang('lang.Showing') </span><span class="pull-left">{{($pivots->currentpage()-1)*$pivots->perpage()+1}} </span> <span class="pull-left">@lang('lang.to') </span>
                                    <span class="pull-left">{{(($pivots->currentpage()-1)*$pivots->perpage())+$pivots->count()}} </span><span class="pull-left">@lang('lang.of')</span><span class="pull-left">{{$pivots->total()}}</span><span class="pull-left">@lang('lang.Pivots')</span>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                        {{$pivots->links()}}
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
            $("#category").change(function(){
                $(this).closest("form").submit();
            });
            $("#domain").change(function(){
                $(this).closest("form").submit();
            });
        });
    </script>
@endsection
