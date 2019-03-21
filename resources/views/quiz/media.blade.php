@section('title', 'Add')
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="page_content" quiz_id="{{$quiz->quiz_id}}" >
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tab-nav-right" role="tablist">
        <li role="presentation" class="active"><a href="#home_animation_2" id="myMedia" data-toggle="tab" >@lang('lang.My_Media')</a></li>
        <li role="presentation"><a href="#profile_animation_2" id="allMedia" data-toggle="tab">@lang('lang.All_Media')</a></li>
    </ul>
    <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane animated fadeInRight active" id="home_animation_2">
                <div class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row m-t-20" id="myMediaTab">
                        @foreach($medias as $media)
                            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12" id="media_{{$media->id}}">
                                <div class="thumbnail homework-item-media">
                                    <img src="{{$media->thumbnail}}">
                                    <div class="caption">
                                        <h3>{{$media['title_'.Lang::getLocale()]}} </h3>
                                        <a  class="btn btn-primary btn-xs waves-effect pull-right deleteMediaQuiz" idMedia="{{$media->id}}"  role="button"><i class="material-icons">delete</i></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="dataTables_info" >
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="dataTables_paginate paging_simple_numbers"  id="page_1">
                                <?=$pagination?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane animated fadeInRight without-scroll" id="profile_animation_2">
                <div class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row dataTables_wrapper">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 float-left">
                                <div class="form-horizontal">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group search-container-a">
                                                <div class="form-line float-left">
                                                    <input  id="searchmediatext" type="search" class="form-control input-sm" placeholder="@lang('lang.Search')" aria-controls="DataTables_Table_0">
                                                </div>
                                                <button id="btnSearchmediaQuiz" class="btn btn-primary btn-xs waves-effect float-left search-absolute1" title="@lang('lang.Search')"><i class="material-icons">search</i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 float-left">
                                <div class="form-horizontal">
                                    <div class="row clearfix">
                                        <div class="col-lg-4 col-md-4 col-sm-2 col-xs-4 form-control-label">
                                            <label class="float-left">@lang('lang.Category')</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-10 col-xs-8">
                                            <div class="form-group">
                                                <div class="form-line float-left">
                                                    <select  id="categoryMedia" class="form-control show-tick ">
                                                        <option value="0" selected> -----</option>
                                                        @foreach($categories as $key=>$value)
                                                            <option value="{{$value}}">{{$key}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 float-left">
                                <div class="form-horizontal">
                                    <div class="row clearfix">
                                        <div class="col-lg-4 col-md-4 col-sm-2 col-xs-4 form-control-label">
                                            <label class="float-left">@lang('lang.Grade')</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-10 col-xs-8">
                                            <div class="form-group">
                                                <div class="form-line float-left">
                                                    <select id="gradeMedia" class="form-control show-tick">
                                                        <option value="-1">@lang('lang.All')</option>
                                                        @for($i=0;$i<13;$i++)
                                                            <?php if ($i == 0) {
                                                                $gradetext = 'Kindergarten';
                                                            } else {
                                                                $gradetext = $i;
                                                            }
                                                            ?>

                                                            <option  value="{{$i}}">{{$gradetext}}</option>

                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="row m-t-20" id="allMediaSearch">

                    </div>

                    <div class="row">
                        <div class="col-sm-5">
                            <div class="dataTables_info" >
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="dataTables_paginate paging_simple_numbers"  id="page_2" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

