@section('title', 'Add')
@php $lang=Lang::getLocale();@endphp
{{App::setLocale($lang)}}
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs tab-nav-right" role="tablist">
            <li att_active="1"  id="tabmymedia" att_idhomework="{{$idhomework}}"  role="presentation" class="{{($tab == 'mymedia') ?'active':''}}"><a href="#home_animation_2" data-toggle="tab">@lang('lang.My_Media')</a></li>
            <li att_active="0"  id="taballmedia"  att_idhomework="{{$idhomework}}" role="presentation" class="{{($tab == 'AllMedia')?'active':''}}"><a href="#profile_animation_2" data-toggle="tab">@lang('lang.All_Media')</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content" homeworkid="{{$idhomework}}" id="type_tab" att_type="{{$tab}}">
            @if($tab=='mymedia')
            <div role="tabpanel" class="tab-pane animated fadeInRight {{($tab == 'mymedia') ?'active':''}}" id="home_animation_2">
                <div class="row m-t-20">
                    @if(isset($mymedia)&&count($mymedia)>0)
                        @foreach($mymedia as $mymed=>$mediadata)
                            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                                <div class="thumbnail homework-item-media">
                                    <img src="{{$mediadata["thumbnail"]}}">
                                    <div class="caption">
                                        <h3>{{$mediadata["title_".Lang::getLocale()]}}</h3>
                                        <a onclick="deletemymedia(this)" att_idhomework="{{$idhomework}}" att_idmedia="{{$mediadata['id_media']}}" att_id="{{$mediadata['id']}}"   class="btn btn-primary btn-xs waves-effect pull-right" role="button"><i class="material-icons">delete</i></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>


                <div class="row">

                    <div class="col-sm-5">
                        <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate_1">
                            @if($mymedia->count())
                                <span class="pull-left">@lang('lang.Showing')</span><span
                                        class="pull-left"> {{($mymedia->currentpage()-1)*$mymedia->perpage()+1}} </span>
                                <span class="pull-left">@lang('lang.to') </span>
                                <span class="pull-left">  {{(($mymedia->currentpage()-1)*$mymedia->perpage())+$mymedia->count()}}  </span><span
                                        class="pull-left">@lang('lang.of')</span><span
                                        class="pull-left"> {{$mymedia->total()}} </span><span
                                        class="pull-left">@lang('lang.Games')</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div att_tab="{{$tab}}" att_idhomework="{{$idhomework}}" class="dataTables_paginate paging_simple_numbers"
                             id="DataTables_Table_0_paginate_1">
                            {{$mymedia->links()}}

                        </div>
                    </div>
                </div>


            </div>
            @endif
                @if($tab=='AllMedia')
            <div role="tabpanel" class="tab-pane animated fadeInRight {{($tab == 'AllMedia')?'active':''}}" id="profile_animation_2">
                <div class="row dataTables_wrapper">
                    <div class="col-lg-3 col-sm-12 col-md-3 col-xs-12 float-left m-b-10">
                        <form class="form-horizontal">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group search-container-a">
                                        <div class="form-line float-left">
                                            <input att_idhomework="{{$idhomework}}" id="searchmedia" type="search" value="{{$search}}" class="form-control input-sm" placeholder="@lang('lang.Search')" aria-controls="DataTables_Table_0">
                                        </div>
                                        <button id="btnSearchmediaHomework" att_idhomework="{{$idhomework}}" class="btn btn-primary btn-xs waves-effect float-left  search-absolute1" title="@lang('lang.Search')"><i class="material-icons">search</i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-3 col-sm-12 col-md-3 col-xs-12 float-left m-b-10">
                        <form class="form-horizontal">
                            <div class="row clearfix">
                                <div class="col-lg-4 col-md-4 col-sm-2 col-xs-4 form-control-label">
                                    <label class="float-left">@lang('lang.Type')</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-10 col-xs-8">
                                    <div class="form-group">
                                        <div class="form-line float-left">
                                            <select att_idhomework="{{$idhomework}}" id="Type_media"
                                                    class="form-control show-tick">
                                                <option @if($type=='games') {{'selected'}}@endif value="games">@lang('lang.Games')</option>
                                                <option @if($type=='interactive-worksheets') {{'selected'}}@endif value="interactive-worksheets">@lang('lang.InteractiveWorksheets')</option>
                                                <option @if($type=='quiz') {{'selected'}}@endif value="quiz">@lang('lang.Quiz')</option>
                                                <option @if($type=='worksheet') {{'selected'}}@endif value="worksheet">@lang('lang.Worksheets')</option>
                                                <option @if($type=='sound') {{'selected'}}@endif value="sound">@lang('lang.Sound')</option>
                                                <option @if($type=='video') {{'selected'}}@endif value="video">@lang('lang.Viedo')</option>
                                                <option @if($type=='stories') {{'selected'}}@endif value="stories">@lang('lang.Stories')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-3 col-sm-12 col-md-3 col-xs-12 float-left m-b-10">
                        <form class="form-horizontal">
                            <div class="row clearfix">
                                <div class="col-lg-4 col-md-4 col-sm-2 col-xs-4 form-control-label">
                                    <label class="float-left">@lang('lang.Category')</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-10 col-xs-8">
                                    <div class="form-group">

                                        <div class="form-line float-left">
                                            <select att_idhomework="{{$idhomework}}" id="Categories_media"
                                                    class="form-control show-tick ">
                                                <option value="-1">@lang('lang.All')</option>
                                                @if($type!='stories')
                                                    @foreach($categories as $key=>$cat)
                                                        @if($categorymedia==$cat)
                                                            <option selected
                                                                    value="{{$cat}}">@lang('lang.'.$key)</option>
                                                        @else
                                                            <option value="{{$cat}}">@lang('lang.'.$key)</option>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    @foreach($categories as $key=>$cat)
                                                        @if($categorymedia==$cat['catid'])
                                                            <option selected
                                                                    value="{{$cat['catid']}}">{{$cat['name_'.Lang::getLocale()]}}</option>
                                                        @else
                                                            <option value="{{$cat['catid']}}">{{$cat['name_'.Lang::getLocale()]}}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-3 col-sm-12 col-md-3 col-xs-12 float-left m-b-10">
                        <form class="form-horizontal">
                            <div class="row clearfix">
                                <div class="col-lg-4 col-md-4 col-sm-2 col-xs-4 form-control-label">
                                    <label class="float-left">@lang('lang.Grade')</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-10 col-xs-8">
                                    <div class="form-group">
                                        <div class="form-line float-left">
                                            <select att_idhomework="{{$idhomework}}" att="{{$grade}}" id="Grade_media"
                                                    class="form-control show-tick">
                                                @for($i=0;$i<13;$i++)
                                                    <?php
                                                   if ($i == 0) {
                                                        $gradetext = 'Kindergarten';
                                                    } else {
                                                        $gradetext = $i;
                                                    }
                                                    ?>
                                                    @if($grade==$i)
                                                        <option selected value="{{$i}}">{{$gradetext}}</option>
                                                    @else
                                                        <option value="{{$i}}">{{$gradetext}}</option>
                                                    @endif
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row m-t-20">
                    @if(isset($media)&&count($media)>0 )
                        @foreach($media as $med)
                            <?php $flag='';?>

                                @if(isset($mymedia)&&count($mymedia)>0)
                                    @foreach($mymedia as $mymed=>$mediadata)
                                        <?php
                                        if($med["type"]==6){$med['id']=$med['quizid'];}?>
                                        @if($mediadata['id_media']==$med['id'])
                                            <?php $flag='disable-item';?>
                                        @endif
                                    @endforeach
                                @endif
                            @if(isset($med['id']))
                                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                                    <div class="thumbnail homework-item-media">
                                        <?php
                                        $URLImage = '';
                                        if ($type != 'stories' ) {
                                            if ($med["type"]==6){
                                                $med['path']='platform/quiz/view/'.App::getLocale().'/index.php?id='.$med['quizid'];
                                                $med['thumbnail']='/images/quiz/'.$med['category'].App::getLocale().'.jpg';
                                                $med['title_en']=$med['title'];
                                                $med["title_ar"]=$med['title'];
                                            }elseif ($med['path'] == '') {
                                                $URLImage = str_replace("###", $med['id'], $med['thumbnail']);
                                            } else {
                                                $id = explode("?id=", $med['path'])[1];
                                                $URLImage = str_replace("###", $id, $med['thumbnail']);
                                            }
                                        } else if ($type == 'stories') {
                                            $URLImage = str_replace("###", $med['id'], $med['thumbnail']);
                                            $URLImage = str_replace("$$$", $med['seriesid'], $URLImage);
                                            if(empty($URLImage)){
                                                $URLImage='https://www.manhal.com/platform/stories/'.$med['seriesid'].'/story/'.$med['id'].'/images/pic.jpg';
                                            }
                                        }
                                        if($med["type"]==6){
                                            $path=\App\Http\Controllers\LessonsController::getMediaSrc($URLImage,"","",6, $med['quizid']);
                                        }elseif($med["type"]==17){
                                            $path=\App\Http\Controllers\LessonsController::getMediaSrc($URLImage,"","",17, $med['id']);
                                        }else{
                                            $path=\App\Http\Controllers\LessonsController::getMediaSrc($URLImage,$med["filename"],$med["path"],$med["type"], $med['id']);
                                        }
                                        if($med["type"]==6){
                                            $URLImage='https://www.manhal.com/images/quiz/'.$med['category']. App::getLocale().'.jpg';
                                        }
                                        ?>
                                        <img src="{{$URLImage}}">
                                        <div title_ar="{{$med["title_ar"]}}" title_en="{{$med["title_en"]}}" url="{{$path}}"
                                             class="caption">
                                            <h3>{{$med["title_".Lang::getLocale()]}}</h3>
                                            <a   onclick="addmymedia(this)" att_idhomework="{{$idhomework}}"
                                               att_id="{{$med['id']}}" class="btn btn-primary btn-xs waves-effect {{$flag}} pull-right"
                                               role="button"><i class="material-icons">add</i></a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                    @endif
                </div>


                    <div class="row">
                        <div class="col-sm-5">
                            <div class="dataTables_info">
                                @if($media->count())
                                    <span class="pull-left">@lang('lang.Showing')</span><span
                                            class="pull-left">{{($media->currentpage()-1)*$media->perpage()+1}}</span>
                                    <span class="pull-left">@lang('lang.to') </span>
                                    <span class="pull-left"> {{(($media->currentpage()-1)*$media->perpage())+$media->count()}}</span><span
                                            class="pull-left">@lang('lang.of')</span><span
                                            class="pull-left">{{$media->total()}}</span><span
                                            class="pull-left">@lang('lang.Games')</span>
                            @endif
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div att_tab="{{$tab}}" att_idhomework="{{$idhomework}}" class="dataTables_paginate paging_simple_numbers"
                                 id="DataTables_Table_0_paginate_1">
                                {{$media->links()}}

                            </div>
                        </div>
                    </div>

            </div>
                @endif
        </div>
    </div>
</div>
