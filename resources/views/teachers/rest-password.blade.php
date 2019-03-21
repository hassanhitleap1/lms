<form id="edit-form" action="{{URL::to('/').'/'.Lang::getLocale().'/teachers/'.$user->userid.'/rest-password'}}" enctype="multipart/form-data" method="POST">
    <div class="row clearfix">

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <label>@lang('lang.New_Password')</label>
                    <input type="password" class="form-control jq_formdata" placeholder="@lang('lang.New_Password')" name="password" value="" />
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

        <div class="modal-footer">
            <button class="btn btn-primary waves-effect" type="button"  id="rest_password" >@lang('lang.Save')</button>
        </div>

    </div>
    <input type="hidden" class="jq_formdata" name="_token" value="{{ csrf_token() }}">
</form>