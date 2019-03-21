@extends('layout.app')
@section('title', __('lang.Levels'))
@section('content')
    <script type="text/javascript">
        var url = "{{url(Lang::getLocale().'/levels')}}";
        var _token = "{{ csrf_token() }}";
        var lang="{{Lang::getLocale()}}";
    </script>
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>
    @php $lang=Lang::getLocale();@endphp
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 m-b-10 float-left">
                                <div class="form-horizontal">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group search-container-a">
                                                <div class="form-line float-left">
                                                    <form id="Levels" action="{{URL::to('/').'/'.Lang::getLocale().'/levels'}}"  method="GET">
                                                        <input type="search" class="form-control input-sm" placeholder="@lang('lang.Search')" aria-controls="DataTables_Table_0" name="search" value="{{ isset($_GET['search']) ? $_GET['search']: null }}">
                                                    </form>
                                                </div>
                                                <button class="btn btn-primary waves-effect float-left search-absolute"><i class="material-icons">search</i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(\App\Users::isManhalAdmin() OR  \App\Users::isSchoolManger() OR \App\Users::isSchoolAdmin() )
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 float-right">
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
                                <a class="btn btn-primary waves-effect float-right btn-addlevel" id="btn-addlevel">@lang('lang.Add_level')</a>
                            </div>
                            @endif
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable js-exportable">
                            <thead>
                            <tr>
                                <th>#</th>

                                <th class="@if(!empty($descask) && $descask=='ASC' && !empty($orderBy) && $orderBy=='ltitle_ar'){{'sorting_asc'}}@else sorting_desc @endif  LevelSortingName_Ar">@lang('lang.Name_Ar')</th>
                                <th class="@if(!empty($descask) && $descask=='ASC' && !empty($orderBy) && $orderBy=='ltitle_en'){{'sorting_asc'}}@else sorting_desc @endif  LevelSortingName_En">@lang('lang.Name_En')</th>
                                <th class="@if(!empty($descask) && $descask=='ASC' && !empty($orderBy) && $orderBy=='level_number'){{'sorting_asc'}}@else sorting_desc @endif LevelSortingName_order">@lang('lang.Sorting')</th>
                                <th class="@if(!empty($descask) && $descask=='ASC' && !empty($orderBy) && $orderBy=='updated_at'){{'sorting_asc'}}@else sorting_desc @endif LevelSortingName_Date">@lang('lang.Created_date')</th>
                                <th>@lang('lang.Action')</th>
                            </tr>
                            </thead>
                            <tbody class="tbody">
                            @if(count($levels)>0)
                                @foreach($levels as $key=>$levl)
                                    <tr>
                                        <td>{{$key}}</td>
                                        <td><span class="name">{{$levl->ltitle_ar}}</span></td>
                                        <td><span class="name">{{$levl->ltitle_en}}</span></td>
                                        <td>{{$levl->level_number}}</td>
                                        <td>{{$levl->updated_at}}</td>
                                        <td class="action">
                                            @if(\App\Users::isManhalAdmin() OR  \App\Users::isSchoolManger() OR \App\Users::isSchoolAdmin() )
                                            <a class="btn-editlevels" att="{{$levl->level_id}}" onclick="showpopup();" title="@lang('lang.Edit')"><i class="material-icons">edit</i></a>
                                            <a class="btn-deletelevels" att="{{$levl->level_id}}" title="@lang('lang.Delete')"> <i class="material-icons">delete</i></a>
                                            @endif
                                            <a href="{{asset(Lang::getLocale().'/curriculums?curricula_level='.$levl->level_id)}}" title="@lang('lang.Curriculums')"><i class="flaticon1-book"></i></a>
                                            <a href="{{asset(Lang::getLocale().'/students/?level='.$levl->level_id)}}" title="@lang('lang.show_students')"><i class="flaticon1-university"></i></a>
                                            <a href="{{asset(Lang::getLocale().'/teachers/?level='.$levl->level_id )}}" title="@lang('lang.Show_Teachers')"><i class="flaticon1-teacher"></i></a>
                                            <a href="{{url('/')."/".Lang::getLocale()}}/calender/?level={{$levl->level_id}}" ><i class="flaticon-calendar-with-a-clock-time-tools fi"  title="@lang('lang.Calender')"></i></a>
                                            <a   href="{{url('/')."/".Lang::getLocale()}}/classes/?level={{$levl->level_id}}"><i class="flaticon1-theater" title="@lang('lang.Classes')"></i></a>
                                            <a  levelid="{{$levl->level_id}}" class="btn-sendMessgeTolevelUsers" title="@lang('lang.Send_Message')"><i class="flaticon1-null"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        </div>
                        <div class="row">
         
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
