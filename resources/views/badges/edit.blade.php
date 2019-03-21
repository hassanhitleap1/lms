@section('title', 'Add')
<form id="edit-form" action="{{URL::to('/').'/'.Lang::getLocale().'/badges/'.$badge->badge_id.'/update'}}"  method="POST">
    <div class="row clearfix">
        <div class="col-sm-6">
            <div class="form-group">
                <div class="form-line">
                    <label>@lang('lang.Name_Ar')</label>
                    <input type="text" class="form-control" placeholder="@lang('lang.Title_Ar')" name="title_ar" value="{{$badge->title_ar}}"/>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <div class="form-line">
                    <label>@lang('lang.Name_En')</label>
                    <input type="text" class="form-control" placeholder="@lang('lang.Title_En')" name="title_en" value="{{$badge->title_en}}"/>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <div class="form-line">
                    <label>@lang('lang.Description_Ar')</label>
                    <textarea name="description_ar" rows="2" class="form-control no-resize" placeholder="@lang('lang.Description_Ar')">{{$badge->description_ar}}</textarea>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <div class="form-line">
                    <label>@lang('lang.Description_En')</label>
                    <textarea name="description_en" rows="2" class="form-control no-resize" placeholder="@lang('lang.Description_En')">{{$badge->description_en}}</textarea>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label class="float-left">@lang('lang.Category')</label>
                <div class="form-line float-left">
                    <select class="form-control show-tick" name="category">
                        <option  disabled selected>Select your option</option>
                        @foreach ($categories as $category)
                            @if($badge->category==$category->category_id)
                                <option value="{{$category->category_id}}" selected>{{$category["title_".Lang::getLocale()] }}</option>
                            @else
                                <option value="{{$category->category_id}}">{{$category["title_".Lang::getLocale()] }}</option>
                            @endif

                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <div class="form-line">
                    <label>@lang('lang.Points')</label>
                    <input type="text" class="form-control" placeholder="@lang('lang.Points')" name="points" value="{{$badge->points}}"/>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label class="float-left">@lang('lang.Levels')</label>
                <div class="form-line float-left">
                    <select class="form-control show-tick" name="level">
                        <option  disabled selected>Select your option</option>
                        @foreach ($levels as $level)
                            @if($badge->level_id==$level->level_id)
                                <option value="{{$level->level_id}}" selected>{{$level["ltitle_".Lang::getLocale()] }}</option>
                            @else
                                <option value="{{$level->level_id}}">{{$level["ltitle_".Lang::getLocale()] }}</option>
                            @endif

                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary waves-effect pull-right" type="button"  id="update_badges" >@lang('lang.Save')</button>
            <input type="hidden" class="jq_formdata" name="_token" value="{{ csrf_token() }}">
        </div>
    </div>
</form>
