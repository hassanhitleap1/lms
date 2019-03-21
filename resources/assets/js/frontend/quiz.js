$(document).ready(function () {

    $(document).on('click', '#btnSearchmediaQuiz', function() {
        var search= $("#searchmediatext").val();
        var category=$("#categoryMedia").val() ;
        var grade=$("#gradeMedia").val() ;
        getAllMediaQuiz(search,category,grade);
    });
    $(document).on('change', '#categoryMedia', function() {
        var search= $("#searchmediatext").val();
        var category=$("#categoryMedia").val() ;
        var grade=$("#gradeMedia").val() ;
        getAllMedia(search,category,grade);
    });
    $(document).on('change', '#gradeMedia', function() {
        var search= $("#searchmediatext").val();
        var category=$("#categoryMedia").val() ;
        var grade=$("#gradeMedia").val() ;
        getAllMediaQuiz(search,category,grade);
    });

    $("#btnSearchmediaQuiz").change(function () {
        var search= $("#searchmediatext").val();
        var category=$("#categoryMedia").val() ;
        var grade=$("#gradeMedia").val() ;
        var page=1;
        getAllMediaQuiz(search,category,grade,page);
    });
    $(document).on('click', '#allMedia', function() {
        var search= '' ;
        var category=1;
        var grade=0;
        getAllMediaQuiz(search,category,grade);
    });
    $(document).on('click', '#myMedia', function() {
        var url=SITE_URL+Language+'/quiz/get-my-media';
        getMyMediaQuiz(url);
    });
    $(document).on('click', '.addMedia', function() {
        if($(this).attr('disabled')==undefined) {
            $(this).attr("disabled", true)
            var mediaId = $(this).attr('media_id');
            var nameEn = $(this).attr('name_en');
            var nameAr = $(this).attr('name_ar');
            var quizId = $("#page_content").attr('quiz_id');
            var thumbnail='https://www.manhal.com/images/quiz/'+$(this).attr('category')+Language+'.jpg';
            var action = SITE_URL + Language + '/quiz/add-media';
            $.ajax({
                url: action,
                type: 'GET',
                data: {mediaId: mediaId, nameAr: nameAr, nameEn: nameEn, quizId: quizId,thumbnail:thumbnail},
                success: function (JSON) {
                }
            });
        }
    });
    $(document).on('click', '.deleteMediaQuiz', function() {
        var idMedia=$(this).attr('idMedia');
        var action= SITE_URL+Language+'/quiz/deletemedia';
        $.ajax({
            url: action,
            type: 'GET',
            data: {idMedia:idMedia},
            success: function (JSON) {
                $( "#media_"+idMedia ).remove();
            }
        });
    });
    $(document).on('click', '.page-link-ajax-my-media', function() {
        event.preventDefault();
        var url=$(this).attr('url');
        getMyMediaQuiz(url);
    });
    $(document).on('click', '.page-link-ajax-all-media', function() {
        event.preventDefault();
        var url=$(this).attr('url');
        var search= $("#btnSearchmediaQuiz").val() ;
        var category=$("#categoryMedia").val() ;
        var grade=$("#gradeMedia").val() ;
        getAllMediaQuiz(search,category,grade,url);
    });

    $(document).on("change","#level-quiz-tab-class",function(){
        idlevel=$(this).val();
        $("#popup_content").load(SITE_URL+Language+"/quiz/"+$('#page_content_quiz_ass').attr("data-id")+"/assign-to?level="+idlevel+"&tab=class");
        console.log();
        showpopup();
    });

    $(document).on("change","#level-quiz-std",function(){
        idlevel=$(this).val();
        $("#popup_content").load(SITE_URL+Language+"/quiz/"+$('#page_content_quiz_ass').attr("data-id")+"/assign-to?levelstd="+idlevel+"&tab=student");
        console.log();
        showpopup();
    });

    $(document).on("change","#class-quiz-std",function(){
        idlevel=$("#level-quiz-std").val();
        classid=$(this).val();
        $("#popup_content").load(SITE_URL+Language+"/quiz/"+$('#page_content_quiz_ass').attr("data-id")+"/assign-to?levelstd="+idlevel+'&classstd='+classid+"&tab=student");
        console.log();
        showpopup();
    });
});

