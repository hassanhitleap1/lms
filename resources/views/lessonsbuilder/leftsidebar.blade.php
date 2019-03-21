<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar lesson-builder">
    <a class="bars"></a>
    <div class="vertical-slider-viewer">
        <input type="hidden" id="lessonid" value="{{$Lesson->id}}">
            <ul class="hide-bullets  bulider" id="lesson_item_container">

                <?php
                foreach($Media as $media){
                    $media_title="";
                    if(isset($media->title) && $media->title!=""){
                        $media_title=$media->title;
                    }elseif(isset($media->{"title_".Lang::getLocale()}) && $media->{"title_".Lang::getLocale()}!="" ){
                        $media_title=$media->{"title_".Lang::getLocale()};
                    }

                    ?>
                    <li class="col-sm-12 col-md-12 col-lg-12 jq_media_item  {{((strpos($media->url, 'video') !== false))? 'jq_video':''}}  {{((strpos($media->url, 'audio') !== false)) ?'jq_sound' :''}} " media_agree="<?=$media->agree;?>" media_type="<?=$media->type;?>" media_cat="<?=$media->category;?>" media_id="<?=$media->id_media;?>"  src-attr="<?=$media->url;?>" category_ar="<?=$media->category_ar;?>" category_en="<?=$media->category_en;?>">
                        <div class="item-header">
                            <div class="item-category" title="@lang('lang.Category')">{{$media->{"category_".Lang::getLocale()} }}</div>
                            <div class="item-delete float-right" title="@lang('lang.Delete')"></div>
                        </div>
                        <a class="thumbnail jq_media_title" id="carousel-selector-1" title="{{$media_title}}">
                            <img src="{{$media->thumbnail }}">
                        </a>
                    </li>
                <?php
                }
                ?>


            </ul>
    </div>
    <div class="legal">
        <div class="copyright">
            <div class="version"></div>
        </div>
    </div>
    <div class="addlesson-viewer-container">
        <a><i class="icon"></i>@lang('lang.Add')</a>
    </div>
</aside>
<!-- #END# Left Sidebar -->