@extends('layout.app')
@section('title', __('lang.Domains'))
@section('content')
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body ">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <form id="filter-domain-search" action="{{URL::to('/').'/'.Lang::getLocale().'/domains'}}"  method="GET">
                                <input id="orderby" type="hidden" name="orderby" >
                                <input id="sort" type="hidden" name="descask" >
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 m-b-10 float-left">
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
                                @if(\Illuminate\Support\Facades\Auth::user()->permission <5)
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 float-right">
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
                                    <a class="btn btn-primary waves-effect float-right btn-adddomain"  id="popup_adddomains">@lang('lang.Add_Domains')</a>
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
                                <th class="sorting_asc DomainSortingNumber">@lang('lang.Domain_Number')</th>
                                <th class="sorting_asc DomainSortingTitle">@lang('lang.Title')</th>
                                <th class="sorting_asc DomainSortingDescription">@lang('lang.Description')</th>
                                <th class="sorting_asc DomainSortingCategory">@lang('lang.Category')</th>
                                <th>@lang('lang.Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i=$domaines->currentpage()*$domaines->perpage()-$domaines->perpage();
                            ?>
                                @foreach($domaines as $domain)
                                    <tr>
                                        <?php $dom= (array)$domain;?>
                                        <td>{{++$i}}</td>
                                        <td>{{$domain->domainnumber}}</td>
                                        <td class="name"><?=$dom['title_'.Lang::getLocale()];?></td>
                                        <td><?=$dom['description_'.Lang::getLocale()];?></td>
                                        <td><?=$dom['ctitle_'.Lang::getLocale()];?></td>
                                        <td class="action">
                                            @if(\App\Users::isManhalAdmin() )
                                            <a  id="edit_domain" data-id="{{$domain->domain_id}}"><i class="material-icons" title="@lang('lang.Edit')">edit</i></a>
                                            <a class="jq_delete_user"  data-id="{{$domain->domain_id}}" data-action="{{url('/')."/".Lang::getLocale()}}/domains/{{$domain->domain_id}}/delete"><i class="material-icons" title="@lang('lang.Delete')">delete</i></a>
                                            @endif
                                            <a href="{{asset(Lang::getLocale().'/standards?domain='.$domain->domain_id)}}" title="@lang('lang.Show_Standards')"><i class="flaticon1-donei"></i></a>
                                            <a href="{{asset(Lang::getLocale().'/pivots/?domain='.$domain->domain_id)}}" title="@lang('lang.Show_Pivots')"><i class="flaticon1-menu"></i></a>
                                            <a href="{{asset(Lang::getLocale().'/competencies?domain='.$domain->domain_id)}}" title="@lang('lang.Show_Competencies')"><i class="flaticon1-list"></i></a>
                                            <a href="{{asset(Lang::getLocale().'/curriculums?domain='.$domain->domain_id)}}" title="@lang('lang.Curriculums')"><i class="flaticon1-book"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info">
                                    <span class="pull-left">@lang('lang.Showing') </span><span class="pull-left">{{($domaines->currentpage()-1)*$domaines->perpage()+1}} </span> <span class="pull-left">@lang('lang.to') </span>
                                    <span class="pull-left">{{(($domaines->currentpage()-1)*$domaines->perpage())+$domaines->count()}} </span><span class="pull-left">@lang('lang.of')</span><span class="pull-left">{{$domaines->total()}}</span><span class="pull-left">@lang('lang.Domains')</span>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                        {{$domaines->links()}}
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
        });
    </script>
@endsection
