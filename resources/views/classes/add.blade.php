@section('title', 'Add')
<form id="edit-form" action="{{URL::to('/').'/'.Lang::getLocale().'/classes/save'}}"  method="POST">
    <div class="row" id="errors"></div>
    <div class="row clearfix">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <label>@lang('lang.Name_Ar')</label>
                    <input type="text" class="form-control" placeholder="@lang('lang.Name_Ar')" name="name_ar" />
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <label>@lang('lang.Name_En')</label>
                    <input type="text" class="form-control" placeholder="@lang('lang.Name_En')" name="name_en"/>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <label>@lang('lang.Level')</label>
                    <div class="form-line float-left">
                        <select class="form-control show-tick jq_formdata" name="level"id="level">
                                    @foreach($levels as $level)
                                        <option value="{{$level->level_id}}"> {{$level['ltitle_'.App::getLocale()]}}</option>
                                    @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="float-left">@lang('lang.Home_Room_Teacher')</label>
                <div class="form-line float-left">
                    <select class="form-control show-tick jq_formdata" name="teacher"id="teacher">
                        <option value="-1"  selected hidden>Please Choose...</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{$teacher->userid}}"> {{$teacher->fullname}}</option>
                                @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" class="jq_formdata" name="_token" value="{{ csrf_token() }}">
             <button class="btn btn-primary waves-effect btn-saveclasses pull-right" type="button"  id="update_class" >@lang('lang.Save')</button>
        </div>
    </div>
    
</form>