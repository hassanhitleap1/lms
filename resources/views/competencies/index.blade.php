@extends('layout.app')
@section('title', __('lang.Competencies'))
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
                            <form id="filter-competencies-search" action="{{URL::to('/').'/'.Lang::getLocale().'/competencies'}}" method="get">
                                <input id="orderby" type="hidden" name="orderby" >
                                <input id="sort" type="hidden" name="descask" >
                                <div class="col-md-5th-1 col-sm-12 col-xs-12 m-b-10 float-left">
                                    <div class="form-horizontal">
                                        <div class="row clearfix">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                                <label class="float-left">@lang('lang.Domain')</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                <div class="form-group">
                                                    <div class="form-line float-left">
                                                        <select class="form-control show-tick" name="domain" id="domain">
                                                            <option value="-1" selected >----</option>
                                                                @foreach($domains as $domain)
                                                                    @if(isset($_GET['domain']))
                                                                        @if($_GET['domain']==$domain->domain_id)
                                                                        <?php $pivots=$domain->pivots;?>
                                                                            <option selected  value="{{$domain->domain_id}}">{{$domain['title_'.App::getLocale()]}}</option>
                                                                        @else
                                                                            <option value="{{$domain->domain_id}}">{{$domain['title_'.App::getLocale()]}}</option>
                                                                        @endif
                                                                    @else
                                                                        <option value="{{$domain->domain_id}}">{{$domain['title_'.App::getLocale()]}}</option>
                                                                    @endif
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5th-1 col-sm-12 col-xs-12 m-b-10 float-left">
                                    <div class="form-horizontal">
                                        <div class="row clearfix">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                                <label class="float-left">@lang('lang.Pivot')</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                <div class="form-group">
                                                    <div class="form-line float-left">
                                                        <select class="form-control show-tick" name="pivot" id="pivot">
                                                            <option value="-1" selected >---- </option>
                                                            @foreach($pivots as $pivot)
                                                                @if(isset($_GET['pivot']))
                                                                    @if($_GET['pivot']==$pivot->pivot_id)
                                                                        <?php $standards=$pivot->standards?>
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
                                <div class="col-md-5th-1 col-sm-12 col-xs-12 m-b-10 float-left">
                                    <div class="form-horizontal">
                                        <div class="row clearfix">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                                <label class="float-left">@lang('lang.Standard')</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                <div class="form-group">
                                                    <div class="form-line float-left">
                                                        <select class="form-control show-tick" name="standard" id="standard">
                                                             <option value="-1" selected >---- </option>
                                                            @foreach($standards as $standard)
                                                                @if(isset($_GET['standard']))
                                                                    @if($_GET['standard']==$standard->standards)

                                                                        <option  selected value="{{$standard->standard_id}}">{{$standard['title_'.App::getLocale()]}}</option>
                                                                    @else
                                                                        <option value="{{$standard->standard_id}}">{{$standard['title_'.App::getLocale()]}}</option>
                                                                    @endif
                                                                @else
                                                                    <option value="{{$standard->standard_id}}">{{$standard['title_'.App::getLocale()]}}</option>
                                                                @endif

                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5th-1 col-sm-12 col-xs-12 m-b-10 float-left">
                                    <div class="form-horizontal">
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group search-container-a">
                                                    <div class="form-line float-left">
                                                        <input type="search" class="form-control input-sm" placeholder="@lang('lang.Search')"  name="search" value="{{ isset($_GET['search']) ? $_GET['search']: null }}">
                                                    </div>
                                                    <button class="btn btn-primary waves-effect float-left search-absolute"><i class="material-icons">search</i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(\Illuminate\Support\Facades\Auth::user()->permission <5)
                                <div class="col-md-5th-1 col-sm-12 col-xs-12 float-right m-b-10">
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
                                        <a class="btn btn-primary waves-effect float-right "  id="popup_addcompetencies">@lang('lang.Add_Competencies')</a>
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
                                <th class="sorting_asc CompetenciesSortingTitle">@lang('lang.Title')</th>
                                <th class="sorting_asc CompetenciesSortingDescription">@lang('lang.Description')</th>
                                <th>@lang('lang.Standard')</th>
                                @if(\App\Users::isManhalAdmin()  )
                                <th>@lang('lang.Action')</th>
                                @endif

                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=1;?>
                            @foreach($competencies as $competencie)
                                <tr>
                                    <td>{{ $i++}}</td>
                                    <td class="name">{{$competencie['title_'.App::getLocale()]}}</td>
                                    <td>{{$competencie['description_'.App::getLocale()]}}</td>
                                    <td><a class="gotopage">{{$competencie->standardInfo['title_'.App::getLocale()]}}</a></td>
                                    @if(\App\Users::isManhalAdmin()  )
                                    <td class="action">
                                        <a  id="edit_competencie" data-id="{{$competencie->compentence_id}}"><i class="material-icons" title="@lang('lang.Edit')">edit</i></a>
                                        <a class="jq_delete_user"  data-id="{{$competencie->compentence_id}}" data-action="{{url('/')."/".Lang::getLocale()}}/competencies/{{$competencie->compentence_id}}/delete"><i class="material-icons" title="@lang('lang.Delete')">delete</i></a>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info">
                                    <span class="pull-left">@lang('lang.Showing') </span><span class="pull-left">{{($competencies->currentpage()-1)*$competencies->perpage()+1}} </span> <span class="pull-left">@lang('lang.to') </span>
                                    <span class="pull-left">{{(($competencies->currentpage()-1)*$competencies->perpage())+$competencies->count()}} </span><span class="pull-left">@lang('lang.of')</span><span class="pull-left">{{$competencies->total()}}</span><span class="pull-left">@lang('lang.Competencies')</span>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                    {{$competencies->links()}}
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
            $("#domain").change(function(){
                $(this).closest("form").submit();
            });
            $("#pivot").change(function(){
                $(this).closest("form").submit();
            });
            $("#standard").change(function(){
                $(this).closest("form").submit();
            });
        });
    </script>
@endsection