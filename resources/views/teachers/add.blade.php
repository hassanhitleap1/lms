@section('title', 'Add')
<form id="edit-form" action="{{URL::to('/').'/'.Lang::getLocale().'/teachers/save'}}" enctype="multipart/form-data" method="POST">
    <div class="row" id="errors"></div>
    <div class="row clearfix">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <label>@lang('lang.Username')</label>
                    <input type="text" class="form-control jq_formdata" placeholder="@lang('lang.Name')" name="uname" value="" />
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <label>@lang('lang.Fullname')</label>
                    <input type="text" class="form-control jq_formdata" placeholder="@lang('lang.Fullname')" name="fullname" value="" />
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <label>@lang('lang.Email')</label>
                    <input type="text" class="form-control jq_formdata" placeholder="@lang('lang.Email')" name="email" value="" />
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <label>@lang('lang.Phone')</label>
                    <input type="text" class="form-control jq_formdata" placeholder="@lang('lang.Phone')" name="phone" value="" />
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 float-left">
            <div class="form-group">
                <label class="float-left">@lang('lang.Home_room_level')</label>
                <div class="form-line float-left">
                    <select class="form-control show-tick jq_formdata" name="level"id="levels">
                        <option value="-1">-----</option>
                        @foreach($levels as $level)
                            <option data-id="{{$level->level_id}}" value="{{$level->level_id}}"  >{{$level["ltitle_".Lang::getLocale()] }}</option>
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

                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <label>@lang('lang.Password')</label>
                    <input type="password" class="form-control jq_formdata" placeholder="@lang('lang.Password')" name="password" value="" />
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <label>@lang('lang.confirm_password')</label>
                    <input type="password" class="form-control jq_formdata" placeholder="@lang('lang.confirm_password')" name="confirm_password" value="" />
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <label>@lang('lang.Birth_of_Date')</label>
                    <input type="text" class="datepicker form-control jq_formdata" placeholder="@lang('lang.Birth_of_Date')" name="birthdate" data-dtp="dtp_ZYZzi"  value="" onclick="loadPicker()"  onfocus="loadPicker()" onmouseover="loadPicker()">
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
            <button class="btn btn-primary waves-effect pull-right" type="button"  id="add_teacher" >@lang('lang.Save')</button>
        </div>

    </div>
    <input type="hidden" class="jq_formdata" name="_token" value="{{ csrf_token() }}">
</form>

