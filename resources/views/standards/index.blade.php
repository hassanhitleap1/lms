@extends('layout.app')
@section('title', __('lang.Standards'))
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
                                <form id="filter-standard-search" action="{{URL::to('/').'/'.Lang::getLocale().'/standards/filter'}}" method="get">
                                    <input id="orderby" type="hidden" name="orderby" >
                                    <input id="sort" type="hidden" name="descask" >
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 float-left">
                                        <div class="form-horizontal">
                                            <div class="row clearfix">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                                    <label class="float-left">@lang('lang.Category')</label>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                    <div class="form-group">
                                                        <div class="form-line float-left">
                                                            <select class="form-control show-tick jq_formdata" name="category"id="category">
                                                                    <option value="-1" selected >----</option>
                                                                    @foreach($categories as $category)
                                                                    @if(isset($_GET['category']))
                                                                        @if($_GET['category']==$category->category_id)
                                                                            <?php $domains=$category->infoDomains;?>
                                                                            <option  selected value="{{$category->category_id}}">{{$category['title_'.App::getLocale()]}}</option>
                                                                        @else
                                                                            <option value="{{$category->category_id}}">{{$category['title_'.App::getLocale()]}}</option>
                                                                        @endif
                                                                    @else
                                                                        <option value="{{$category->category_id}}">{{$category['title_'.App::getLocale()]}}</option>
                                                                    @endif
    
                                                                @endforeach                                                                    

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 float-left">
                                        <div class="form-horizontal">
                                            <div class="row clearfix">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                                    <label class="float-left">@lang('lang.Domain')</label>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                    <div class="form-group">
                                                        <div class="form-line float-left">
                                                            <select class="form-control show-tick jq_formdata" name="domain"id="domain">
                                                                <option value="-1" selected >----</option>
                                                                @foreach($domains as $domain)
                                                                    @if(isset($_GET['domain']))
                                                                        @if($_GET['domain']==$domain->domain_id)
                                                                            <?php $pivots=$domain->pivots;?>
                                                                            <option selected  value="{{$domain->domain_id}}">{{$domain['title_'.App::getLocale()]}}</option>
                                                                        @else
                                                                            <option   value="{{$domain->domain_id}}">{{$domain['title_'.App::getLocale()]}}</option>
                                                                        @endif
                                                                    @else
                                                                        <option   value="{{$domain->domain_id}}">{{$domain['title_'.App::getLocale()]}}</option>
                                                                    @endif

                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 float-left">
                                            <div class="form-horizontal">
                                                <div class="row clearfix">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                                        <label class="float-left">@lang('lang.Pivot')</label>

                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                        <div class="form-group">
                                                            <div class="form-line float-left">
                                                                <select class="form-control show-tick jq_formdata" name="pivot"id="pivot">
                                                                    <option value="-1" selected >----</option>
                                                                    @foreach($pivots as $pivot)
                                                                    @if(isset($_GET['pivot']))
                                                                        @if($_GET['pivot']==$pivot->pivot_id)
                                                                            <option selected  value="{{$pivot->pivot_id}}">{{$pivot['title_'.App::getLocale()]}}</option>
                                                                        @else
                                                                            <option value="{{$pivot->pivot_id}}">{{$pivot['title_'.App::getLocale()]}}</option>
                                                                        @endif
                                                                    @else
                                                                        <option value="{{$pivot->pivot_id}}">{{$pivot['title_'.App::getLocale()]}}</option>
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
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 float-right m-t-10">
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
                                        <a class="btn btn-primary waves-effect float-right btn-addstandards"  id="popup_addstandard">@lang('lang.Add_Standards')</a>
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
                                <th class="sorting_asc StandardSortingNumber">@lang('lang.Standard_Number')</th>
                                <th class="sorting_asc StandardSortingTitle">@lang('lang.Title')</th>
                                <th class="sorting_asc StandardSortingDescription">@lang('lang.Description')</th>
                                <th class="sorting_asc StandardSortingCategory">@lang('lang.Category')</th>
                                <th class="sorting_asc StandardSortingDomain">@lang('lang.Domain')</th>
                                <th class="sorting_asc StandardSortingPivot">@lang('lang.Pivot')</th>
                                <th >@lang('lang.Action')</th>
                            </tr>
                            </thead>
                                   <?php
                                    $i = $standards->currentpage() * $standards->perpage() - $standards->perpage();
                                    ?>
                            <tbody>
                                @foreach($standards as $standard)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$standard->standard_number}}</td>
                                        <td class="name">{{$standard['title_'.App::getLocale()]}}</td>
                                        <td>{{$standard['description_'.App::getLocale()]}}</td>
                                        <td>{{$standard->categoryInfo['title_'.App::getLocale()]}}</td>
                                        <td>{{$standard->domainInfo['title_'.App::getLocale()]}}</td>
                                        <td>{{$standard->pivotInfo['title_'.App::getLocale()]}}</td>
                                        <td class="action">
                                            @if(\App\Users::isManhalAdmin()  )
                                            <a id="edit_standard" data-id="{{$standard->standard_id}}"><i class="material-icons" title="@lang('lang.Edit')">edit</i></a>
                                            <a class="jq_delete_user"  data-id="{{$standard->standard_id}}" data-action="{{url('/')."/".Lang::getLocale()}}/standards/{{$standard->standard_id}}/delete"><i class="material-icons" title="@lang('lang.Delete')">delete</i></a>
                                            @endif
                                            <a href="{{asset(Lang::getLocale().'/competencies?standard='.$standard->standard_id)}}" title="@lang('lang.Show_Competencies')"><i class="flaticon1-list"></i></a>
                                            <a href="{{url('/')."/".Lang::getLocale()}}/lessons?standard={{$standard->standard_id}}" title="@lang('lang.Show_Lessons')"><i class="flaticon1-blackboard"></i></a>
                                            <a href="{{asset(Lang::getLocale().'/curriculums?standard='.$standard->standard_id)}}" title="@lang('lang.Curriculums')"><i class="flaticon1-book"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info">
                                    <span class="pull-left">@lang('lang.Showing') </span><span class="pull-left">{{($standards->currentpage()-1)*$standards->perpage()+1}} </span> <span class="pull-left">@lang('lang.to') </span>
                                    <span class="pull-left">{{(($standards->currentpage()-1)*$standards->perpage())+$standards->count()}} </span><span class="pull-left">@lang('lang.of')</span><span class="pull-left">{{$standards->total()}}</span><span class="pull-left">@lang('lang.Standards')</span>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                        {{$standards->links()}}
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
            $("#pivot").change(function(){
                $(this).closest("form").submit();
            });
        });
    </script>
@endsection
