<form id="edit-form" action="{{URL::to('/').'/'.Lang::getLocale().'/teachers/'.$teacher[0]->userid.'/update'}}" enctype="multipart/form-data" method="POST">
<div class="row clearfix">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Username')</label>
                <input type="text" class="form-control jq_formdata" placeholder="@lang('lang.Name')" name="uname" value="{{$teacher[0]->uname}}" />
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Fullname')</label>
                <input type="text" class="form-control jq_formdata" placeholder="@lang('lang.Fullname')" name="fullname" value="{{$teacher[0]->fullname}}" />
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Email')</label>
                <input type="text" class="form-control jq_formdata" placeholder="@lang('lang.Email')" name="email" value="{{$teacher[0]->email}}" />
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Phone')</label>
                <input type="text" class="form-control jq_formdata" placeholder="@lang('lang.Phone')" name="phone" value="{{$teacher[0]->phone}}" />
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 float-left">
        <div class="form-group">
            <label class="float-left">@lang('lang.Home_room_level')</label>
            <div class="form-line float-left">
                <select class="form-control show-tick jq_formdata" name="level"id="levels">
                    <option value="-1">-----</option>
                    <?php  $modelClass=null;?>
                    @foreach($levels as $level)
                        @if($teacher[0]->userLevel != null)
                                @if($level->level_id == $teacher[0]->userLevel->level_id)
                                    <?php  $modelClass=$level->classesInfo;?>
                                    <option data-id="{{$level->level_id}}" value="{{$level->level_id}}"  selected>{{$level["ltitle_".Lang::getLocale()] }}</option>
                                @else
                                    <option data-id="{{$level->level_id}}" value="{{$level->level_id}}" >{{$level["ltitle_".Lang::getLocale()] }}</option>
                                @endif
                            @else
                                <option data-id="{{$level->level_id}}" value="{{$level->level_id}}" >{{$level["ltitle_".Lang::getLocale()] }}</option>
                            @endif
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 float-left">
        <div class="form-group">
            <label class="float-left">@lang('lang.Home_room_class')</label>
            <div class="form-line float-left">
                <select class="form-control show-tick jq_formdata" name="class" id="class">
                    <option value="-1">-----</option>
                    @if($modelClass != null)
                        @foreach($modelClass as $class)
                            @if($class["class_id"] == $teacher[0]->userClass["class_id"])
                                <option data-id="{{$class["class_id"]}}" value="{{$class["class_id"]}}"  selected>{{$class["ctitle_".Lang::getLocale()] }}</option>
                            @else
                                <option data-id="{{$class["class_id"]}}" value="{{$class["class_id"]}}"  >{{$class["ctitle_".Lang::getLocale()] }}</option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Birth_of_Date')</label>
                <input type="text" class="datepicker form-control jq_formdata" placeholder="@lang('lang.Birth_of_Date')" name="birthdate" data-dtp="dtp_ZYZzi"  value="{{$teacher[0]->birthdate}}">
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Upload_avatar')</label>
                <label class="input-group-btn">
                    <span class="btn btn-primary file-upload-button">
                        <i class="material-icons">file_upload</i>
                        <input type="file" class="jq_formdata" name="avatar" id="avatar" style="display: none;">
                    </span>
                </label>
                <div class="upload-view" style=""></div>
                <input type="text" class="form-control" style="display: none" readonly="">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary waves-effect pull-right" type="button"  id="update_teacher" teacher-id="{{$teacher[0]->userid}}">@lang('lang.Update')</button>
    </div>

</div>
    <input type="hidden" class="jq_formdata" name="_token" value="{{ csrf_token() }}">
</form>