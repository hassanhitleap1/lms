@section('title', '	Assign Lesson')
<form id="edit-form" action="{{URL::to('/').'/'.Lang::getLocale().'/badges/'.$badge->badge_id.'/saveassign'}}"  method="POST">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <label>@lang('lang.Assign_Lesson')</label>
                    <div class="form-line float-left">
                        <select class="form-control show-tick jq_formdata" name="lessons" id="lessons">
                            <option  disabled selected>--------</option>
                            @foreach($lessons as $lesson)
                                @if(!empty($assign))
                                    @if($assign->ref_id==$lesson->id)
                                        <option value="{{$lesson->id}}" selected> {{$lesson-> title}}</option>
                                        @else
                                        <option value="{{$lesson->id}}"> {{$lesson-> title}}</option>
                                    @endif
                                    @else
                                    <option value="{{$lesson->id}}"> {{$lesson-> title}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
            <input type="hidden" class="jq_formdata" name="_token" value="{{ csrf_token() }}">
        <div class="modal-footer">
            <a class="btn btn-primary " onclick="hidepopup();">@lang('lang.Cancel')</a>
            <button class="btn btn-primary" type="button"  id="save_assign_lesson" >@lang('lang.Assign_Lesson')</button>
        </div>
    </div>
</form>
