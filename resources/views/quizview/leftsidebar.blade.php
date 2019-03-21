<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar lesson-viewer">
    <a class="bars"></a>
    <div class="vertical-slider-viewer">
        <input type="hidden" id="quizid" value="<?=$quiz->quiz_id?>">
            <ul class="hide-bullets" id="lesson_item_container">

                <?php
                foreach($Media as $media){
                if($media->result!='' && $media->result!=null){
                    $selected="selected";
                }else{
                    $selected="";
                }
                    ?>

                    <li class="col-sm-12 col-md-12 col-lg-12 jq_media_item" media_agree="<?=$media->agree;?>" media_type="<?=$media->type;?>" media_cat="<?=$media->category;?>" media_id="<?=$media->id_media;?>" src-attr="<?=$media->url;?>">
                    <div class="item-header">
                            <div class="item-category" title="@lang('lang.Category')">{{$media->title_en }}</div>
                        <div class="item-seen {{$selected}} float-right" id="seen_{{$media->id_media}}"></div>

                    </div>
                        <a class="thumbnail jq_media_title" id="carousel-selector-1" title="{{$media->title_en}}">
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
</aside>
<!-- #END# Left Sidebar -->