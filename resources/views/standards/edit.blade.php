@section('title', 'Add')
<form id="edit-form" action="{{URL::to('/').'/'.Lang::getLocale().'/standards/'.$standard->standard_id.'/update'}}"  method="POST">
<div class="row clearfix">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Standard_Number')</label>
                <input type="text" class="form-control" placeholder="@lang('lang.Standard_Number')" name="standard_number" value="{{$standard->standard_number}}" />
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Title_Ar')</label>
                <input type="text" class="form-control" placeholder="@lang('lang.Title_Ar')" name="title_ar" value="{{$standard->title_ar}}" />
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Title_En')</label>
                <input type="text" class="form-control" placeholder="@lang('lang.Title_En')" name="title_en" value="{{$standard->title_en}}" />
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="float-left">@lang('lang.Category')</label>
            <div class="form-line float-left">
                <select class="form-control show-tick"  name="category" id="category-standards">
                    @foreach($categories as $category)
                        @if($category->category_id == $standard->categoryInfo->category_id )
                            <option value="{{$category->category_id}}" selected>{{$category['title_'.App::getLocale()]}}</option>
                        @else
                            <option value="{{$category->category_id}}">{{$category['title_'.App::getLocale()]}}</option>
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
                <textarea name="description_ar" rows="2" class="form-control no-resize" placeholder="@lang('lang.Description_Ar')">{{$standard->description_ar}}</textarea>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Description_En')</label>
                <textarea name="description_en" rows="2" class="form-control no-resize" placeholder="@lang('lang.Description_En')">{{$standard->description_en}}</textarea>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="float-left">@lang('lang.Domain')</label>
            <div class="form-line float-left">
                <select class="form-control show-tick" name="domain" id="domain-standards" >
                    @foreach($domains as $domain)
                        @if($domain->domain_id == $standard->domainInfo->domain_id )
                            <option value="{{$domain->domain_id}}" selected>{{$domain['title_'.App::getLocale()]}}</option>
                        @else
                            <option value="{{$domain->domain_id}}">{{$domain['title_'.App::getLocale()]}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="float-left">@lang('lang.Pivot')</label>
            <div class="form-line float-left">
                <select class="form-control show-tick" name="pivot"  id="pivot-standards">
                    @foreach($pivots as $pivot)
                        @if($pivot->pivot_id == $standard->pivotInfo->pivot_id )
                         <option value="{{$pivot->pivot_id}}" selected>{{$pivot['title_'.App::getLocale()]}}</option>
                        @else
                          <option value="{{$pivot->pivot_id}}">{{$pivot['title_'.App::getLocale()]}}</option>
                        @endif
                    @endforeach   
                </select>
            </div>
        </div>
    </div>

     <div class="modal-footer">
            <button class="btn btn-primary waves-effect pull-right" type="button"  id="update_standard" >@lang('lang.Save')</button>
        </div>

    </div>
    <input type="hidden" class="jq_formdata" name="_token" value="{{ csrf_token() }}">
</form>
