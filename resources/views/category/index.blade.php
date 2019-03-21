@extends('layout.app')
@section('title', __('lang.Category'))
@section('content')
    <script type="text/javascript">
        var lang="{{Lang::getLocale()}}";
        var url = "{{url(Lang::getLocale().'/category')}}";
        var _token = "{{ csrf_token() }}";

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
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12  m-b-10 float-left">
                               <div class="form-horizontal">
                                   <div class="row clearfix">
                                       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                           <div class="form-group search-container-a">
                                               <div class="form-line float-left">
                                                   {!! Form::open(array('id'=>'Category', 'method'=>'GET', 'url'=>Lang::getLocale().'/category' )) !!}
                                                   <input type="search" name="search" class="form-control input-sm CategoryKeywords" placeholder="@lang('lang.Search')" value="@if(!empty($search)){{$search}}@endif" aria-controls="DataTables_Table_0">
                                                   @if(!empty($search))
                                                       {!! Form::hidden('search',$search) !!}
                                                   @endif
                                                   {!! Form::close() !!}                                               </div>
                                               <button class="btn btn-primary waves-effect float-left search-absolute"><i class="material-icons">search</i></button>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                           @if(\App\Users::isManhalAdmin() OR  \App\Users::isSchoolManger() OR \App\Users::isSchoolAdmin() )
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
                               <a class="btn btn-primary waves-effect float-right btn-addcategory"
                                  onclick="showpopup();">@lang('lang.Add_Category')</a>
                               <a class="btn btn-primary waves-effect float-right btn-sortcategory"
                                  onclick="showpopup();">@lang('lang.Category_Sort')</a>
                           </div>
                           @endif
                       </div>
                       <div class="table-responsive">
                           <table class="table table-bordered table-striped table-hover js-basic-example dataTable js-exportable">
                           <thead>
                           <tr>
                               <th>#</th>
                               <th class="@if(!empty($descask) && $descask=='ASC' && !empty($orderBy) && $orderBy=='title_ar'){{'sorting_asc'}}@else sorting_desc @endif  CategorySortingName_Ar">@lang('lang.Name_Ar')</th>
                               <th class="@if(!empty($descask) && $descask=='ASC' && !empty($orderBy) && $orderBy=='title_en'){{'sorting_asc'}}@else sorting_desc @endif  CategorySortingName_En">@lang('lang.Name_En')</th>
                               <th class="@if(!empty($descask) && $descask=='ASC' && !empty($orderBy) && $orderBy=='order'){{'sorting_asc'}}@else sorting_desc @endif CategorySortingName_order">@lang('lang.Sorting')</th>
                               <th class="@if(!empty($descask) && $descask=='ASC' && !empty($orderBy) && $orderBy=='updated_at'){{'sorting_asc'}}@else sorting_desc @endif CategorySortingName_Date">@lang('lang.Created_date')</th>
                               <th>@lang('lang.Action')</th>

                           </tr>
                           </thead>
                           <tbody class="tbody">
                           @if(count($category)>0)
                               @foreach($category as $key=>$cat)
                                   <tr>
                                       <td>{{$key}}</td>
                                       <td><span class="">{{$cat->title_ar}}</span></td>
                                       <td><span class="name">{{$cat->title_en}}</span></td>
                                       <td>{{$cat->order}}</td>
                                       <td>{{$cat->updated_at}}</td>

                                       <td class="action">
                                           @if(\App\Users::isManhalAdmin() OR  \App\Users::isSchoolManger() OR \App\Users::isSchoolAdmin() )
                                               <a class="btn-editcategory" att="{{$cat->category_id}}" onclick="showpopup();" title="@lang('lang.Edit')"><i class="material-icons">edit</i></a>
                                               <a class="btn-deletecategory" att="{{$cat->category_id}}" title="@lang('lang.Delete')"> <i class="material-icons">delete</i></a>
                                           @endif
                                           <a href="{{asset(Lang::getLocale().'/curriculums?curricula_cat='.$cat->category_id)}}" title="@lang('lang.Curriculums')"><i class="flaticon1-book"></i></a>
                                       </td>
                                   </tr>
                               @endforeach
                           @endif
                           </tbody>
                       </table>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>

@endsection
