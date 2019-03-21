@section('title', 'edit')
<form id="edit-form" action="{{URL::to('/').'/'.Lang::getLocale().'/pivots/'.$pivot->pivot_id.'/update'}}"  method="POST">
<div class="row clearfix">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Standard_Number')</label>
                <input type="text" class="form-control" placeholder="@lang('lang.Standard_Number')" name="pivotnumber" value="{{$pivot->pivotnumber}}"/>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Title_Ar')</label>
                <input type="text" class="form-control" placeholder="@lang('lang.Title_Ar')" name="title_ar" value="{{$pivot->title_ar}}"/>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Title_En')</label>
                <input type="text" class="form-control" placeholder="@lang('lang.Title_En')" name="title_en" value="{{$pivot->title_en}}"/>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="float-left">@lang('lang.Category')</label>
            <div class="form-line float-left">
                <select class="form-control show-tick" name="category" id="category-standards">
                    @foreach($categories as $category)
                        @if($category->category_id == $pivot->category )
                            <option selected value="{{$category->category_id}}">{{$category['title_'.Lang::getLocale()]}}</option>
                        @else
                            <option value="{{$category->category_id}}">{{$category['title_'.Lang::getLocale()]}}</option>
                        @endif

                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Description_Ar')</label>
                <textarea name="description_ar" rows="2" class="form-control no-resize" placeholder="@lang('lang.Description_Ar')">{{$pivot->description_ar}}</textarea>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Description_En')</label>
                <textarea name="description_en" rows="2" class="form-control no-resize" placeholder="@lang('lang.Description_En')">{{$pivot->description_en}}</textarea>

            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="float-left">@lang('lang.Domain')</label>
            <div class="form-line float-left">
                <select class="form-control show-tick" name="domain" id="domain-standards">
                    @foreach($domains as $domain)
                        @if($domain->domain_id ==$pivot->domain )
                            <option  selected value="{{$domain->domain_id}}">{{$domain['title_'.Lang::getLocale()]}}</option>
                        @else
                            <option value="{{$domain->domain_id}}">{{$domain['title_'.Lang::getLocale()]}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
            <button class="btn btn-primary waves-effect pull-right" type="button"  id="add_pivot" >@lang('lang.Save')</button>
        </div>

    </div>
    <input type="hidden" class="jq_formdata" name="_token" value="{{ csrf_token() }}">
</form>

