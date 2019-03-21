/**
 * Created by Dar Al-Manhal Hussam Abu Khadijeh on 23/09/2018.
 */

var SITE_URL;
if (location.hostname === "localhost" || location.hostname === "127.0.0.1"){
    SITE_URL="http://localhost:8000/";
}else {
    SITE_URL="https://lms.manhal.com/";
}

Language="en";

function getLang(){
    var str =String(window.location);

    var lang = str.search("/en/");
    if(lang > 0 && lang < 40 ){
        Language ="en";
    }else{
        Language= "ar";
    }
}

getLang();
$(document).ready(function(){

    $(".bulider").sortable();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    $("#save_lesson").click(function(){
        showLoading();
        data={};
        var media={};
        var tempmedia={};
        var i=0;
        $(".jq_media_item").each(function(){
            tempmedia={};
            tempmedia["id_lesson"]=$("#lessonid").val();
            tempmedia["title"]=$(this).find(".jq_media_title").first().attr("title");
            tempmedia["description"]="";
            tempmedia["thumbnail"]=$(this).find("img").first().attr("src");
            tempmedia["type"]=$(this).attr("media_type");
            tempmedia["category"]=$(".category-icon-container").attr("category");
            tempmedia["agree"]=$(this).attr("media_agree");
            tempmedia["id_media"]=$(this).attr("media_id");
            tempmedia["url"]=$(this).attr("src-attr");
            tempmedia["category_ar"]=$(this).attr("category_ar");
            tempmedia["category_en"]=$(this).attr("category_en");
            media[i]=tempmedia;
            i++;
        });

        data["media"]=JSON.stringify(media);
        console.log(data);
        if(window.location.href.toLowerCase().indexOf("homework")==-1){//it's lesson
            data["lessontype"]="lesson";
        }else{//it's homehowrk
            data["lessontype"]="homework";
        }
        $.ajax({
            url: SITE_URL+Language+"/lessonsbuilder/"+$("#lessonid").val()+"/save",
            type: 'POST',
            data:data,
            datatype:"josn",
            success: function (jsonResult) {
                console.log(jsonResult);
                hideLoading();
            }
        });
    });
    $(document).on('click', '.addlesson-viewer-container a', function (e) {
        showLoading();
        var lessontype='';
        if(window.location.href.toLowerCase().indexOf("homework")==-1){//it's lesson
            lessontype="lesson";
        }else{//it's homehowrk
            lessontype="homework";
        }
        $(".modal-body").empty().load(SITE_URL+Language+"/lessonsbuilder/"+$("#lessonid").val()+"/viewmedia?lessontype="+lessontype,function(){
            //$(".types-main-container").empty().load(SITE_URL+Language+"/lessonsbuilder/item");
            console.log("loaded",SITE_URL+Language+"/lessonsbuilder/"+$("#lessonid").val()+"/edit?lessontype"+lessontype);
            showpopup();
            hideLoading();
            disabledAddmedia();

        });

    });
    $(document).on("click",".jq_addmedialesson",function(){
        var mediaID=$(this).attr("media_id");
        $(this).removeClass("jq_addmedialesson");
        $(this).addClass("disable-item");
        var category='';
        if(Language=="en"){
            category=$(this).attr("category_en");
        }else{
            category=$(this).attr("category_ar");
        }
        str=$(this).attr("media_src");
        var sound=(str.indexOf("audio") !== -1)?"jq_sound":"";
        var vedio=(str.indexOf("video") !== -1)?"jq_video":"";
        html='<li class="col-sm-12 col-md-12 col-lg-12 '+vedio+' jq_media_item '+sound+'"  category_ar="'+$(this).attr("category_ar")+'"  category_en="'+$(this).attr("category_en")+'"   media_agree="'+mediaID+'" media_type="'+mediaID+'" media_cat="'+$("#Categories_media").val()+'" media_id="'+mediaID+'" src-attr="'+$(this).attr("media_src")+'">';
        html+='<div class="item-header">';
        html+='<div class="item-category" title="Category">'+category+'</div>';
        html+='<div class="item-delete float-right" title="Delete"></div>';
        html+='</div>';
        html+='<a class="thumbnail jq_media_title" id="carousel-selector-'+$("#lesson_item_container li").length+'" title="'+$(this).closest(".thumbnail").find("h3").html()+'">';
        html+='<img src="'+$(this).closest(".thumbnail").find("img").attr("src")+'">';
        html+='</a>';
        html+='</li>';
        $("#lesson_item_container").append(html);
        $(this).addClass("disable-item");
    });

    $(document).on("click",".jq_media_item .item-delete",function(){
        $(this).closest(".jq_media_item").remove();
        $(this).parent().parent().remove();

        var hostname=window.location;
        var link=$('.vertical-slider-viewer ul li').first().attr('src-attr');
        var media_id=$('.vertical-slider-viewer ul li').first().attr('media_id');
        $('.lesson-viewer-iframe').attr('media_id',media_id);
        $('.lesson-viewer-iframe').attr('src',link+'?scorm=true&origin'+hostname);
        $('.lesson-viewer-iframe').attr('src','');
        $('.vertical-slider-viewer ul li').first().click();
        console.log(link)

        var attrhref = $(object).attr("src-attr");
        if(attrhref.indexOf('?') > -1){
            $(".lesson-viewer-iframe").attr("src", attrhref+"&scorm=true&origin="+SITE_URL);
        }else {
            $(".lesson-viewer-iframe").attr("src", attrhref+"?scorm=true&origin"+SITE_URL);
        }
        $(".lesson-viewer-iframe").attr("media_id",  $(object).attr("media_id"));

        $(this).addClass("selected").siblings().removeClass("selected");

        if($(".vertical-slider-viewer ul li.selected").index() == 0) {
            $(".arrow-prev").addClass("pause");
        }else{
            $(".arrow-prev").removeClass("pause");
        }
        if ($(".vertical-slider-viewer ul li.selected").index() == $(".vertical-slider-viewer ul li").length - 1) {
            $(".arrow-next").addClass("pause");
        }else {
            $(".arrow-next").removeClass("pause");
        }

    });

    $(document).on("click",".page-link",function(e){
        e.preventDefault();
        var page=$(this).html();
        if(page=='›' || page=='‹'){
            var page = $(this).attr('href');
            if (typeof page !== typeof undefined && page !== false) {
                page= page.substring(2, 4);
            }else{
                page=1;
            }
        }
        console.log(page);
        if(!$.isNumeric(page)){
            return;
        }
        showLoading();
        $(".modal-body").load(SITE_URL+Language+"/lessonsbuilder/"+$("#lessonid").val()+"/viewmedia?page="+page+"&mediatype="+$("#Type_media").val()+"&category="+$("#Categories_media").val()+"&grade="+$("#Grade_media").val()+"&search="+$("#searchmedia").val(),function(){
            hideLoading();
        });

    });

    $(document).on("change","#Type_media, #Categories_media, #Grade_media",function(){
        reloadMedia();

    });
    $(document).on("click","#btnSearchmedia",function(e){
        e.preventDefault();
        reloadMedia();
    });


});

