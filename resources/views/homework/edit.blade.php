@section('title', 'Edit')
<div class="row clearfix default">
    <div class="col-sm-6">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Title')</label>
                <input  id="title_homework" type="text" value="{{$homework->title}}" class="form-control"
                       placeholder="@lang('lang.Title')"/>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Description')</label>
                <input id="description_homework" type="text" value="{{$homework->description}}" class="form-control"
                       placeholder="@lang('lang.Description')"/>
            </div>
        </div>
    </div>
    <div class="col-sm-6 ">
        <div class="form-group">
            <label class="float-left">@lang('lang.Category')</label>
            <div class="form-line float-left">
                <select id="categories_homework" class="form-control show-tick">
                    @if(count($categories)>0)
                        @foreach($categories as $key=>$cat)
                            <?php
                            $slect = '';
                            if ($homework->category == $cat->category_id) {
                                $slect = 'selected';
                            }
                            ?>
                            <option {{$slect}} value="{{$cat->category_id}}">{{ $cat->{"title_".Lang::getLocale()} }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer col-sm-12">
        <a id="{{$homework->homework_id}}" class="btn btn-primary waves-effect btn-saveedithomework pull-right" onclick="hidepopup();">@lang("lang.Save")</a>
    </div>
</div>