function printAllMediaTabQuiz(data,pagination,quizMediaAdd){
    $("#allMediaSearch").html('');
    $('#page_2').html('');
    var content='';
    $.each(data, function( key ,value ) {
        if(quizMediaAdd.includes(parseInt(value.quizid))){
            content+=' <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12" >\n' +
                '                    <div class="thumbnail homework-item-media">\n' +
                '                    <img src="https://www.manhal.com/images/quiz/'+value.category+Language+'.jpg">\n' +
                '                    <div class="caption">\n' +
                '                    <h3>'+value.title+'</h3>\n' +
                '                    <a  class="btn btn-primary btn-xs waves-effect pull-right addMedia '+
                '" role="button" media_id='+value.quizid+' disabled name_en="'+value.title+'" name_ar="'+value.title+'" id="linkadd_'+value.quizid+'" category="'+value.category+'" ><i class="material-icons"  >add</i></a>\n' +
                '                </div>\n' +
                '                </div>\n' +
                '                </div>';
        }else{
            content+=' <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12" >\n' +
                '                    <div class="thumbnail homework-item-media">\n' +
                '                    <img src="https://www.manhal.com/images/quiz/'+value.category+Language+'.jpg">\n' +
                '                    <div class="caption">\n' +
                '                    <h3>'+value.title+'</h3>\n' +
                '                    <a  class="btn btn-primary btn-xs waves-effect pull-right addMedia '+
                '" role="button" media_id='+value.quizid+' name_en="'+value.title+'" name_ar="'+value.title+'" id="linkadd_'+value.quizid+'" category="'+value.category+'"><i class="material-icons"  >add</i></a>\n' +
                '                </div>\n' +
                '                </div>\n' +
                '                </div>';
        }
    });
    content+=' <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12" ><a target="_blank" href="https://www.manhal.com/en/quiz-editor?id=new">\n' +
        '                    <div class="thumbnail homework-item-media">\n' +
        '                    <img src="https://www.manhal.com/themes/main-Light-green-En/images/file.svg">\n' +
        '                    <div class="caption">\n' +
        '                </div>\n' +
        '                </div>\n' +
        '                </a></div>';
    $("#allMediaSearch").append(content);
    $('#page_2').html(pagination);
}
function getAllMediaQuiz(search,category,grade,url=false){
    var action='';
    if(url){
        action=url;
    }else{
        action= SITE_URL+Language+'/quiz/get-all-media';
    }
    var quizId = $("#page_content").attr('quiz_id');
    $(".page-loader-wrapper").show();
    $.ajax({
        url: action,
        type: 'GET',
        data: {search:search,category:category,grade:grade,quizId:quizId},
        success: function (JSON) {
            if(JSON.allMedia.data.length>0 || JSON.allMedia.data.lengths != null){
                printAllMediaTabQuiz(JSON.allMedia.data,JSON.allMedia.pagination,JSON.quizMediaAdd);
            }else{
                $("#allMediaSearch").html('');
            }
            $(".page-loader-wrapper").hide();
        }
    });
}
function printMyMediaTabQuiz(data,pagination){
    $('#myMediaTab').html('');

    $('#page_1').html('');
    var content='';
    $.each(data, function( key ,value ) {
        content+=
            '<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12" id="media_'+value.id+'">\n' +
            '  <div class="thumbnail homework-item-media">\n' +
            '    <img src="'+value.thumbnail+'">\n' +
            '      <div class="caption">\n' +
            '           <h3>'+value['title_'+Language]+' </h3>\n' +
            '            <a  class="btn btn-primary btn-xs waves-effect pull-right deleteMediaQuiz" idMedia="'+value.id+'"  role="button"><i class="material-icons">delete</i></a>\n' +
            '      </div>\n' +
            '   </div>\n' +
            '</div>';
    });

    $('#myMediaTab').append(content);
    $('#page_1').html(pagination);
}
function getMyMediaQuiz(url){
    var action=url;
    var quizId = $("#page_content").attr('quiz_id');
    $(".page-loader-wrapper").show();
    $.ajax({
        url: action,
        type: 'GET',
        data: {quizId:quizId},
        success: function (JSON) {
            if(JSON.media.data.length>0 ){
                printMyMediaTabQuiz(JSON.media.data,JSON.pagination);
            }
            $(".page-loader-wrapper").hide();
        }
    });
}