function reloadMedia(){
    showLoading();
    var searchmedia=$("#searchmedia").val();
    var searchmedia = searchmedia.replace(/ /g, '%20');
    $(".modal-body").load(SITE_URL+Language+"/lessonsbuilder/"+$("#lessonid").val()+"/viewmedia?page=1&mediatype="+$("#Type_media").val()+"&category="+$("#Categories_media").val()+"&grade="+$("#Grade_media").val()+"&search="+searchmedia,function(){
        hideLoading();
        disabledAddmedia();
    });
}

function showLoading(){
    $(".page-loader-wrapper").show();
}
function hideLoading(){
    $(".page-loader-wrapper").hide();
}
window.addEventListener("touchmove", function (event) {
    event.preventDefault();
}, {passive: false});


function onloadIframe() {
    var liCount=$('.hide-bullets li').length;
    if( liCount == '0' ){
        $('.lesson-viewer-iframe').html('');
        $('.lesson-viewer-iframe').attr("src","");
    }
}


function disabledAddmedia(){
    mediaIdes=[];
    var object =$( "#lesson_item_container>li" );
    object.each(function() {
        mediaIdes.push($(this).attr('media_id')) ;
    });
    object2=$('.material-icons');

    console.log(mediaIdes);
    object2.each(function() {

        if( mediaIdes.indexOf($(this).attr('id-disabled'))> -1){
            console.log($(this).attr('id-disabled'));
            $(this).parent().prop('disabled', true);
            $(this).parent().addClass( "disable-item" );

        }else {
            console.log('disabled false ');
            $(this).parent().prop("disabled", false);
            $(this).parent().removeClass( "disable-item" );
            $(this).parent().addClass( "jq_addmedialesson" );
        }
    });
}