@section('title', 'Add')


<div class="clearfix lessonid" id="lessonid" att_id="{{$lesson->id}}">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 float-left">
            <form class="form-horizontal">
                <div class="row clearfix">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 form-control-label">
                        <label class="float-left">@lang('lang.Teacher')</label>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                        <div class="form-group">
                            <div class="form-line float-left">
                                <select class="form-control show-tick jq_formdata get-group-teacher" name="teacher" id="teacherforlesson"  >
                                    <option value="-1">-----</option>
                                    @foreach($teachers as $teacher)
                                        <option  {{(isset($_GET['teacher'])&& $_GET['teacher'] ==$teacher->userid)?'selected':''}} value="{{$teacher->userid}}">{{$teacher->fullname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <form id="assign-lesson-to-group" action="{{URL::to('/').'/'.Lang::getLocale().'/lessons/'.$lesson->id.'/add_lesson_to_group'}}" enctype="multipart/form-data" method="POST">
        <div class="card" style="box-shadow: none;">
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
                        <?php
                        $i=$groups->currentpage()*$groups->perpage()-$groups->perpage();
                        ?>
                        @foreach($groups as $group)
                            <tr>
                                <td>{{++$i}}</td>
                                <td>
                                    <div class="demo-checkbox">
                                        <input type="checkbox" att_id="{{$group->group_id}}" id="Group_checkbox_{{$group->group_id}}" class="filled-in" name="groups[]"  value="{{$group->group_id}}"/>
                                        <label for="Group_checkbox_{{$group->group_id}}">{{$group->{"title_".Lang::getLocale()} }}</label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                <div  id="ul-pagination">
                                    <?=str_replace('page-link','page-link-assign-group',$groups->links())?>
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
                        <input type="text" class="datepicker form-control jq_formdata" placeholder="@lang('lang.Start_Date')"  data-dtp="dtp_ZYZzi"  value="{{date('Y-m-d')}}"  name="startdate" onclick="loadPicker()"  onfocus="loadPicker()" onmouseover="loadPicker()"  >
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 m-t-10">
                <div class="form-group">
                    <div class="form-line">
                        <label>@lang('lang.End_Date')</label>
                        <input  type="text" class="datepicker form-control jq_formdata" placeholder="@lang('lang.End_Date')"  data-dtp="dtp_ZYZzi"  value="{{date('Y-m-d')}}" name="enddate"  onclick="loadPicker()"  onfocus="loadPicker()" onmouseover="loadPicker()"  >
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a  class="btn btn-primary waves-effect add-group-to-lesson" >@lang('lang.AssignGroup')</a>
            </div>
        </div>
        <input type="hidden" class="jq_formdata" name="_token" value="{{ csrf_token() }}">
    </form>

</div>
