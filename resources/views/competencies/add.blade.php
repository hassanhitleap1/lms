@section('title', 'Add')
<form id="edit-form" action="{{URL::to('/').'/'.Lang::getLocale().'/competencies/save'}}"  method="POST">
<div class="row clearfix">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Title_Ar')</label>
                <input type="text" class="form-control" placeholder="@lang('lang.Title_Ar')" name="title_ar"/>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Title_En')</label>
                <input type="text" class="form-control" placeholder="@lang('lang.Title_En')" name="title_en"/>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Description_Ar')</label>
                <textarea name="description_ar" rows="2" class="form-control no-resize" placeholder="@lang('lang.Description_Ar')"></textarea>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Description_En')</label>
                <textarea name="description_en" rows="2" class="form-control no-resize" placeholder="@lang('lang.Description_En')"></textarea>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="float-left">@lang('lang.Category')</label>
            <div class="form-line float-left">
                <select class="form-control show-tick"  name="category" id="category-standards">
                    <option  disabled selected>-----</option>
                    @foreach($categories as $category)
                        <option value="{{$category->category_id}}">{{$category['title_'.App::getLocale()]}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="float-left">@lang('lang.Domain')</label>
            <div class="form-line float-left">
                <select class="form-control show-tick" name="domain" id="domain-standards" >
                    <option  disabled selected>-----</option>
                    @foreach($domains as $domain)
                        <option value="{{$domain->domain_id}}">{{$domain['title_'.App::getLocale()]}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="float-left">@lang('lang.Pivot')</label>
            <div class="form-line float-left">
                <select class="form-control show-tick" name="pivot" id="pivot-standards">
                    <option value="0" selected disabled>please select the pivot </option>
                    @foreach($pivots as $pivot)
                        <option value="{{$pivot->pivot_id}}">{{$pivot['title_'.App::getLocale()]}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="float-left">@lang('lang.Standard')</label>
            <div class="form-line float-left">
                <select class="form-control show-tick" name="standard" id="standard-standards">
                    <option  selected disabled>please select the Standard </option>
                    @foreach($standards as $standard)
                        <option value="{{$standard->standard_id}}">{{$standard['title_'.App::getLocale()]}}</option>
                    @endforeach
                </select>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary waves-effect pull-right" type="button"  id="add_competencie" >@lang('lang.Save')</button>
    </div>

</div>
    <input type="hidden" class="jq_formdata" name="_token" value="{{ csrf_token() }}">
</form>


<script>


</script>

