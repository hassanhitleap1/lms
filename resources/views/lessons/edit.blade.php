<div class="row clearfix">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Title')</label>
                <input id="title" type="text" class="form-control" value="{{$lesson->title}}" placeholder="@lang('lang.Title')"/>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Description')</label>
                <input id="description" type="text" class="form-control" value="{{$lesson->description}}" placeholder="@lang('lang.Description')"/>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="float-left">@lang('lang.Levels')</label>
            <div class="form-line float-left">
                <select id="level" class="form-control autosubmit show-tick" name="level">
                    <option value="">---------</option>
                    <?php
                    foreach($levels as $level){
                        if(isset($lesson->level) && $lesson->level==$level["level_id"] ){
                            $selected='selected="selected"';
                        }else{
                            $selected='';
                        }
                        echo '<option value="'.$level["level_id"].'" '.$selected.'>'.$level["ltitle_".Lang::getLocale()].'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="float-left">@lang('lang.Category')</label>
            <div class="form-line float-left">
                <select id="category" class="form-control show-tick category-standards">
                    <option value="">---------</option>
                    <?php
                    foreach($categories as $category){
                        $selected='';
                    if($lesson->category==$category["category_id"]){
                        $selected='selected';
                    }
                        echo '<option '.$selected.' value="'.$category["category_id"].'" >'.$category["title_".Lang::getLocale()].'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="float-left">@lang('lang.Curriculum')</label>
            <div class="form-line float-left">
                <select id="curricula" class="form-control show-tick">
                    <option value="">---------</option>
                    <?php
                    foreach($curriculums as $curricula){
                        $selected='';
                        if($lesson->curricula==$curricula["curriculumsid"]){
                            $selected='selected';
                        }
                        echo '<option '.$selected.'  value="'.$curricula["curriculumsid"].'"  >'.$curricula["cu_title_".Lang::getLocale()].'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="float-left">@lang('lang.Domains')</label>
            <div class="form-line float-left">
                <select id="se-les-domain" class="form-control show-tick domain-standards" name="domain">
                    <option  value="-1">---------</option>
                    <?php
                    $modelPivots=null;
                    foreach($domains as $domain){
                        if($domain->domain_id==$lesson->domain){
                            $modelPivots=$domain->pivots;
                            echo '<option   value="'.$domain["domain_id"].'"  selected >'.$domain["title_".App::getLocale()].'</option>';
                        }else{
                            echo '<option   value="'.$domain["domain_id"].'"  >'.$domain["title_".App::getLocale()].'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="float-left">@lang('lang.Pivots')</label>
            <div class="form-line float-left">
                <select id="sc-les-pivot" class="form-control show-tick pivot-standards" name="pivot">
                    <option  value="-1">---------</option>
                    <?php $modelStandards=null;?>
                    @if($modelPivots !=null )
                        @foreach($modelPivots as $pivot)
                            @if($pivot->pivot_id==$lesson->pivot)
                                <?php $modelStandards= $pivot->standards;?>
                                <option value="{{$pivot->pivot_id}}" selected> {{$pivot['title_'.App::getLocale()]}}</option>
                            @else
                                <option value="{{$pivot->pivot_id}}"> {{$pivot['title_'.App::getLocale()]}}</option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="float-left">@lang('lang.Standards')</label>
            <div class="form-line float-left">
                <select id="sc-les-standard" class="form-control show-tick standard-standards" name="standard">
                    <option  value="-1">---------</option>
                    @if($modelStandards !=null)
                        @foreach($modelStandards as $standard)
                            @if($standard->standard_id==$lesson->standard)
                                <option value="{{$standard->standard_id}}" selected> {{$standard['title_'.App::getLocale()]}}</option>
                            @else
                                <option value="{{$standard->standard_id}}"> {{$standard['title_'.App::getLocale()]}}</option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Start_Date')</label>
                <input type="text" class="datepicker form-control jq_formdata" id="Start_Date"  name="start_date" placeholder="@lang('lang.Start_Date')" ddd="{{$lesson->start_date}}"    value="{{$lesson->start_date}}" onfocus="loadPicker()" onmouseover="loadPicker()">
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.End_Date')</label>
                <input type="text" class="datepicker form-control jq_formdata" id="End_Date" name="end_date" placeholder="@lang('lang.End_Date')"    value="{{$lesson->close_date}}" onfocus="loadPicker()" onmouseover="loadPicker()" >
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Min_Point')</label>
                <input id="min_point" type="text" class="form-control" placeholder="@lang('lang.Min_Point')" value="{{$lesson->min_point}}"/>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Max_Point')</label>
                <input id="max_point" type="text" class="form-control" placeholder="@lang('lang.Max_Point')" value="{{$lesson->max_point}}"/>
            </div>
        </div>
    </div>


    <div class="modal-footer">
        <a att_id="{{$lesson->id}}" class="btn btn-primary waves-effect btn-save_lessonedit pull-right" >@lang('lang.Save')</a>
    </div>
</div>

