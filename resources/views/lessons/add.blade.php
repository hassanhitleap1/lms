<div class="row clearfix">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Title')</label>
                <input id="title" type="text" class="form-control" value="" placeholder="@lang('lang.Title')"/>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Description')</label>
                <input id="description" type="text" class="form-control" value="" placeholder="@lang('lang.Description')"/>
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

                        echo '<option value="'.$level["level_id"].'" >'.$level["ltitle_".Lang::getLocale()].'</option>';
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
                <select id="category"  class="form-control show-tick category-standards">
                    <option value="">---------</option>
                    <?php
                    foreach($categories as $category){

                        echo '<option  value="'.$category["category_id"].'" >'.$category["title_".Lang::getLocale()].'</option>';
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
                    <option value="" >---------</option>
                    <?php
                    foreach($curriculums as $curricula){

                        echo '<option   value="'.$curricula["curriculumsid"].'"  >'.$curricula["cu_title_".Lang::getLocale()].'</option>';
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
                    foreach($domains as $domain){

                        echo '<option   value="'.$domain["domain_id"].'"  >'.$domain["title_".App::getLocale()].'</option>';
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
                <select id="sc-les-pivot " class="form-control show-tick pivot-standards"  name="pivot">
                    <option  value="-1">---------</option>
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
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Start_Date')</label>
                <input type="text" class="datepicker form-control jq_formdata" id="Start_Date"  name="start_date" placeholder="@lang('lang.Start_Date')"    value="" onfocus="loadPicker()" onmouseover="loadPicker()">
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.End_Date')</label>
                <input type="text" class="datepicker form-control jq_formdata" id="End_Date" name="end_date" placeholder="@lang('lang.End_Date')"    value="" onfocus="loadPicker()" onmouseover="loadPicker()" >
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Min_Point')</label>
                <input id="min_point" type="text" class="form-control" placeholder="@lang('lang.Min_Point')" value=""/>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Max_Point')</label>
                <input id="max_point" type="text" class="form-control" placeholder="@lang('lang.Max_Point')" value=""/>
            </div>
        </div>
    </div>


    <div class="modal-footer">
        <a class="btn btn-primary waves-effect btn-save_lessonadd pull-right" >@lang('lang.Save')</a>
    </div>
</div>
