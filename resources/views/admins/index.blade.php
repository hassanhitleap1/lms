@extends('layout.app')
@section('title', __('lang.Admins'))
@section('content')
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap dataTables_filter">
                        <div class="row">
                            <form id="filter-search-admin" action="{{URL::to('/').'/'.Lang::getLocale().'/admins'}}"  method="GET">
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
                                @if(\App\Users::isManhalAdmin() OR  \App\Users::isSchoolManger())
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
                                    <a class="btn btn-primary waves-effect float-right" id="popup_addadmin">@lang('lang.Add_Admin')</a>
                                </div>
                                @endif
                            </form>
                        </div>
                        <div class="table-responsive">

                            @if(count($admins)>0)
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="sorting_asc AdminSortingName">@lang('lang.Name')</th>
                                    <th class="sorting_asc AdminSortingEmail">@lang('lang.Email')</th>
                                    <th class="sorting_asc AdminSortingPhone">@lang('lang.Phone')</th>
                                    <th class="sorting_desc AdminSortingCreateDate">@lang('lang.Created_date')</th>
                                    @if(\App\Users::isManhalAdmin() OR  \App\Users::isSchoolManger())
                                    <th>@lang('lang.Action')</th>
                                    @endif
                                </tr>
                                </thead>
                                <?php
                                    $i=$admins->currentpage()*$admins->perpage()-$admins->perpage();
                                ?>
                                <tbody>
                                @foreach($admins as $admin)

                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td><img src="{{asset($admin->avatar)}}" alt="User Avatar" class="img-circle avatar-table" width="400"><span class="name">{{$admin->fullname}}</span></td>
                                        <td>{{$admin->email}}</td>
                                        <td>{{$admin->phone}}</td>
                                        <td>{{$admin->created_at}}</td>
                                        @if(\App\Users::isManhalAdmin() OR  \App\Users::isSchoolManger())
                                        <td class="action"><a class="edit_user" data-id="{{$admin->userid}}"><i class="material-icons" title="@lang('lang.Edit')">edit</i></a>
                                            <a class="jq_delete_user"  data-id="{{$admin->userid}}" data-action="{{url('/')."/".Lang::getLocale()}}/admins/{{$admin->userid}}/delete"><i class="material-icons" title="@lang('lang.Delete')">delete</i></a>
                                            <a  userid="{{$admin->userid}}" class="btn-sendMessgeToUser" title="@lang('lang.Send_Message')"><i class="flaticon1-null"></i></a>
                                            <a id="rest_pass_admin" data-id="{{$admin->userid}}"><i class="material-icons" title="@lang('lang.Rest_Password')">vpn_key</i></a>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                        @endif
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                <div class="dataTables_info" >
                                    <span class="pull-left">@lang('lang.Showing') </span><span class="pull-left">{{($admins->currentpage()-1)*$admins->perpage()+1}} </span> <span class="pull-left">@lang('lang.to') </span>
                                    <span class="pull-left">{{(($admins->currentpage()-1)*$admins->perpage())+$admins->count()}} </span><span class="pull-left">@lang('lang.of')</span><span class="pull-left">{{$admins->total()}}</span><span class="pull-left">@lang('lang.Admins')</span>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                    {{$admins->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
