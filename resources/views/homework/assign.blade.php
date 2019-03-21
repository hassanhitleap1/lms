@section('title', 'Assign_homework')
@php $lang=Lang::getLocale();@endphp
{{App::setLocale($lang)}}

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs tab-nav-right" role="tablist">
            <li   att_idhomework="{{$idhomework}}" att_active="1"  id="tabGroup" role="presentation"  class="@if($type=='group') {{'active'}}@endif"><a href="#home_animation_2" data-toggle="tab">@lang('lang.Groups')</a></li>
            <li   att_idhomework="{{$idhomework}}" att_active="0"  id="tabClasses" role="presentation" class="@if($type=='classes') {{'active'}}@endif"><a href="#profile_animation_2" data-toggle="tab">@lang('lang.Classes')</a></li>
            <li   att_idhomework="{{$idhomework}}" att_active="0"  id="tabStudent" role="presentation" class="@if($type=='student') {{'active'}}@endif"><a href="#student_animation_2" data-toggle="tab">@lang('lang.Students')</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <?php
            $startdate=date("Y/m/d");
            $enddate=date("Y/m/d");
            ?>
                @if($type=='group')

            <div role="tabpanel" class="tab-pane animated fadeInRight active" id="home_animation_2">
                <div class="row clearfix assign-homework-container">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="body table-responsive no-padding">
                                <div class="dataTables_wrapper form-inline dt-bootstrap specify-content-table">
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('lang.Group_name')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($data)&&count($data)>0)
                                            @foreach($data as $items=>$item)
                                        <tr>
                                            <td>{{$items}}</td>
                                            <td>
                                                <div class="demo-checkbox">
                                                    <input type="checkbox" att_id="{{$item->group_id}}" id="Group_checkbox{{$items}}" class="filled-in"  />
                                                    <label for="Group_checkbox{{$items}}">{{$item->{"title_".Lang::getLocale()} }}</label>
                                                </div>
                                            </td>
                                        </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="dataTables_info">
                                                <span class="pull-left">@lang('lang.Showing')</span><span
                                                        class="pull-left">{{($data->currentpage()-1)*$data->perpage()+1}}</span>
                                                <span class="pull-left">@lang('lang.to') </span>
                                                <span class="pull-left"> {{(($data->currentpage()-1)*$data->perpage())+$data->count()}}</span><span
                                                        class="pull-left">@lang('lang.of')</span><span
                                                        class="pull-left">{{$data->total()}}</span><span
                                                        class="pull-left">@lang('lang.Group')</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                            <div att_tab="{{$type}}" att_idhomework="{{$idhomework}}" class="dataTables_paginate paging_simple_numbers"
                                                 id="DataTables_Table_0_paginate_2">
                                                {{$data->links()}}

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 m-t-10">
                        <div class="form-group">
                            <div class="form-line">
                                <label>@lang('lang.Start_Date')</label>
                                <input id="startdatehomework" type="text" class="datepicker form-control jq_formdata" placeholder="@lang('lang.Start_Date')"  data-dtp="dtp_ZYZzi"  value="{{$startdate}}" onclick="loadPicker()"  onfocus="loadPicker()" onmouseover="loadPicker()" >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 m-t-10">
                        <div class="form-group">
                            <div class="form-line">
                                <label>@lang('lang.End_Date')</label>
                                <input id="enddatehomework" type="text" class="datepicker form-control jq_formdata" placeholder="@lang('lang.End_Date')"  data-dtp="dtp_ZYZzi"  value="{{$enddate}}" onclick="loadPicker()"  onfocus="loadPicker()" onmouseover="loadPicker()" >
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <a att_tab="{{$type}}" att_idhomework="{{$idhomework}}" class="btn btn-primary waves-effect add_assign_homework" >@lang('lang.Assign_homework')</a>
                </div>
            </div>
            @endif
                @if($type=='classes')
            <div role="tabpanel" class="tab-pane animated fadeInRight" id="profile_animation_2">
                <div class="dataTables_wrapper">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 float-left">
                        <form class="form-horizontal">
                            <div class="row clearfix">
                                <div class="col-lg-1 col-md-1 col-sm-2 col-xs-12 form-control-label">
                                    <label class="float-left">@lang('lang.Level')</label>
                                </div>
                                <div class="col-lg-11 col-md-11 col-sm-10 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line float-left">
                                            <select att_class="{{$classe_id}}" att_type="classes" att_idhomework="{{$idhomework}}"  id="level_assign_select" class="form-control show-tick">
                                                @if(isset($levels)&&count($levels)>0)
                                                    @foreach($levels as $items=>$item)
                                                        {{$select=''}}
                                                    @if($item->level_id==$level_id)
                                                            {{$select='selected'}}
                                                      @endif
                                                    <option {{$select}} value="{{$item->level_id}}">{{$item->{"ltitle_".Lang::getLocale()} }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row clearfix assign-homework-container">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="body table-responsive no-padding">
                                <div class="dataTables_wrapper form-inline dt-bootstrap">
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('lang.Class_name')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($data)&&count($data)>0)
                                            @foreach($data as $items=>$item)
                                        <tr>
                                            <td>{{$items}}</td>
                                            <td>
                                                <div class="demo-checkbox">
                                                    <input type="checkbox" att_id="{{$item->class_id}}"  id="Class_checkbox{{$items}}" class="filled-in"  />
                                                    <label for="Class_checkbox{{$items}}">{{$item->{"ctitle_".Lang::getLocale()} }}</label>
                                                </div>
                                            </td>
                                        </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                            <div class="dataTables_info">
                                                <span class="pull-left">@lang('lang.Showing')</span><span
                                                        class="pull-left">{{($data->currentpage()-1)*$data->perpage()+1}}</span>
                                                <span class="pull-left">@lang('lang.to') </span>
                                                <span class="pull-left"> {{(($data->currentpage()-1)*$data->perpage())+$data->count()}}</span><span
                                                        class="pull-left">@lang('lang.of')</span><span
                                                        class="pull-left">{{$data->total()}}</span><span
                                                        class="pull-left">@lang('lang.Class')</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                            <div att_tab="{{$type}}" att_level="{{$level_id}}" att_idhomework="{{$idhomework}}" class="dataTables_paginate paging_simple_numbers"
                                                 id="DataTables_Table_0_paginate_2">
                                                {{$data->links()}}

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 m-t-10">
                        <div class="form-group">
                            <div class="form-line">
                                <label>@lang('lang.Start_Date')</label>
                                <input id="startdatehomework" type="text" class="datepicker form-control jq_formdata" placeholder="@lang('lang.Start_Date')"  data-dtp="dtp_ZYZzi"  value="{{$startdate}}" onclick="loadPicker()"  onfocus="loadPicker()" onmouseover="loadPicker()"  >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 m-t-10">
                        <div class="form-group">
                            <div class="form-line">
                                <label>@lang('lang.End_Date')</label>
                                <input id="enddatehomework" type="text" class="datepicker form-control jq_formdata" placeholder="@lang('lang.End_Date')"  data-dtp="dtp_ZYZzi"  value="{{$enddate}}" onclick="loadPicker()"  onfocus="loadPicker()" onmouseover="loadPicker()"  >
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <a att_level="{{$level_id}}" att_tab="{{$type}}" att_idhomework="{{$idhomework}}" class="btn btn-primary waves-effect add_assign_homework" >@lang('lang.Assign_homework')</a>
                </div>
            </div>
                @endif
                @if($type=='student')
            <div role="tabpanel" class="tab-pane animated fadeInRight" id="student_animation_2">
                <div class="dataTables_wrapper">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 float-left m-t-10">
                        <form class="form-horizontal">
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 form-control-label">
                                    <label class="float-left">@lang('lang.Level')</label>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line float-left">
                                            <select att_class="{{$classe_id}}" att_type="student"  att_idhomework="{{$idhomework}}"  id="level_assign_select" class="form-control show-tick">
                                                @if(isset($levels)&&count($levels)>0)
                                                    @foreach($levels as $items=>$item)
                                                        {{$select=''}}
                                                        @if($item->level_id==$level_id)
                                                            {{$select='selected'}}
                                                        @endif
                                                        <option {{$select}} value="{{$item->level_id}}">{{$item->{"ltitle_".Lang::getLocale()} }}</option>
                                                    @endforeach
                                                @endif


                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 float-left m-t-10">
                        <form class="form-horizontal">
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 form-control-label">
                                    <label class="float-left">@lang('lang.Class')</label>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line float-left">
                                            <select  att_type="student" att_idhomework="{{$idhomework}}"  id="classes_assign_select" class="form-control show-tick">
                                                @if(isset($classes)&&count($classes)>0)
                                                    @foreach($classes as $items=>$item)
                                                        {{$select=''}}
                                                        @if($item->class_id==$classe_id)
                                                            {{$select='selected'}}
                                                        @endif
                                                        <option {{$select}} value="{{$item->class_id}}">{{$item->{"ctitle_".Lang::getLocale()} }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 float-left m-t-10">
                        <form class="form-horizontal">
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 form-control-label">
                                    <label class="float-left">@lang('lang.Group')</label>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line float-left">
                                            <select att_type="student" att_idhomework="{{$idhomework}}" id="group_assign_select" class="form-control show-tick">
                                                @if(isset($group)&&count($group)>0)
                                                    <option  value=-1>------------------</option>
                                                    @foreach($group as $items=>$item)
                                                        {{$select=''}}
                                                        @if($item->group_id==$group_id &&$student_fillter==1)
                                                            {{$select='selected'}}
                                                        @endif
                                                        <option {{$select}} value="{{$item->group_id}}">{{$item->{"title_".Lang::getLocale()} }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row clearfix assign-homework-container">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="body table-responsive no-padding">
                                <div class="dataTables_wrapper form-inline dt-bootstrap">
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('lang.Student_name')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($data)&&count($data)>0)
                                            @foreach($data as $items=>$item)
                                        <tr>
                                            <td>{{$items}}</td>
                                            <td>
                                                <div class="demo-checkbox">
                                                    <input type="checkbox" att_id="{{$item->userid}}" id="student_checkbox{{$items}}" class="filled-in"  />
                                                    <label for="student_checkbox{{$items}}">{{$item->fullname}}</label>
                                                </div>
                                            </td>
                                        </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                            <div class="dataTables_info">
                                                <span class="pull-left">@lang('lang.Showing')</span><span
                                                        class="pull-left">{{($data->currentpage()-1)*$data->perpage()+1}}</span>
                                                <span class="pull-left">@lang('lang.to') </span>
                                                <span class="pull-left"> {{(($data->currentpage()-1)*$data->perpage())+$data->count()}}</span><span
                                                        class="pull-left">@lang('lang.of')</span><span
                                                        class="pull-left">{{$data->total()}}</span><span
                                                        class="pull-left">@lang('lang.Group')</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                            <div  att_class="{{$classe_id}}" att_tab="{{$type}}" att_idhomework="{{$idhomework}}" class="dataTables_paginate paging_simple_numbers"
                                                 id="DataTables_Table_0_paginate_2">
                                                {{$data->links()}}

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 m-t-10">
                        <div class="form-group">
                            <div class="form-line">
                                <label>@lang('lang.Start_Date')</label>
                                <input id="startdatehomework" type="text" class="datepicker form-control jq_formdata" placeholder="@lang('lang.Start_Date')"  data-dtp="dtp_ZYZzi"  value="{{$startdate}}" onclick="loadPicker()"  onfocus="loadPicker()" onmouseover="loadPicker()"  >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 m-t-10">
                        <div class="form-group">
                            <div class="form-line">
                                <label>@lang('lang.End_Date')</label>
                                <input id="enddatehomework" type="text" class="datepicker form-control jq_formdata" placeholder="@lang('lang.End_Date')"  data-dtp="dtp_ZYZzi"  value="{{$enddate}}" onclick="loadPicker()"  onfocus="loadPicker()" onmouseover="loadPicker()"  >
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <a att_level="{{$level_id}}" att_tab="{{$type}}" att_idhomework="{{$idhomework}}" class="btn btn-primary waves-effect add_assign_homework" ">@lang('lang.Assign_homework')</a>
                </div>
            </div>
                @endif
        </div>
    </div>
</div>
