@section('title', 'Add')
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="page_content_lesson_ass" data-id="{{$lesson->id}}">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tab-nav-right" role="tablist">
        <li role="presentation" class="{{($tab=='group')? 'active':''}}"><a href="#home_animation_2" data-toggle="tab" >@lang('lang.Group')</a></li>
        <li role="presentation" class="{{($tab=='class')? 'active':''}}"><a href="#profile_animation_2" data-toggle="tab" >@lang('lang.Class')</a></li>
        <li role="presentation" class="{{($tab=='student')? 'active':''}}"><a href="#profile_animation_3" data-toggle="tab" >@lang('lang.Students')</a></li>
    </ul>
    <!-- Tab panes -->
    <form id="edit-form" action="{{URL::to('/').'/'.Lang::getLocale().'/lessons/'.$lesson->id.'/add_lesson_to_group'}}"  method="POST">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane animated fadeInRight {{($tab=='group')? 'active':''}}" id="home_animation_2">
                <div class="dataTables_wrapper form-inline dt-bootstrap specify-content-table">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead >
                        <tr>
                            <th>#</th>
                            <th >@lang('lang.Group_Name')</th>
                        </tr>
                        </thead>
                        <tbody id="table-groups">
                        <?php $i=1;?>
                        @foreach($groups as $group)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>
                                    <div class="demo-checkbox">
                                        <input type="checkbox" id="Group_checkbox_{{$group->group_id}}" class="filled-in"  name="groups[]" value='{{$group->group_id}}'/>
                                        <label for="Group_checkbox_{{$group->group_id}}">{{$group['title_'.App::getLocale()]}}</label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>


                </div>
            </div>
            <div role="tabpanel" class="tab-pane animated fadeInRight without-scroll {{($tab=='class')? 'active':''}}" id="profile_animation_2">
                <div class="dataTables_wrapper form-inline dt-bootstrap specify-content-table">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 float-left m-b-10 m-t-10">
                        <form class="form-horizontal">
                            <div class="row clearfix">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 form-control-label float-left">
                                    <label class="float-left">@lang('lang.Level')</label>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line float-left">
                                            <select class="form-control show-tick jq_formdata" id="level-lesson-tab-class">
                                                <option value="-1">-----</option>
                                                @foreach($levels as $level)
                                                    <option value="{{$level->level_id}}" @if(isset($_GET['level'])&& $level->level_id==$_GET['level'])selected @endif> {{$level['ltitle_'.App::getLocale()]}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th >@lang('lang.Class_Name')</th>
                        </tr>
                        </thead>
                        <tbody id="list-view-class">
                        <?php $i=1;?>
                        @foreach($classes as $class)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>
                                    <div class="demo-checkbox">
                                        <input type="checkbox" id="Class_checkbox_{{$class->class_id}}" class="filled-in" name="classes[]" value='{{$class->class_id}}' />
                                        <label for="Class_checkbox_{{$class->class_id}}">{{$class['ctitle_'.App::getLocale()]}}</label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane animated fadeInRight without-scroll {{($tab=='student')? 'active':''}}" id="profile_animation_3">
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="body table-responsive">
                                <div class="dataTables_wrapper form-inline dt-bootstrap specify-content-table">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 float-left">
                                            <form class="form-horizontal">
                                                <div class="row clearfix">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 form-control-label">
                                                        <label class="float-left">@lang('lang.Level')</label>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                        <div class="form-group">
                                                            <div class="form-line float-left">
                                                                <select class="form-control show-tick " id="level-lesson-std"  >
                                                                    <option value="-1">-----</option>
                                                                    @foreach($levelStd as $le)
                                                                        <option value="{{$le->level_id}}" @if(isset($_GET['levelstd'])&& $le->level_id==$_GET['levelstd'])selected @endif> {{$le['ltitle_'.App::getLocale()]}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 float-left">
                                            <form class="form-horizontal">
                                                <div class="row clearfix">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 form-control-label">
                                                        <label class="float-left">@lang('lang.Class')</label>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                        <div class="form-group">
                                                            <div class="form-line float-left">
                                                                <select class="form-control show-tick"  id="class-lesson-std">
                                                                    <option value=-1>----------------------</option>
                                                                    @foreach($classStd as $cls)
                                                                        <option value="{{$cls->class_id}}" @if(isset($_GET['classstd'])&& $cls->class_id == $_GET['classstd'])selected @endif> {{$cls['ctitle_'.App::getLocale()]}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th >@lang('lang.Name')</th>
                                        </tr>
                                        </thead>
                                        <tbody  id="list-view-student" >
                                        <?php
                                        $i = $students->currentpage() * $students->perpage() - $students->perpage();
                                        ?>
                                        @foreach($students as $student)
                                            <tr>
                                                <td>{{+$i}}</td>
                                                <td>
                                                    <div class="demo-checkbox">
                                                        <input type="checkbox" id="Students_checkbox_{{$student->userid}}" class="filled-in"  name="students[]" value={{$student->userid}}/>
                                                        <label for="Students_checkbox_{{$student->userid}}">{{$student->fullname}}</label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <div class="col-sm-7">
                                        <div class="dataTables_paginate paging_simple_numbers" id="ul-pagination-student">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <label>@lang('lang.Start_Date')</label>
                            <input type="text" class="datepicker form-control jq_formdata" placeholder="@lang('lang.Start_Date')" name="startDate" data-dtp="dtp_ZYZzi"  value="<?=date('Y-m-d')?>" onclick="loadPicker()"  onfocus="loadPicker()" onmouseover="loadPicker()">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <label>@lang('lang.End_Date')</label>
                            <input type="text" class="datepicker form-control jq_formdata" placeholder="@lang('lang.End_Date')" name="endDate" data-dtp="dtp_ZYZzi"  value="<?=date('Y-m-d')?>" onclick="loadPicker()"  onfocus="loadPicker()" onmouseover="loadPicker()">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary waves-effect" type="button"  id="saveQuiz" >@lang('lang.Save')</button>
                </div>
            </div>
            <input type="hidden" class="jq_formdata" name="_token" value="{{ csrf_token() }}">
        </div>
    </form>
</div>
