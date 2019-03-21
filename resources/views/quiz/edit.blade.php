@section('title', 'Add')
<form id="edit-form" action="{{URL::to('/').'/'.Lang::getLocale().'/quiz/'.$quiz->quiz_id.'/update'}}"  method="POST">
    <div class="row clearfix">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <label>@lang('lang.Title')</label>
                    <input type="text" class="form-control jq_formdata" placeholder="@lang('lang.Title')" name="title" value="{{$quiz->title}}" />
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <label>@lang('lang.Description')</label>
                    <input type="text" class="form-control jq_formdata" placeholder="@lang('lang.Description')" name="description" value="{{$quiz->description}}"/>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 float-left">
            <div class="form-group">
                <label class="float-left">@lang('lang.Level')</label>
                <div class="form-line float-left">
                    <select class="form-control show-tick jq_formdata level" name="level"id="level">
                        @foreach($levels as $level)
                            @if($level->level_id== $quiz->level)
                                 <option  value="{{$level->level_id}}" selected>{{$level["ltitle_".Lang::getLocale()] }} </option>
                                @else
                                <option  value="{{$level->level_id}}" >{{$level["ltitle_".Lang::getLocale()] }} </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 float-left">
            <div class="form-group">
                <label class="float-left">@lang('lang.Category')</label>
                <div class="form-line float-left">
                    <select class="form-control show-tick jq_formdata level" name="category" id="category">
                        <option selected disabled>-----</option>
                        @foreach($categories as $category)
                            @if($category->category_id== $quiz->category)
                                <option  value="{{$category->category_id}}" selected>{{$category["title_".Lang::getLocale()] }} </option>
                            @else
                                <option  value="{{$category->category_id}}" >{{$category["title_".Lang::getLocale()] }} </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary waves-effect pull-right" type="button"  id="updateQuiz" >@lang('lang.Save')</button>
        </div>

    </div>
    <input type="hidden" class="jq_formdata" name="_token" value="{{ csrf_token() }}">
</form>


