@section('title', 'Add')
@php $lang=Lang::getLocale();@endphp
{{App::setLocale($lang)}}

<style>
    .pagination {
        margin: 0px 0 20px 0;
    }
</style>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row dataTables_wrapper">
                        <div class="col-lg-3 col-sm-12 col-md-3 col-xs-12 float-left">
                            <form class="form-horizontal">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group search-container-a">
                                            <div class="form-line float-left">
                                                <input  id="searchmedia" type="search" value="{{$search}}" class="form-control input-sm" placeholder="@lang('lang.Search')" aria-controls="DataTables_Table_0">
                                            </div>
                                            <button id="btnSearchmedia" class="btn btn-primary btn-xs waves-effect float-left search-absolute" title="@lang('lang.Search')"><i class="material-icons">search</i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-3 col-sm-12 col-md-3 col-xs-12 float-left">
                            <form class="form-horizontal">
                                <div class="row clearfix">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                        <label class="float-left">@lang('lang.Type')</label>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <div class="form-group">
                                            <div class="form-line float-left">
                                                <select  id="Type_media"
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
                        <div class="col-lg-3 col-sm-12 col-md-3 col-xs-12 float-left">
                            <form class="form-horizontal">
                                <div class="row clearfix">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                        <label class="float-left">@lang('lang.Category')</label>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <div class="form-group">

                                            <div class="form-line float-left">
                                                <select  id="Categories_media"
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
                        <div class="col-lg-3 col-sm-12 col-md-3 col-xs-12 float-left">
                            <form class="form-horizontal">
                                <div class="row clearfix">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                        <label class="float-left">@lang('lang.Grade')</label>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <div class="form-group">
                                            <div class="form-line float-left">
                                                <select  att="{{$grade}}" id="Grade_media"
                                                        class="form-control show-tick">
                                                    @for($i=0;$i<13;$i++)
                                                        <?php if ($i == 0) {
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
        <div id="lesson_media_container">

                    <div class="row m-t-20">
                        @if(isset($media)&&count($media)>0 )
                            @foreach($media as $med)
                                <?php $med['id']=($med["type"]==6)?$med['quizid']:$med['id'];?>
                                <?php $flag='';?>
                                @if(isset($mymedia)&&count($mymedia)>0)
                                    @foreach($mymedia as $mymed=>$mediadata)
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
//                                                print_r($med);
//                                                exit();
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
                                                $med["category_en"]=$med["name_en"];
                                                $med["category_ar"]=$med["name_ar"];
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

                                            <div title_ar="{{$med["title_ar"]}}" title_en="{{$med["title_en"]}}"
                                                 class="caption">
                                                <h3>{{$med["title_".Lang::getLocale()]}}</h3>
                                                <?php $mediaid=(int)$med['id'];?>
                                                <a  category_ar="<?= isset($med["category_ar"])? $med["category_ar"]:'';?>" category_en="<?= isset($med["category_en"])?$med["category_en"]:'';?>" class="<?=((!empty($mediaAssigns)) && in_array($mediaid, $mediaAssigns)? 'disable-item':'jq_addmedialesson' );?> btn btn-primary btn-xs waves-effect {{$flag}} pull-right" media_id="{{$mediaid}}" role="button" media_src="{{$path}}"><i class="material-icons" id-disabled="{{$mediaid}}">add</i></a>
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
                                <span class="pull-left">@lang('lang.Showing')</span><span
                                        class="pull-left">{{($media->currentpage()-1)*$media->perpage()+1}}</span>
                                <span class="pull-left">@lang('lang.to') </span>
                                <span class="pull-left"> {{(($media->currentpage()-1)*$media->perpage())+$media->count()}}</span><span
                                        class="pull-left">@lang('lang.of')</span><span
                                        class="pull-left">{{$media->total()}}</span><span
                                        class="pull-left">@lang('lang.Games')</span>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div aclass="dataTables_paginate paging_simple_numbers"
                                 id="DataTables_Table_0_paginate_1 pull-right">
                                {{$media->links()}}

                            </div>
                        </div>
                    </div>
        </div>
    </div>
</div>