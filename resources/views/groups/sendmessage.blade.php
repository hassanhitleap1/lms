<form id="send-message-form" action="{{URL::to('/').'/'.Lang::getLocale().'/groups/send-message/'.$id}}"  method="POST">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <label>@lang('lang.Message')</label>
                    <textarea rows="4" class="form-control no-resize" placeholder="@lang('lang.Please_type_what_you_want_to_send')" name="message"></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a class="btn btn-primary waves-effect " onclick="hidepopup();">@lang('lang.Cancel')</a>
            <button class="btn btn-primary waves-effect" type="button"  id="sendMessgeToUser" >@lang('lang.Send_Message')</button>
        </div>
    </div>
    <input type="hidden" class="jq_formdata" name="_token" value="{{ csrf_token() }}">
</form>