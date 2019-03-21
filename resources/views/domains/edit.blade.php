@section('title', 'update')
<form id="edit-form" action="{{URL::to('/').'/'.Lang::getLocale().'/domains/'.$domain->domain_id.'/update'}}"  method="POST">
<div class="row clearfix">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Domain_Number')</label>
                <input type="text" class="form-control" placeholder="@lang('lang.Domain_Number')" name="domainnumber" value="{{$domain->domainnumber}}"/>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Title_Ar')</label>
                <input type="text" class="form-control" placeholder="@lang('lang.Title_Ar')"name="title_ar" value="{{$domain->title_ar}}"/>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Title_En')</label>
                <input type="text" class="form-control" placeholder="@lang('lang.Title_En')" name="title_en" value="{{$domain->title_en}}"/>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="float-left">@lang('lang.Category')</label>
            <div class="form-line float-left">
                <select class="form-control show-tick" name="category">
                    <option   selected>Select your option</option>
                    @foreach ($categories as $category)
                        @if($domain->category==$category->category_id)
                            <option selected  value="{{$category->category_id}}">{{$category->title_en}}</option>
                        @else
                            <option value="{{$category->category_id}}">{{$category->title_en}}</option>
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
                <textarea name="description_ar" rows="2" class="form-control no-resize" placeholder="@lang('lang.Description_Ar')">{{$domain->description_ar}}</textarea>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Description_En')</label>
                <textarea name="description_en" rows="2" class="form-control no-resize" placeholder="@lang('lang.Description_En')">{{$domain->description_en}}</textarea>
            </div>
        </div>
    </div>
<div class="modal-footer">
            <button class="btn btn-primary waves-effect pull-right" type="button"  id="update_domain" >@lang('lang.Save')</button>
        </div>

    </div>
    <input type="hidden" class="jq_formdata" name="_token" value="{{ csrf_token() }}">
</form>