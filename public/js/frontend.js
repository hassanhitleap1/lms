/**
 * Created by Dar Al-Manhal Publishers - Hussam Abu Khadijeh on 15/04/2018.
 */

var SITE_URL;
if (location.hostname === "localhost" || location.hostname === "127.0.0.1"){
    SITE_URL="http://localhost:8000/";
}else {
    SITE_URL="https://lms.manhal.com/";
}
Language="en";
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //auto submit
    $(document).on("change",".autosubmit",function(){
        $(this).closest('form')[0].submit();
    });

    $(document).on("click",".btn-addLessons",function(){
        $("#popup_header").attr('crud','add');
        $("#popup_content").load(SITE_URL+Language+"/lessons/add");
        $("#popup_header").html(" - "+window.Lang.lang.Add_Lessons);
        showpopup();
    });

    $(document).on("click",".btn-sendMessgeToUser",function(){
        var userid= $(this).attr('userid');
        $("#popup_content").load(SITE_URL+Language+"/users/send-message/"+userid);
        showpopup();
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
    });

    $(document).on("click",".btn-group.bootstrap-select.form-control.show-tick",function(){
        $(this).removeClass('open');
    });

    $(document).on("click",".btn-sendMessgeTolevelUsers",function(){
        var levelId= $(this).attr('levelId');
        $("#popup_content").load(SITE_URL+Language+"/levels/send-message/"+levelId);
        showpopup();
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
    });

    $(document).on("click",".btn-sendMessgeToClassUsers",function(){
        var classid= $(this).attr('classid');
        $("#popup_content").load(SITE_URL+Language+"/classes/send-message/"+classid);
        showpopup();
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
    });
    $(document).on("click",".btn-assignToLessonGroup",function(){
        var lessonid= $(this).attr('att_id');
        var tab=$('#assignd_lesson_to_gorup').hasClass('active');
        $("#popup_content").load(SITE_URL+Language+"/lessons/assign_lesson_to_group/"+lessonid+'?tab=group');
        showpopup();
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
    });


    $(document).on("click",".btn-sendMessgeToGroupUsers",function(){
        var groupid= $(this).attr('groupid');
        $("#popup_content").load(SITE_URL+Language+"/groups/send-message/"+groupid);
        showpopup();
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
    });

    $(document).on("click",".edit_user",function(){
        $("#popup_content").load(SITE_URL+Language+"/admins/"+$(this).attr("data-id")+"/edit",function(){
            loadPicker();
        });
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });
    $(document).on("click","#addCurricula",function(){
        $("#popup_header").attr('crud','add');
        $("#popup_content").load(SITE_URL+Language+"/curriculums/add");
        $("#popup_header").html(" - "+window.Lang.lang.Add_Curriculums);
        showpopup();
    });
    $(document).on("click","#popupAddQuiz",function(){
        $("#popup_header").attr('crud','add');
        $("#popup_content").load(SITE_URL+Language+"/quiz/create");
        showpopup();
        $("#popup_header").html(" - "+window.Lang.lang.Add_Quiz);
    });



    $(document).on("click",".jq_book_choser",function(){
        $("#cu_book").val($(this).attr("bookid"));
        $(".btn-backbook").click();
        $(".jq_book_chosed").find("img").attr("src",$(this).find("img").attr("src"));
        $(".jq_book_chosed").find("h3").html($(this).find("h3").html());
    });

    $(document).on("click",".jq_insert_curriculum",function(){

        var formData = new FormData($(".jq_curriculmform")[0]);
        $(".page-loader-wrapper").show();
        $.ajax({
            url: SITE_URL+Language+"/curriculums/save",
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $(".page-loader-wrapper").hide();
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                            });
                        });
                    }else{
                        $("#super_content").html(HTML);
                        $(".page-loader-wrapper").hide();
                        hidepopup();
                    }
                }
            }
        });
    });

    $(document).on("click","#sendMessgeToUser",function(){
        var formData = new FormData($("#send-message-form")[0]);
        $.ajax({
            url: $("#send-message-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function () {
                hidepopup();
            }
        });
    });
    $(document).on("click","#addQuiz",function(){
        var formData = new FormData($("#edit-form")[0]);
        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $(".page-loader-wrapper").hide();
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                            });
                        });
                    }else{
                        $("#super_content").html(HTML);
                        $(".page-loader-wrapper").hide();
                        hidepopup();
                    }
                }
            }
        });
    });

    $(document).on("click","#saveQuiz",function(){
        var formData = new FormData($("#edit-form")[0]);
        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (Json) {
                if(Json==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else if(isJSON (Json)){
                    $.each(Json, function(i, errors){
                        $.each(errors, function(y, error){
                            showErrorMessage(error);
                            return false;
                        });
                    });
                }
            }
        });
    });


    $(document).on("click","#add_admin",function(){
        var formData = new FormData($("#edit-form")[0]);
        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {

                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                               });    
                       }); 
                    }else{
                       $("#super_content").html(HTML);
                         hidepopup();
                    }   
                }
            }
        });
    });
    $(document).on("click","#add_competencie",function(){
        var formData = new FormData($("#edit-form")[0]);
        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                            });
                        });
                    }else{
                        $("#super_content").html(HTML);
                        hidepopup();
                    }
                }
            }
        });
    });
    $(document).on("click","#rest_password",function(){
        var formData = new FormData($("#edit-form")[0]);
        showLoader();
        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                hideLoader();
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                            });
                        });
                    }else{
                        $("#super_content").html(HTML);
                        hidepopup();
                    }
                }
            }
        });
    });
    $(document).on("click","#searchstudent",function(){
        var formData = new FormData($("#filter-form")[0]);
        $.ajax({
            url: $("#filter-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    $("#super_content").html(HTML);
                }
            }
        });
    });
    $(document).on("click","#searchteachers",function(){
        var formData = new FormData($("#filter-form")[0]);
        $.ajax({
            url: $("#filter-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    $("#super_content").html(HTML);
                }
            }
        });
    });
    $(document).on("click",".jqDeleteQuiz",function(){
        var data={};
        var action=$(this).attr("data-action");
        data["userid"]=$(this).attr("data-id");
        swal({
            title: window.Lang.lang.AreUSure,
            text: window.Lang.lang.DoUWDelThisQuiz,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: window.Lang.lang.Yes,
            cancelButtonText: window.Lang.lang.No,
            closeOnConfirm: false
        }, function () {
            showLoader();
            $.ajax({
                url: action,
                type: 'DELETE',
                data: data,
                cache: false,
                processData: false,
                datatype:"HTML",
                contentType: false,
                success: function (HTML) {
                    hideLoader();
                    if(HTML==0){
                        swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError);
                    }else{
                        $("#super_content").html(HTML);
                        hidepopup();
                        swal(window.Lang.lang.QuizHasBeenDelSuc);
                        swal.close();
                        Lobibox.notify('success', {
                            msg: Lang.lang.lessonHasBeenDelSuc
                        });
                    }
                }
            });

        });
    });
    $(document).on("click","#assignStudent",function(){
        $("#popup_header").attr('crud','assignStudent');
        $("#popup_content").load(SITE_URL+Language+"/groups/"+$(this).attr("data-id")+"/assign-student");

        showpopup();
    });
    $(document).on("change","#level-lesson-tab-class",function(){
        idlevel=$(this).val();
        lessonid=$('#page_content_lesson_ass').attr("data-id");
        $("#popup_content").load(SITE_URL+Language+"/lessons/assign_lesson_to_group/"+lessonid+'?level='+idlevel+'&tab=class');

        showpopup();
    });
    $(document).on("click",".btn-mediaQuiz",function(){
        $("#popup_content").load(SITE_URL+Language+"/quiz/"+$(this).attr("quiz_id")+"/viewmedia");

        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });
    $(document).on("click","#assignQuizTo",function(){
        $("#popup_content").load(SITE_URL+Language+"/quiz/"+$(this).attr("data-id")+"/assign-to");

        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });
    $(document).on("click","#popup_addstudent",function(){
        $("#popup_header").attr('crud','add');
        $("#popup_content").load(SITE_URL+Language+"/students/new");
        $("#popup_header").html(" - "+window.Lang.lang.Add_Student);

        showpopup();
    });
    $(document).on("click","#popup_addparent",function(){
        $("#popup_header").attr('crud','add');
        $("#popup_content").load(SITE_URL+Language+"/parents/new");
        $("#popup_header").html(" - "+window.Lang.lang.Add_Parent);

        showpopup();
    });
    $(document).on("click","#rest_pass_admin",function(){
        $("#popup_content").load(SITE_URL+Language+"/admins/"+$(this).attr("data-id")+"/rest-password-admin",function(){
            loadPicker();
        });
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });
    $(document).on("click","#rest_pass_teacher",function(){
        $("#popup_content").load(SITE_URL+Language+"/teachers/"+$(this).attr("data-id")+"/rest-password-teacher",function(){
            loadPicker();
        });
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });
    $(document).on("click","#rest_pass_student",function(){
        $("#popup_content").load(SITE_URL+Language+"/students/"+$(this).attr("data-id")+"/rest-password-student",function(){
            loadPicker();
        });
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });
    $(document).on("click","#rest_pass_parent",function(){
        $("#popup_content").load(SITE_URL+Language+"/parents/"+$(this).attr("data-id")+"/rest-password-parent",function(){
            loadPicker();
        });
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });

    $(document).on("click","#add_child",function(){
        $("#popup_header").attr('crud','add_chiled');
        $("#popup_content").load(SITE_URL+Language+"/parents/childs/"+$(this).attr("data-id"),function(){
            loadPicker();
        });
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });

    $(document).on("click",".jq_delete_item",function(){
        var data={};
        var action=$(this).attr("data-action");
        data["userid"]=$(this).attr("data-id");
        itemName= $(this).attr("item-name");
        swal({
            title: window.Lang.lang.AreUSure,
            text: window.Lang.lang.DoUWDelThis +' ' + itemName,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: window.Lang.lang.Yes,
            cancelButtonText: window.Lang.lang.No,
            closeOnConfirm: false
        }, function () {
            showLoader();
            $.ajax({
                url: action,
                type: 'POST',
                data: data,
                cache: false,
                processData: false,
                datatype:"HTML",
                contentType: false,
                success: function (HTML) {
                    hideLoader();
                    if(HTML==0){
                        swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError);
                    }else{
                        $("#super_content").html(HTML);
                        hidepopup();
                        swal(window.Lang.lang.UserHasBeenDelSuc);
                    }
                }
            });

        });
    });
    $(document).on("click","#edit_student",function(){
        $("#popup_content").load(SITE_URL+Language+"/students/"+$(this).attr("data-id")+"/edit",function(){
            loadPicker();
        });
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });

    $(document).on("click",".jq_delete_badges",function(){
        var data={};
        var action=$(this).attr("data-action");
        data["userid"]=$(this).attr("data-id");
        swal({
            title: window.Lang.lang.AreUSure,
            text: window.Lang.lang.DoUWDelThisBadges,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: window.Lang.lang.Yes,
            cancelButtonText: window.Lang.lang.No,
            closeOnConfirm: false
        }, function () {
            showLoader();
            $.ajax({
                url: action,
                type: 'POST',
                data: data,
                cache: false,
                processData: false,
                datatype:"HTML",
                contentType: false,
                success: function (HTML) {
                    hideLoader();
                    if(HTML==0){
                        swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError);
                    }else{
                        $("body").html(HTML);
                        hidepopup();
                        // swal(window.Lang.lang.UserHasBeenDelSuc);
                        swal.close();
                        Lobibox.notify('success', {
                            msg: Lang.lang.BadgesHasBeenDelSuc
                        });
                    }
                }
            });

        });
    });

    $(document).on("change","#teacherforlesson",function(){
        var lessonid= $('#lessonid').attr('att_id');
        var teacher=$(this).val();
        $("#popup_content").load(SITE_URL+Language+"/lessons/assign_lesson_to_group/"+lessonid+"?tab=true&teacher="+teacher);
        showpopup();
    });

    $(document).on("click",".assignd_lesson_to_gorup",function(){
        var lessonid= $('#lessonid').attr('att_id');
        $("#popup_content").append('');
        $("#popup_content").load(SITE_URL+Language+"/lessons/assign_lesson_to_group/"+lessonid+"?tab=group");
        showpopup();
    });
    $(document).on("change","#level-lesson-std",function(){
        idlevel=$(this).val();
        lessonid=$('#page_content_lesson_ass').attr("data-id");
        $("#popup_content").load(SITE_URL+Language+"/lessons/assign_lesson_to_group/"+lessonid+'?levelstd='+idlevel+'&tab=student');

        showpopup();
    });
    $(document).on("click",".assign_lesson_to_gorup",function(){
        var lessonid= $('#lessonid').attr('att_id');
        $("#popup_content").load(SITE_URL+Language+"/lessons/assign_lesson_to_group/"+lessonid);
        showpopup();
    });
    $(document).on("change","#class-lesson-std",function(){
        idlevel=$("#level-lesson-std").val();
        classid=$(this).val();
        lessonid=$('#page_content_lesson_ass').attr("data-id");
        $("#popup_content").load(SITE_URL+Language+"/lessons/assign_lesson_to_group/"+lessonid+'?levelstd='+idlevel+'&classstd='+classid+'&tab=student');

        showpopup();
    });

    $(document).on("click",".page-link-assign-group",function(event){
        event.preventDefault();
        var lessonid= $('#lessonid').attr('att_id');
        var page=$(this).html();
        $("#popup_content").load(SITE_URL+Language+"/lessons/assign_lesson_to_group/"+lessonid+"?page="+page);
        showpopup();
    });

    $(document).on("click",".page-link-assigned-group",function(event){
        event.preventDefault();
        var lessonid= $('#lessonid').attr('att_id');
        var page=$(this).html();
        $("#popup_content").load(SITE_URL+Language+"/lessons/assign_lesson_to_group/"+lessonid+"?page="+page+"&tab=group");
        showpopup();
    });

    $(document).on("click",".add-group-to-lesson",function(event){
        event.preventDefault();
        var formData = new FormData($("#assign-lesson-to-group")[0]);
        showLoader();
        $.ajax({
            url: $("#assign-lesson-to-group").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function (Json) {
                hideLoader();
                if(Json==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else if(isJSON (Json)){
                    $.each(Json, function(i, errors){
                        $.each(errors, function(y, error){
                            showErrorMessage(error);
                            return false;
                        });
                    });
                }
            }
        });
    });

});

function showLoader(){
    $(".page-loader-wrapper").show();
}
function hideLoader(){
    $(".page-loader-wrapper").hide();
}
/**
 * Created by Dar Al-Manhal Publishers - Hussam Abu Khadijeh on 15/04/2018.
 */

var SITE_URL;
if (location.hostname === "localhost" || location.hostname === "127.0.0.1"){
    SITE_URL="http://localhost:8000/";
}else {
    SITE_URL="https://lms.manhal.com/";
}
Language="en";
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on("click","#delete-assign-user ",function(){
        var data={};
        var action=$(this).attr("data-action");
        data["userid"]=$(this).attr("data-id");
        swal({
            title: window.Lang.lang.AreUSure,
            text: window.Lang.lang.DoUWDelThisUser,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: window.Lang.lang.Yes,
            cancelButtonText: window.Lang.lang.No,
            closeOnConfirm: true
        }, function () {
            showLoader();
            $.ajax({
                url: action,
                type: 'POST',
                data: data,
                cache: false,
                processData: false,
                datatype:"HTML",
                contentType: false,
                success: function (HTML) {
                    if(HTML==0){
                        swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError);
                    }else{
                        if(isJSON (HTML)){
                            $('#tr'+data["userid"]).html('');
                        }else{
                            $("#super_content").html(HTML);
                            hidepopup();
                        }
                    }
                    hideLoader();
                }
            });

        });
    });
    $(document).on('click', ".search-absolute", function() {
        $('form').submit();
    });
    $(document).on('change', "#levels", function() {

        idlevel=$(this).val();

        Url=SITE_URL+Language+"/students/get-classes-level/"+idlevel;

        $(".page-loader-wrapper").show();
        $.ajax({
            url: Url,
            type: 'GET',
        }).done(function(data) {
            $('#class').html('');
            $('#class').append('<option  value="-1" >----</option>')
            $.each( data.class, function( key, value ) {
                $('#class').append('<option value="' + value.class_id + '">' +value['ctitle_'+Language]+ '</option>');

            });
            $(".page-loader-wrapper").hide();

        }).fail(function() {
            alert( "error" );
        });
    });
    $(document).on('change', "#level[class='level']", function() {

        idlevel=$(this).val();
        Url=SITE_URL+Language+"/students/get-classes-level/"+idlevel;

        $(".page-loader-wrapper").show();
        $.ajax({
            url: Url,
            type: 'GET',
        }).done(function(data) {
            $('#class').html('');
            $('#class').append('<option  value="-1" >----</option>')
            $.each( data.class, function( key, value ) {
                $('#class').append('<option value="' + value.class_id + '">' +value['ctitle_'+Language]+ '</option>');

            });
            $(".page-loader-wrapper").hide();

        }).fail(function() {
            alert( "error" );
        });
    });
    $(document).on("click","#popup_awards",function(){
        $("#popup_content").load(SITE_URL+Language+"/progress/awards");
        showpopup();
    });
    $(document).on("click","#popup_addadmin",function(){
        $("#popup_header").attr('crud','add');
        $("#popup_content").load(SITE_URL+Language+"/admins/new");
        $("#popup_header").html(" - "+window.Lang.lang.Add_Admin);
        showpopup();
    });
    $(document).on("click","#btn-addlevel",function(){
        $("#popup_header").attr('crud','add');
        $("#popup_content").load(SITE_URL+Language+"/levels/viewAddLevel");
        $("#popup_header").html(" - "+window.Lang.lang.Add_level);
        showpopup();
    });
    $(document).on("click",".edit_user",function(){
        $("#popup_content").load(SITE_URL+Language+"/admins/"+$(this).attr("data-id")+"/edit",function(){
            loadPicker();
        });
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });
    $(document).on("click","#Assign_Lesson",function(){
        $("#popup_content").load(SITE_URL+Language+"/badges/"+$(this).attr("data-id")+"/assignLesson",function(){
            loadPicker();
        });
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });
    $(document).on("click","#update_admin",function(){
        var formData = new FormData($("#edit-form")[0]);
        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                            });
                        });
                    }else{
                        $("#super_content").html(HTML);
                        hidepopup();
                    }
                }
            }
        });
        $("#errors").html(" ");
    });
    $(document).on("click","#update_teacher",function(){
        var formData = new FormData($("#edit-form")[0]);
        
        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                               });    
                       }); 
                    }else{
                       $("#super_content").html(HTML);
                         hidepopup();
                    }                    
                }
            }
        });
        $("#errors").html(" ");
    });
    $(document).on("click","#updateQuiz",function(){
        var formData = new FormData($("#edit-form")[0]);

        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                            });
                        });
                    }else{
                        $("#super_content").html(HTML);
                        hidepopup();
                    }
                }
            }
        });
        $("#errors").html(" ");
    });
    $(document).on("click","#add_teacher",function(){
        var formData = new FormData($("#edit-form")[0]);
        
        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                               });    
                       }); 
                    }else{
                       $("#super_content").html(HTML);
                         hidepopup();
                    }                    
                }
            }
        });
        $("#errors").html(" ");
    });
    $(document).on("click","#save_assign_lesson",function(){
        var formData = new FormData($("#edit-form")[0]);
        
        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                               });    
                       }); 
                    }else{
                       $("body").html(HTML);
                         hidepopup();
                    }                    
                }
            }
        });
    });
    $(document).on("click","#update_student",function(){
        var formData = new FormData($("#edit-form")[0]);

        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                            });
                        });
                    }else{
                        $("#super_content").html(HTML);
                        hidepopup();
                    }
                }
            }
        });
        $("#errors").html(" ");
    });
    $(document).on("click","#update_parent",function(){
        var formData = new FormData($("#edit-form")[0]);

        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                            });
                        });
                    }else{
                        $("#super_content").html(HTML);
                        hidepopup();
                    }
                }
            }
        });
        $("#errors").html(" ");
    });
    $(document).on("click","#add_domain",function(){
        var formData = new FormData($("#edit-form")[0]);
        
        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                               });    
                       }); 
                    }else{
                       $("#super_content").html(HTML);
                         hidepopup();
                    }                    
                }
            }
        });
        $("#errors").html(" ");
    });
    $(document).on("click","#add_pivot",function(){
        var formData = new FormData($("#edit-form")[0]);
        
        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                               });    
                       }); 
                    }else{
                       $("#super_content").html(HTML);
                         hidepopup();
                    }                    
                }
            }
        });
        $("#errors").html(" ");
    });
    $(document).on("click","#add_standard",function(){
        var formData = new FormData($("#edit-form")[0]);
        
        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                               });    
                       }); 
                    }else{
                       $("#super_content").html(HTML);
                         hidepopup();
                    }                    
                }
            }
        });
        $("#errors").html(" ");
    });
    $(document).on("click","#update_domain",function(){
        var formData = new FormData($("#edit-form")[0]);
        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                               });    
                       }); 
                    }else{
                       $("#super_content").html(HTML);
                         hidepopup();
                    }   
               
                }
            }
        });
        $("#errors").html("");
    });
    $(document).on("click","#update_standard",function(){
        var formData = new FormData($("#edit-form")[0]);
        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                               });    
                       }); 
                    }else{
                       $("#super_content").html(HTML);
                         hidepopup();
                    }   
               
                }
            }
        });
        $("#errors").html("");
    });
    $(document).on("click","#update_group",function(){
        var formData = new FormData($("#edit-form")[0]);

        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                               });    
                       }); 
                    }else{
                       $("#super_content").html(HTML);
                         hidepopup();
                    }   
                }
            }
        });
        $("#errors").html("");
    });
    $(document).on("click","#add_group",function(){
        var formData = new FormData($("#edit-form")[0]);

        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                               });    
                       }); 
                    }else{
                       $("#super_content").html(HTML);
                         hidepopup();
                    }   
                }
            }
        });
        $("#errors").html("");
    });
    $(document).on("click","#save_badges",function(){
        var formData = new FormData($("#edit-form")[0]);
        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(isJSON (HTML)){
                    $.each(HTML, function(i, errors){
                        $.each(errors, function(y, error){
                            showErrorMessage(error);
                            return false;
                        });
                    });
                }else{
                    $("#super_content").html(HTML);
                    hidepopup();
                }


            }
        });

    });
    $(document).on("click","#update_badges",function(){
        var formData = new FormData($("#edit-form")[0]);

        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                            });
                        });
                    }else{
                        $("#super_content").html(HTML);
                        hidepopup();
                    }
                }
            }

        });
        $("#errors").html("");
    });
    $(document).on("click","#update_class",function(){
        var formData = new FormData($("#edit-form")[0]);

        $.ajax({
            url: $("#edit-form").attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            datatype:"HTML",
            contentType: false,
            success: function (HTML) {
                if(HTML==0){
                    swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
                        button: window.Lang.lang.OK
                    });
                }else{
                    if(isJSON (HTML)){
                        $.each(HTML, function(i, errors){
                            $.each(errors, function(y, error){
                                showErrorMessage(error);
                                return false;
                               });    
                       }); 
                    }else{
                       $("#super_content").html(HTML);
                         hidepopup();
                    }   
                }
            }
           
        });
       $("#errors").html("");
    });
    $(document).on("click",".add-std-to-group",function(){
        $(".page-loader-wrapper").show();
        userid=$(this).attr("data-id");
        groupId=$(this).attr("group_id");
        Url=SITE_URL+Language+"/groups/"+ groupId+"/assign-user/"+userid;

        $.ajax({
            url: Url,
            type: 'GET',
        }).done(function(data) {
            $('#tr'+userid).html('');
            $(".page-loader-wrapper").hide();
        });
    });
    $(document).on("click",".jq_delete_user",function(){
       var data={};
        var action=$(this).attr("data-action");
        data["userid"]=$(this).attr("data-id");
        swal({
            title: window.Lang.lang.AreUSure,
            text: window.Lang.lang.DoUWDelThisUser,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: window.Lang.lang.Yes,
            cancelButtonText: window.Lang.lang.No,
            closeOnConfirm: false
        }, function () {
            showLoader();
            $.ajax({
                url: action,
                type: 'POST',
                data: data,
                cache: false,
                processData: false,
                datatype:"HTML",
                contentType: false,
                success: function (HTML) {
                    hideLoader();
                    if(HTML==0){
                        swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError);
                    }else{
                        $("#super_content").html(HTML);
                        hidepopup();
                        // swal(window.Lang.lang.UserHasBeenDelSuc);
                        swal.close();
                        Lobibox.notify('success', {
                            msg: Lang.lang.UserHasBeenDelSuc
                        });
                    }
                }
            });
        });
    });
    $(document).on("click","#popup_addteacher",function(){
        $("#popup_header").attr('crud','add');
        $("#popup_content").load(SITE_URL+Language+"/teachers/new");
        $("#popup_header").html(" - "+window.Lang.lang.Add_Teacher);
        showpopup();
    });
    $(document).on("click","#add_badges",function(){
        $("#popup_header").attr('crud','add');
        $("#popup_content").load(SITE_URL+Language+"/badges/create");
        showpopup();
        $("#popup_header").html(" - "+window.Lang.lang.Add_Badges);
    });
    $(document).on("click","#popup_addgroup",function(){
        $("#popup_header").attr('crud','add');
        $("#popup_content").load(SITE_URL+Language+"/groups/new");
        $("#popup_header").html(" - "+window.Lang.lang.Add_Group);

        showpopup();
    });
    $(document).on("click","#popup_addcompetencies",function(){
        $("#popup_header").attr('crud','add');
        $("#popup_content").load(SITE_URL+Language+"/competencies/new");
        $("#popup_header").html(" - "+window.Lang.lang.Add_Competencies);

        showpopup();
    });
    $(document).on("click","#popup_adddomains",function(){
        $("#popup_header").attr('crud','add');
        $("#popup_content").load(SITE_URL+Language+"/domains/new");
        $("#popup_header").html(" - "+window.Lang.lang.Add_Domains);

        showpopup();
    });
    $(document).on("click","#popup_addpivot",function(){
        $("#popup_header").attr('crud','add');
        $("#popup_content").load(SITE_URL+Language+"/pivots/new");
        $("#popup_header").html(" - "+window.Lang.lang.Add_Pivots);

        showpopup();
    });
    $(document).on("click","#popup_addstandard",function(){
        $("#popup_header").attr('crud','add');
        $("#popup_content").load(SITE_URL+Language+"/standards/new");
        $("#popup_header").html(" - "+window.Lang.lang.Add_Standards);

        showpopup();
    });
    $(document).on("click","#popup_addclass",function(){
        $("#popup_header").attr('crud','add');
        $("#popup_content").load(SITE_URL+Language+"/classes/new");
        $("#popup_header").html(" - "+window.Lang.lang.Add_Class);

        showpopup();
    });
    $(document).on("click","#edit_teacher",function(){
        $("#popup_content").load(SITE_URL+Language+"/teachers/"+$(this).attr("data-id")+"/edit",function(){
            loadPicker();
        });
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });
    $(document).on("click","#edit_domain",function(){
        $("#popup_content").load(SITE_URL+Language+"/domains/"+$(this).attr("data-id")+"/edit",function(){
            loadPicker();
        });
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });
    $(document).on("click","#edit_competencie",function(){
        $("#popup_content").load(SITE_URL+Language+"/competencies/"+$(this).attr("data-id")+"/edit",function(){
            loadPicker();
        });
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });
    $(document).on("click","#edit_standard",function(){
        $("#popup_content").load(SITE_URL+Language+"/standards/"+$(this).attr("data-id")+"/edit",function(){
            loadPicker();
        });
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });
    $(document).on("click","#edit_pivot",function(){
        $("#popup_content").load(SITE_URL+Language+"/pivots/"+$(this).attr("data-id")+"/edit",function(){
            loadPicker();
        });
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });
    $(document).on("click","#edit_parent",function(){
        $("#popup_content").load(SITE_URL+Language+"/parents/"+$(this).attr("data-id")+"/edit",function(){
            loadPicker();
        });
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });
    $(document).on("click","#edit_group",function(){

        $("#popup_content").load(SITE_URL+Language+"/groups/"+$(this).attr("data-id")+"/edit",function(){
            loadPicker();
        });
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });
    $(document).on("click","#editQuiz",function(){

        $("#popup_content").load(SITE_URL+Language+"/quiz/"+$(this).attr("data-id")+"/edit",function(){
            loadPicker();
        });
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });
    $(document).on("click","#edit_class",function(){

        $("#popup_content").load(SITE_URL+Language+"/classes/"+$(this).attr("data-id")+"/edit",function(){
            loadPicker();
        });
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });
    $(document).on("click","#edit_badges",function(){
        $("#popup_content").load(SITE_URL+Language+"/badges/"+$(this).attr("data-id")+"/edit",function(){
            loadPicker();
        });
        $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
        showpopup();
    });
    $(document).on("click","#notifications-hover",function(){
        $.ajax({
            url: SITE_URL+Language+'/notifications/read-last-not',
            type: 'GET',
            datatype:"json",
            success: function (data) {
                $("#label-count-notifications").text('');
            }
        });
    });
    $(document).on("click","#messages-hover",function(){
        $.ajax({
            url: SITE_URL+Language+'/messages/read-last-mess',
            type: 'GET',
            datatype:"json",
            success: function (data) {
                $("#label-count-mess").text('');
            }
        });
    });


     $(document).on("click","#pres_1_group",function (event)  {
        var group_id=$('.tab-content').attr('groupId');
        Url=SITE_URL+Language+'/groups/'+group_id+'/assign-student';
        $(".page-loader-wrapper").show();
        $.ajax({
            url: Url,
            type: 'GET',
        }).done(function(data) {

            $(".page-loader-wrapper").hide();
            $('.popup_content').append(data);
        }).fail(function() {
            alert( "error" );
        });
    });
    $(document).on("click","#pres_2_group",function (event) {
        var group_id=$('.tab-content').attr('groupId');
        Url=SITE_URL+Language+'/groups/'+group_id+'/assign-student';
        $(".page-loader-wrapper").show();
        $.ajax({
            url: Url,
            type: 'GET',
        }).done(function(data) {

            $(".page-loader-wrapper").hide();
            $('.popup_content').append(data);
        }).fail(function() {
            alert( "error" );
        });
    });
    $(document).on('change', "#levelclasses", function() {
        $(".page-loader-wrapper").show();
        idlevel=$(this).val();
        Url=SITE_URL+Language+'/groups/get-classes-level/'+idlevel;
        $.ajax({
            url: Url,
            type: 'GET',
        }).done(function(data) {
            appendSelectOptions( data );
            $(".page-loader-wrapper").hide();

        }).fail(function() {
            alert( "error" );
        });
    });

    ////////////  for append in select box in Standards/////////////////////////////
    $(document).on('change', '#category-standards', function () {
        var  categoryId=$(this).val();

        var url=SITE_URL+Language+'/domains/get-domains-category'
        $.ajax({
            url: url,
            type: 'get',
            data: {categoryId:categoryId},
            success: function (data) {

                appendTodomainStanderds(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
            }
        });

    });

    $(document).on('change', '#domain-standards', function () {
        var  domain=$(this).val();

        var url=SITE_URL+Language+'/pivots/get-pivots-domain';
        $.ajax({
            url: url,
            type: 'get',
            data: {domain:domain},
            success: function (data) {

                appendToPivotsStanderds(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
            }
        });

    });

    $(document).on('change', '#pivot-standards', function () {
        var  pivot=$(this).val();

        var url=SITE_URL+Language+'/standards/get-standards-pivot';
        $.ajax({
            url: url,
            type: 'get',
            data: {pivot:pivot},
            success: function (data) {

                appendToStandardStandards(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
            }
        });

    });

    ////////////  for append in select box in Standards end /////////////////////////////
});

////////////  for append in select box in Standards/////////////////////////////
function appendTodomainStanderds(data) {
    var content;
    $('#domain-standards').html('<option  disabled selected>-----</option>') ;
    $.each( data, function( key, value ) {
        content+='<option value="'+value.domain_id+'">'+value['title_'+Language]+'</option>';
    });
    $('#domain-standards').append(content) ;
}
function appendToPivotsStanderds(data) {
    var content;
    $('#pivot-standards').html(' <option  disabled selected>-----</option>') ;
    $.each( data, function( key, value ) {
        content+='<option value="'+value.pivot_id+'">'+value['title_'+Language]+'</option>';
    });
    $('#pivot-standards').append(content) ;
}
function appendToStandardStandards(data) {
    var content;
    $('#standard-standards').html(' <option  disabled selected>-----</option>') ;
    $.each( data, function( key, value ) {
        content+='<option value="'+value.standard_id+'">'+value['title_'+Language]+'</option>';
    });
    $('#standard-standards').append(content) ;
}
////////////  for append in select box in Standards end /////////////////////////////

function showLoader(){
    $(".page-loader-wrapper").show();
}
function hideLoader(){
    $(".page-loader-wrapper").hide();
}
function isJSON (something) {
    if (typeof something != 'string')
        something = JSON.stringify(something);

    try {
        JSON.parse(something);
        return true;
    } catch (e) {
        return false;
    }
}

function showErrorMessage(message) {
    swal(message);
}

function printNotification(data) {
    var last_noif_id=$('#load-more-notif').attr('lastId');

    if((data.notification.length  > 0  ) && ( data.notification[data.notification.length - 1].notification_id> last_noif_id  )){
        $('#label-count-notifications').text(data.count);
        $("#load-more-notif").attr("lastId", data.notification[data.notification.length - 1].notification_id );
        var contant='';
        var strDate='now';
        $('#load-more-notif').text(window.Lang.lang.Load_More);
        $.each(data.notification, function( key, value ) {
            if(last_noif_id > value.notification_id) {
                contant +=
                    '<li>' +
                    '<a href="' + value.link + '" class=" waves-effect waves-block">\n' +
                    '<div class="icon-circle ' + value.color_type + '">\n' +
                    '<i class="material-icons">' + value.type + '</i></div>\n' +
                    '<div class="menu-info">\n' +
                    '<h4>' + value.message + '</h4><p>\n' +
                    '<i class="material-icons">access_time</i>' + strDate + '</p></div>\</a>\n' +
                    '</li>';
            }

        });
        $("#menu-notification").prepend(contant);
    }
    $('#load-more-notif').text(window.Lang.lang.No_Notification);

}
//Our custom function.
function getNotifications(){
    setTimeout(function(){

        $.ajax({
            url: SITE_URL + Language +'/notifications/get',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                printNotification(data);
                getNotifications();
            },
            error: function (jqXHR, textStatus, errorThrown) {
            }
        });

    }, 10000);
}
//Call our function
getNotifications();

function printMessages(data) {
    var lastIdMess= $('#load-more-message').attr('lastIdMess');
    $.each( data.messages, function( k, v ) {
        $( "#menu-mess" ).prepend(
            '<li>\n' +
            '<a href="'+SITE_URL+Language+'/messages/show-all?message='+ v.userid+'" class=" waves-effect waves-block">\n' +
            ' <div class="icon-circle bg-light-green">\n' +
            '<img src="/images/user.png" width="48" height="48" alt="User" />\n' +
            '</div>\n' +
            '<div class="menu-info">\n' +
            '<h4>'+ v.fullname+'</h4>\n' +
            '<p>\n' +
            '<i class="material-icons">access_time</i> '+diffBtweenDate( v.time_mess)+' mins ago\n' +
            '</p>\n' +
            '</div>\n' +
            '</a>\n' +
            '</li>'
        );

    });
    if(data.count>0){
        $('#label-count-mess').text(data.count);
        $('#load-more-message').text(window.Lang.lang.Load_More);
    }else{
        $('#load-more-message').text(window.Lang.lang.No_Messages);
    }

    var content='';
    $.each(data.messages, function( key, value ) {
        var strDate='now';
        content+='<li>\n' +
            '<a href="'+SITE_URL+Language+'/messages/show-all?message='+ value.userid+'" class=" waves-effect waves-block">\n' +
            ' <div class="icon-circle bg-light-green">\n' +
            '<img src="/images/user.png" width="48" height="48" alt="User" />\n' +
            '</div>\n' +
            '<div class="menu-info">\n' +
            '<h4>'+value.fullname+'</h4>\n' +
            '<p>\n' +
            '<i class="material-icons">access_time</i> '+strDate+' mins ago\n' +
            '</p>\n' +
            '</div>\n' +
            '</a>\n' +
            '</li>';

    });
    if(data.count>0){
        if( lastIdMess >data.messages[data.messages.length - 1].id){
            $("#menu-mess").prepend(content);
            $("#load-more-message").attr("lastIdMess", data.messages[data.messages.length - 1].id );
        }
    }


}
function getMessages(){
    setTimeout(function(){

        $.ajax({
            url: SITE_URL + Language +'/messages/get-all',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                printMessages(data);
                getMessages();
            },
            error: function (jqXHR, textStatus, errorThrown) {
            }
        });

    }, 10000);
}
//Call our function
getMessages();

function diffBtweenDate(date) {
    var date1, date2;
    var diff="";
    date1 = new Date( date );

    date2 = new Date( getDateWithFormated() );

    var res = Math.abs(date1 - date2) / 1000;

    // get total days between two dates
    var days = Math.floor(res / 86400);
    if(!days==0){
        diff=days +" Days ago";
        return diff;
    }
    // get hours
    var hours = Math.floor(res / 3600) % 24;
    if(!hours==0){
        diff=" "+hours +" Hour ago";
        return diff;
    }

    // get minutes
    var minutes = Math.floor(res / 60) % 60;
    if(!minutes==0){
        diff=" "+minutes +" Minutes ago";
        return diff;
    }

    // get seconds
    // var seconds = res % 60;
    // document.write("<br>Difference (Seconds): "+seconds);
    return diff;
}

function getDateWithFormated() {
    var currentDate = new Date();
    var day = currentDate.getDate();
    var month = currentDate.getMonth(); //Be careful! January is 0 not 1
    var year = currentDate.getFullYear();
    var hour= currentDate.getHours();
    var minute= currentDate.getMinutes();
    var second= currentDate.getSeconds();
    var dateString =  year +"-"+(month + 1)+"-"+day +" "+hour+":"+minute+":"+second;
    return dateString;
}
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


function hidepopupWithConfirm() {
    if(($("#popup_header").attr('crud')=="add")){
        swal({
            title: window.Lang.lang.AreUSure,
            text: window.Lang.lang.DoUWEixt,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: window.Lang.lang.Yes,
            cancelButtonText: window.Lang.lang.No,
            closeOnConfirm: true
        }, function () {
            $("#popup_header").attr('crud','');
            hidepopup();

        });
    }else{
        var card = $("#popup_header").attr('crud');
        if((card=="add_chiled") || (card=="assignStudent") ){
            location.reload();
        }else{
            hidepopup();
        }
    }

}


$(document).ready(function () {
    setTimeout(function () {
        $(".slimScrollDiv .list").animate({
            scrollTop: $(".sidebar .menu .list li.active").offset().top -($(".navbar").height()+$(".user-info").outerHeight()+$(".breadcrumb").outerHeight()+4)
        }, 900);
    },700);
});

function getEvent(){
    student= $('#student-event').val();
    level= $('#level').val();
    class_id=$("#class").val();
    group=$("#group").val();
    teacher= $('#teacher').val();
    parent= $('#parent').val();

    $.ajax({
        url: SITE_URL+Language+'/calender/get-events',
        type: 'Get',
        data:{student:student,level:level,class_id:class_id,group:group,teacher:teacher,parent:parent},
        datatype:"JSON",
        success: function (data) {

            $('#calendar').fullCalendar({
                header: {
                    left: 'title',
                    // center: ,
                    right: 'today,month,agendaWeek,agendaDay,listWeek,prev,next'
                },
                defaultDate: moment().format('YYYY-MM-DD'),
                navLinks: true, // can click day/week names to navigate views
                editable: false,
                eventLimit: true, // allow "more" link when too many events
                droppable: false,
                events: data,
                eventClick: function(event) {
                    if (event.url) {
                        window.open(event.url, "_blank");
                        return false;
                    }
                }
            });
            //end fullCalendarScript
        }
    });

}

function appendSelectOptions(data){
    $('#class').html('');
    optionsclass='<option  value="-1" >----</option>';
    for(var i =0;i < data.class.length;i++)
    {
        optionsclass+='<option value="' + data.class[i].class_id + '">' +data.class[i]['ctitle_'+Language]+ '</option>';
    }
    $('#class').append(optionsclass);
    $('#class').trigger("change");
}

function appendListViewStudent(data,group_id){
    $('#list-view-student').html('');
    i=1;
    $.each( data.users.data, function( key, value ) {
        x="<tr id="+"tr"+value.userid+">"+
            "<td>"+i+++"</td>" +
            "<td>"+value.fullname+"</td>" +
            "<td>"+value.email+"</td>" +
            "<td>"+value['ltitle_'+Language]+"</td>" +
            "<td>"+value['ctitle_'+Language]+"</td>" +
            "<td  class='action' >" +
            "<a class='add-std-to-group' group_id="+group_id+" data-id="+value.userid+" title="+window.Lang.lang.Add+">"+
            "<i class='material-icons'>add</i>" +
            "</a>" +
            "</td>" +
            "</tr>";

        $('#list-view-student').append(x);

    });

    $('#ul-pagination').html('');
    $('#ul-pagination').append(data.pagination);


}
$(document).on('click', '.btn-addcategory', function (e) {
    $(".modal-body").empty().load(url + "/viewAddCategory");
    $("#popup_header").html(" - "+window.Lang.lang.Add_Category);
});
$(document).on('click', '.btn-sortcategory', function (e) {
    $(".modal-body").empty().load(url + "/viewsort");
    $("#popup_header").html(" - "+window.Lang.lang.Category_Sort);
});
$(document).on('click', '.btn-editcategory', function (e) {
    var task_id = $(this).attr('att');
    $(".modal-body").empty().load(url + "/" + task_id + "/vieweditcategory");
    $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
});
$(document).on('click', '.btn-showresult', function (e) {
    var $idhomework = $(this).attr('att_idhomework');
    var $id_target = $(this).attr('att_id_target');

    $(".modal-body").empty().load(url + '/' + $idhomework + '/' + $id_target + "/showresult");
});
$(document).on('click', '.btn-showresultquiz', function (e) {
    var $idquiz = $(this).attr('att_idquiz');
    var $id_target = $(this).attr('att_id_target');

    $(".modal-body").empty().load(SITE_URL+Language + '/quiz/' + $idquiz + '/' + $id_target + "/showresult");
});

$(document).on('click', '.btn-addhomework', function (e) {
    $("#popup_header").attr('crud','add');
    $(".modal-body").empty().load(url + "/viewadd");
    $("#popup_header").html(" - "+window.Lang.lang.Add_Homework);
});
$(document).on('click', '.btn-saveeaddhomework', function (e) {
    $(".page-loader-wrapper").show();
    var sorting = getUrlParameter('descask');
    if (sorting == '' || sorting == undefined) {
        sorting = 'ASC';
    }
    $.ajax({
        url: url + '/create',
        type: 'POST',
        data: {
            _token: _token,
            'title': $("#title_homework").val(),
            'descask': sorting,
            'description': $("#description_homework").val(),
            'category': $('#categories_homework').val()
        },
        dataType: 'html',
        success: function (data) {

            $("#super_content").html(data);
            $(".page-loader-wrapper").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }
    });
});
$(document).on('click', '.btn-saveedithomework', function (e) {
    $(".page-loader-wrapper").show();
    var sorting = getUrlParameter('descask');
    if (sorting == '' || sorting == undefined) {
        sorting = 'ASC';
    }
    $.ajax({
        url: url + '/saveedit',
        type: 'POST',
        data: {
            _token: _token,
            'id': $(this).attr('id'),
            'title': $("#title_homework").val(),
            'descask': sorting,
            'description': $("#description_homework").val(),
            'category': $('#categories_homework').val()
        },
        dataType: 'html',
        success: function (data) {
            $("#super_content").html(data);
            $(".page-loader-wrapper").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error')
        }
    });
});

$(document).on('click', '.btn-mediahomework', function (e) {
    $(".modal-body").empty().load(url + "/" + $(this).attr('att') + "/viewmedia");
    $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
});
$(document).on('click', '.btn-assignhomework', function (e) {
    $(".modal-body").empty().load(url + "/" + $(this).attr('att') + "/viewassign");
    $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
});

$(document).on('click', '.btn-send-message-groups', function (e) {
    $(".modal-body").empty().load("http://127.0.0.1:8000/en/groups/sendmessage");
});
$(document).on('click', '.btn-saveeaddcategory', function (e) {
    var search = '';
    if (getUrlParameter('search') != undefined) {
        search = getUrlParameter('search');
    }
    $(".page-loader-wrapper").show();
    $.ajax({
        url: url + '/addcategory',
        type: 'POST',
        data: {_token: _token, 'title_ar': $("#title_ar").val(), 'title_en': $("#title_en").val(), 'search': search},
        dataType: 'html',
        success: function (data) {
            if(isJSON (data)){
                data=jQuery.parseJSON( data );
                $(".page-loader-wrapper").hide();
                $.each(data, function(i, errors){
                    $.each(errors, function(y, error){
                        showErrorMessage(error);
                        return false;
                    });
                });
            }else {
                $("#super_content").html(data)
                $(".page-loader-wrapper").hide();
                hidepopup();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error')
        }
    });
});
$(document).on('click', '.btn-saveeditcategory', function (e) {
    var task_id = $(this).attr('att');
    var search = '';
    if (getUrlParameter('search') != undefined) {
        search = getUrlParameter('search');
    }
    $(".page-loader-wrapper").show();
    $.ajax({
        url: url + '/editcategory',
        type: 'GET',
        data: {
            _token: _token,
            'id': task_id,
            'title_ar': $("#title_ar").val(),
            'title_en': $("#title_en").val(),
            'search': search
        },
        dataType: 'html',
        success: function (data) {
            if(isJSON (data)){
                data=jQuery.parseJSON( data );
                $(".page-loader-wrapper").hide();
                $.each(data, function(i, errors){
                    $.each(errors, function(y, error){
                        showErrorMessage(error);
                        return false;
                    });
                });
            }else {
                $("#super_content").html(data);
                $("input[name='search']").val(search);
                $(".page-loader-wrapper").hide();
                hidepopup();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error')
        }
    });
});
$(document).on('click', '.btn-deletecategory', function (e) {
    e.preventDefault();
    var id = $(this).attr('att');
    var __url = url + '/delete?id=' + id + 'v=' + Math.random();
    swal({
        title: window.Lang.lang.RUSure,
        text: window.Lang.lang.RUSureDeleteCategory,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: window.Lang.lang.Yes,
        closeOnConfirm: false,
        dangerMode: true,

    }, function (value) {
        if (value == true) {
            var search = '';
            if (getUrlParameter('search') != undefined) {
                search = getUrlParameter('search');
            }

            $.ajax({
                url: url + '/delete',
                type: 'GET',
                data: {_token: _token, 'id': id, 'search': search},
                dataType: 'html',
                success: function (data) {
                    $("#super_content").html(data);
                    swal.close();
                    Lobibox.notify('success', {
                        msg: Lang.lang.CategoryHasBeenDelSuc
                    });

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('error')
                }
            });
        }
    });

});
$(document).on('click', '.btn-savesortcategory', function (e) {
    e.preventDefault();
    var sort_array = [];
    var search = '';
    if (getUrlParameter('search') != undefined) {
        search = getUrlParameter('search');
    }
    $("#sortable").children().each(function () {
        sort_array.push($(this).attr('data-id'))
    });
    $(".page-loader-wrapper").show();
    $.ajax({
        url: url + '/savesort',
        type: 'POST',
        data: {_token: _token, 'sort': sort_array, 'search': search},
        dataType: 'html',
        success: function (data) {
            $("#super_content").html(data);
            $(".page-loader-wrapper").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error')
        }
    });
});
$(document).on('keypress', '.CategoryKeywords', function (e) {

    if (e.which == 13) {
        e.preventDefault();
        if (getUrlParameter('search') == undefined && $(".CategoryKeywords").val() != '' && $(".CategoryKeywords").val() != ' ') {
            $('<input>').attr({
                type: 'hidden',
                id: 'search',
                name: 'search',
                value: $(".CategoryKeywords").val()
            }).appendTo('#Category');
        } else if ($(".CategoryKeywords").val() == '' || $(".CategoryKeywords").val() == ' ') {
            $("input[name='search']").remove();
        }
        SubmitFormCategory();

    }
});
$(document).on('click', '.CategorySortingName_Ar,.CategorySortingName_En,.CategorySortingName_order,.CategorySortingName_Date', function (e) {
    e.preventDefault();
    var sorting = getUrlParameter('descask');
    if (sorting == 'ASC') {
        sorting = 'DESC';
    } else if (sorting == 'DESC') {
        sorting = 'ASC';
    } else {
        sorting = 'ASC';
    }
    var _type = '';

    if ($(this).hasClass('CategorySortingName_Ar')) {
        _type = 'title_ar';
    } else if ($(this).hasClass('CategorySortingName_En')) {
        _type = 'title_en';
    } else if ($(this).hasClass('CategorySortingName_order')) {
        _type = 'order';
    } else if ($(this).hasClass('CategorySortingName_Date')) {
        _type = 'updated_at';
    }
    $('<input>').attr({
        type: 'hidden',
        id: 'sorting',
        name: 'sorting',
        value: _type
    }).appendTo('#Category');
    $('<input>').attr({
        type: 'hidden',
        id: 'descask',
        name: 'descask',
        value: sorting
    }).appendTo('#Category');
    SubmitFormCategory();
});
$(document).on('click', '.btn-deletelevels', function (e) {
    e.preventDefault();
    var id = $(this).attr('att');
    var __url = url + '/delete?id=' + id
    swal({
        title: window.Lang.lang.RUSure,
        text: window.Lang.lang.RUSureDeleteLevel,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: window.Lang.lang.Yes,
        closeOnConfirm: false
    }, function (value) {
        if (value == true) {
            // $("#Deletelevels" + id).attr('action', __url);
            // $("#Deletelevels" + id).submit();
            var search = '';
            var sorting = '';
            var descask = '';
            var page = '';
            if (getUrlParameter('search') != undefined) {
                search = getUrlParameter('search');
            }
            if (getUrlParameter('sorting') != undefined) {
                sorting = getUrlParameter('sorting');
            }
            if (getUrlParameter('descask') != undefined) {
                descask = getUrlParameter('descask');
            }
            $.ajax({
                url: url + '/delete',
                type: 'GET',
                data: {'id': id, 'search': search, 'sorting': sorting, 'descask': descask},
                dataType: 'html',
                success: function (data) {
                    var text = data.split('levels/delete').join('levels/');
                    $("#super_content").html(text);
                    swal.close();
                    Lobibox.notify('success', {
                        msg: Lang.lang.levelHasBeenDelSuc
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('error')
                }
            });
            return;
        }
    });

});
$(document).on('click', '.btn-addlevel', function (e) {
    $(".modal-body").empty().load(url + "/viewAddLevel");
});
$(document).on('click', '.btn-editlevels', function (e) {
    var task_id = $(this).attr('att');
    $(".modal-body").empty().load(url + "/" + task_id + "/vieweditlevels");
});
$(document).on('click', '.btn-edithomework', function (e) {
    var task_id = $(this).attr('att');

    $(".modal-body").empty().load(url + "/" + task_id + "/viewedit");
    $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
});

$(document).on('click', '.btn-addlevels', function (e) {
    $(".modal-body").empty().load(url + "/add");
});
$(document).on('click', '.btn-saveeaddlevels', function (e) {
    var search = '';
    var sorting = '';
    var descask = '';
    var page = '';
    if (getUrlParameter('search') != undefined) {
        search = getUrlParameter('search');
    }
    if (getUrlParameter('sorting') != undefined) {
        sorting = getUrlParameter('sorting');
    }
    if (getUrlParameter('descask') != undefined) {
        descask = getUrlParameter('descask');
    }
    $(".page-loader-wrapper").show();
    $.ajax({
        url: url + '/addlevels',
        type: 'GET',
        data: {
            _token: _token,
            'school': 1,
            'search': search,
            'ltitle_ar': $("#title_ar").val(),
            'ltitle_en': $("#title_en").val(),
            'sorting': sorting,
            'descask': descask
        },
        dataType: 'html',
        success: function (data) {
            // var text = data.split('levels/addlevels').join( 'levels/');
            if(isJSON (data)){
                data=jQuery.parseJSON( data );
                $(".page-loader-wrapper").hide();
                $.each(data, function(i, errors){
                    $.each(errors, function(y, error){
                        showErrorMessage(error);
                        return false;
                    });
                });
            }else {
                $("#super_content").html(data)
                $(".page-loader-wrapper").hide();
                hidepopup();
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error')
        }
    });
});
$(document).on('click', '.btn-saveeditlevels', function (e) {
    var task_id = $(this).attr('att');
    $(".page-loader-wrapper").show();
    $.ajax({
        url: url + '/editlevels',
        type: 'POST',
        data: {_token: _token, 'id': task_id, 'title_ar': $("#title_ar").val(), 'title_en': $("#title_en").val()},
        dataType: 'html',
        success: function (data) {
            if(isJSON (data)){
                data=jQuery.parseJSON( data );
                $(".page-loader-wrapper").hide();
                $.each(data, function(i, errors){
                    $.each(errors, function(y, error){
                        showErrorMessage(error);
                        return false;
                    });
                });
            }else {
                $("#super_content").html(data);
                $(".page-loader-wrapper").hide();
                hidepopup();
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error')
        }
    });
});
$(document).on('click', '.btn-deletehomeworks', function (e) {
    e.preventDefault();
    var id = $(this).attr('att');
    var __url = url + '/delete?id=' + id
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary file!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    }, function (value) {
        if (value == true) {
            var page = '';
            var search = getUrlParameter('search');
            var sorting = getUrlParameter('descask');
            if (sorting == '' || sorting == undefined) {
                sorting = 'ASC';
            }
            $.ajax({
                url: url + '/delete',
                type: 'GET',
                data: {_token: _token, 'id': id, 'search': search, 'descask': sorting},
                dataType: 'html',
                success: function (data) {
                    $("#super_content").html(data);
                    // swal.close();
                    // swal('successfully  delate');
                    swal.close();
                    Lobibox.notify('success', {
                        msg: Lang.lang.HomeworkHasBeenDelSuc
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('error')
                }
            });
            return;
        }
    });

});
$(document).on('click', '.LevelSortingName_Ar,.LevelSortingName_En,.LevelSortingName_order,.LevelSortingName_Date', function (e) {
    e.preventDefault();
    var sorting = getUrlParameter('descask');
    if (sorting == 'ASC') {
        sorting = 'DESC';
    } else if (sorting == 'DESC') {
        sorting = 'ASC';
    } else {
        sorting = 'ASC';
    }
    var _type = '';

    if ($(this).hasClass('LevelSortingName_Ar')) {
        _type = 'ltitle_ar';
    } else if ($(this).hasClass('LevelSortingName_En')) {
        _type = 'ltitle_en';
    } else if ($(this).hasClass('LevelSortingName_order')) {
        _type = 'level_number';
    } else if ($(this).hasClass('LevelSortingName_Date')) {
        _type = 'updated_at';
    }
    $('<input>').attr({
        type: 'hidden',
        id: 'sorting',
        name: 'sorting',
        value: _type
    }).appendTo('#Levels');
    $('<input>').attr({
        type: 'hidden',
        id: 'descask',
        name: 'descask',
        value: sorting
    }).appendTo('#Levels');
    SubmitFormLevels();
});

$(document).on('keypress', '.LevelKeywords', function (e) {

    if (e.which == 13) {
        e.preventDefault();
        if ((getUrlParameter('search') == undefined || getUrlParameter('search') == '') && $(".LevelKeywords").val() != '' && $(".LevelKeywords").val() != ' ') {
            $('<input>').attr({
                type: 'hidden',
                id: 'search',
                name: 'search',
                value: $(".LevelKeywords").val()
            }).appendTo('#Levels');
        } else if ($(".LevelKeywords").val() == '' || $(".LevelKeywords").val() == ' ') {
            $("input[name='search']").remove();
        }
        SubmitFormLevels();

    }
});
$(document).on('keypress', '.Homeworkskeywords', function (e) {
    e.preventDefault();
    if (e.which == 13) {
        if ((getUrlParameter('search') == undefined || getUrlParameter('search') == '') && $(".Homeworkskeywords").val() != '' && $(".Homeworkskeywords").val() != ' ') {
            $('<input>').attr({
                type: 'hidden',
                id: 'search',
                name: 'search',
                value: $(".Homeworkskeywords").val()
            }).appendTo('#HomeWorks');
        } else if ($(".Homeworkskeywords").val() == '' || $(".Homeworkskeywords").val() == ' ') {
            $("input[name='search']").remove();
        }
        SubmitFormHomeworks();

    }
});

function SubmitFormHomeworks() {
    $(".Homeworkskeywords").val($.trim($(".Homeworkskeywords").val()))
    if ($(".Homeworkskeywords").val() != '' && $(".Homeworkskeywords").val() != ' ') {
        $("input[name='search']").val($(".Homeworkskeywords").val());
    }
    $("#HomeWorks").submit();
}

function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

function SubmitFormCategory() {
    $(".CategoryKeywords").val($.trim($(".CategoryKeywords").val()))
    if ($(".CategoryKeywords").val() != '' && $(".CategoryKeywords").val() != ' ') {
        $("input[name='search']").val($(".CategoryKeywords").val());
    }
    $("#Category").submit();
}

function SubmitFormLevels() {
    $(".LevelKeywords").val($.trim($(".LevelKeywords").val()))
    if ($(".LevelKeywords").val() != '' && $(".LevelKeywords").val() != ' ') {
        $("input[name='search']").val($(".LevelKeywords").val());
    }
    $("#Levels").submit();
}

function showselected(_this) {

    $('#' + _this.id + ' option[selected]').removeAttr("selected");
    $('#' + _this.id + ' option[value=' + _this.value + ']').attr('selected', 'selected');

}

function showselectedclass(_this) {
    $('#' + _this.id + ' option[selected]').removeAttr("selected");
    $('#' + _this.id + ' option[value=' + _this.value + ']').attr('selected', 'selected');
    var id = _this.id;
    var container = (id).split('class').join('Division');
    $(".page-loader-wrapper").show();
    $.ajax({
        url: url + '/class/',
        type: 'GET',
        data: {_token: _token, 'class': _this.value},
        dataType: 'html',
        success: function (v) {
            var data = JSON.parse(v)
            var options = '<select onchange="showselected(this);" id="' + container + '" class="form-control show-tick" tabindex="-98">';
            for (var i = 0; i < data.length; i++) {
                options += ' <option   att="' + data[i].class_id + '" value="' + data[i].class_id + '">' + data[i]['ctitle_'+Language]+ '</option>'
            }
            if(!data.length){
                options += ' <option   att="-1" value="-1">' + window.Lang.lang.Empty+ '</option>';
            }
            $("#" + container).html(options);
            $('.show-tick').selectpicker('refresh');
            $(".page-loader-wrapper").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error')
        }
    });
}

$(document).on('change', '#ClassRoom', function (e) {
    $("input[name='class']").val($(e.target).val());
    $("input[name='steacher']").val(0);
    $("input[name='Division']").val('');
    $("#FormClass").submit();
});

$(document).on('change', '#Division', function (e) {
    $("input[name='Division']").val($(e.target).val());
    $("input[name='steacher']").val(0);
    $("#FormClass").submit();
});
$(document).on('change', '#Teacher', function (e) {
    $("input[name='steacher']").val($(e.target).val());
    $("#FormClass").submit();
});

$(document).on('click', '.btn-savescheduleteacher', function (e) {
    e.preventDefault();
    data_array = [];
    Teacher = $("#Teacher").find(':selected').attr('att');
    for (var i = 0; i < 7; i++) {
        for (var j = 0; j < 8; j++) {
            var category = 'empty';
            Category = $($("#" + i + "_" + j).children().find("#cat" + i + "_" + j + " :selected")[0]).attr('cat');
            Classroom = $($("#" + i + "_" + j).children().find("#class" + i + "_" + j + " :selected")[0]).attr('att');
            Division = $($("#" + i + "_" + j).children().find("#Division" + i + "_" + j + " :selected")[0]).attr('att');
            data_array.push({
                'Category': Category,
                'Period': i + 1,
                'Dayofweek': j + 1,
                'Classroom': Classroom,
                'Division': Division
            });
        }
    }

    $(".page-loader-wrapper").show();
    $.ajax({
        url: url + '/savescheduletea',
        type: 'POST',
        data: {_token: _token, 'data': data_array, 'search': '', 'steacher': Teacher},
        dataType: 'html',
        success: function (data) {
            $("#super_content").html(data);
            $('.show-tick').selectpicker('refresh');
            $(".page-loader-wrapper").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error')
        }
    });


});

function PrintElem() {
    var windowContent = '<!DOCTYPE html>';
    windowContent += '<html>'
    windowContent += '<head><title>'+window.Lang.lang.Print+'</title></head>';
    windowContent += '<body>'
    windowContent += '<img src="' + dataUrl + '">';
    windowContent += '</body>';
    windowContent += '</html>';
    var printWin = window.open('', '', 'width=1024,height=768');
    printWin.document.open();
    printWin.document.write(windowContent);
    setTimeout(function () {
        printWin.document.close();
        printWin.focus();
        printWin.print();
        printWin.close();
        printFlag = true;
    }, 500)
}

function printscu() {
    if (document.getElementById('Image_Print') != null) {
        dataUrl = document.getElementById('Image_Print').toDataURL(); //attempt to save base64 string to server using this var
        $("#Image_Print").remove();
        $(".btn-print-schdule").show();
        $(".btn-savescheduleteacher").show();
        $(".btn-saveschedule").show();
        PrintElem();
        clearInterval(myVar);
        $(".page-loader-wrapper").hide();
    }
}

var printFlag = true;
$(document).on('click', '#btn-print-schdule', function (e) {
    if (printFlag) {
        printFlag = false;
        $(".page-loader-wrapper").show();
        $(".btn-print-schdule").hide();
        $(".btn-saveschedule").hide();
        $(".btn-savescheduleteacher").hide();
        html2canvas(document.querySelector("#super_content")).then(function (canvas) {
            canvas.id = 'Image_Print'
            canvas.style.display = "none";
            document.body.appendChild(canvas);
        });
        $("#Image_Print").hide();
        myVar = setInterval(printscu, 1000);
    }
});
$(document).on('click', '.btn-saveschedule', function (e) {
    e.preventDefault();
    data_array = [];
    for (var i = 0; i < 7; i++) {
        for (var j = 0; j < 8; j++) {
            var category = 'empty';
            Category = $($("#" + i + "_" + j).children().find("#cat" + i + "_" + j + " :selected")[0]).attr('cat');
            Teacher = $($("#" + i + "_" + j).children().find("#teac" + i + "_" + j + " :selected")[0]).attr('teac');

            // data_array.push({'Category':Category,'Teacher':Teacher,'Period':i+1,'Dayofweek':j+1,'Division':Division});
            data_array.push({'Category': Category, 'Teacher': Teacher, 'Period': i + 1, 'Dayofweek': j + 1});
        }
    }
    Classroom = $("#ClassRoom").find(':selected').attr('att');
    Division = $("#Division").find(':selected').attr('att');
    $(".page-loader-wrapper").show();
    $.ajax({
        url: url + '/saveschedule',
        type: 'POST',
        data: {_token: _token, 'data': data_array, 'search': '', 'Division': Division, 'class': Classroom},
        dataType: 'html',
        success: function (data) {
            $("#super_content").html(data);
            $('.show-tick').selectpicker('refresh');
            $(".page-loader-wrapper").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error')
        }
    });
});

$(document).on('click', '.HomeworkSortingTitle,.HomeworkSortingDescription,.HomeworkSortingName_Date', function (e) {
    e.preventDefault();
    var orderby='title';
    var sorting = getUrlParameter('descask');
    if (sorting == 'ASC') {
        sorting = 'DESC';
    } else if (sorting == 'DESC') {
        sorting = 'ASC';
    } else {
        sorting = 'ASC';
    }

    if ($(this).hasClass('HomeworkSortingTitle')) {
        orderby = 'title';
    } else if ($(this).hasClass('HomeworkSortingDescription')) {
        orderby = 'description';
    } else if ($(this).hasClass('HomeworkSortingName_Date')) {
        orderby = 'created_at';
    }
    $('#orderby').attr({
        value: orderby
    }).appendTo('#orderby');
    $('#sort').attr({
        value: sorting
    }).appendTo('#sort');

    document.getElementById('homework-filter').submit();
});



function SubmitFormHomeWorks() {
    $(".keywords").val($.trim($(".keywords").val()))
    if ($(".keywords").val() != '' && $(".keywords").val() != ' ') {
        $("input[name='search']").val($(".keywords").val());
    }
    $("#HomeWorks").submit();
}

$(document).on('click', '#DataTables_Table_0_paginate_1 a', function (e) {

    e.preventDefault();
    var page = $(this).attr('href').split('?=').join('');
    $idhomework = $($(this).parent().parent().parent()).attr('att_idhomework');
    $tab = $($(this).parent().parent().parent()).attr('att_tab');
    getmediaAPI(page, $idhomework, $tab);
});
$(document).on('click', '#DataTables_Table_0_paginate_2 a', function (e) {
    e.preventDefault();
    var page = $(this).html();
    var $idhomework = $($(this).parent().parent().parent()).attr('att_idhomework');
    var $level = $($(this).parent().parent().parent()).attr('att_level');
    var $tab = $($(this).parent().parent().parent()).attr('att_tab');
    var $class = $($(this).parent().parent().parent()).attr('att_class');
    var $group = $('#group_assign_select option[selected]').val();
    getassign_User(page, $idhomework, $tab, $level, $class, $group);
});
$(document).on('click', '#btnSearchmedia', function (e) {
    e.preventDefault();
    $idhomework = $(this).attr("att_idhomework");
    getmediaAPI(1, $idhomework, 'AllMedia');
});
$(document).on('change', '#Type_media,#Categories_media,#Grade_media', function (e) {
    e.preventDefault();
    $idhomework = $(this).attr("att_idhomework");
    getmediaAPI(1, $idhomework, 'AllMedia');
});


function getmediaAPI($page, $idhomework, $media) {
    var $type = $("#Type_media").find(':selected').val();
    var $category = '';
    var $Grade = '';
    var $search = '';
    $(".page-loader-wrapper").show();
    $category = $("#Categories_media").find(':selected').val();
    $Grade = $("#Grade_media").find(':selected').val();
    $search = $("#searchmedia").val();
    $.ajax({
        url: url + "/" + $idhomework + '/viewmedia',
        type: 'GET',
        data: {
            _token: _token,
            'page': $page,
            'search': $search,
            'type': $type,
            'category': $category,
            'grade': $Grade,
            'tab': $media
        },
        dataType: 'html',
        success: function (data) {
            $("#popup_content").html(data);

            $("#tabmymedia").removeClass('active');
            $("#taballmedia").removeClass('active');
            $("#home_animation_2").removeClass('active show');
            $("#profile_animation_2").removeClass('active show');
            $("#tabmymedia").attr('att_active', 0);
            $("#taballmedia").attr('att_active', 0);
            if ($media == 'AllMedia') {

                $("#taballmedia").addClass('active');
                $("#profile_animation_2").addClass('active show');
                $("#taballmedia").attr('att_active', 1);
            } else if ($media == 'mymedia') {

                $("#tabmymedia").addClass('active');
                $("#home_animation_2").addClass('active show');
                $("#tabmymedia").attr('att_active', 1);

            }
            $("#home_animation_2").removeClass('fadeInRight');
            $("#profile_animation_2").removeClass('fadeInRight');
            $(".page-loader-wrapper").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error')
        }
    });
}

function getassign_User($page, $idhomework, $user, $level, $class, $group) {

    $(".page-loader-wrapper").show();

    $.ajax({
        url: url + "/" + $idhomework + '/viewassign',
        type: 'GET',
        data: {
            _token: _token,
            'page': $page,
            'type': $user,
            'level': $level,
            'classe': $class,
            'group': $group,

        },
        dataType: 'html',
        success: function (data) {
            $("#popup_content").html(data);
            $("#tabClasses").removeClass('active');
            $("#tabStudent").removeClass('active');
            $("#tabGroup").removeClass('active');
            $("#tabGroup").attr('att_active', 0);
            $("#tabClasses").attr('att_active', 0);
            $("#tabStudent").attr('att_active', 0);
            $("#profile_animation_2").removeClass('active show');
            $("#student_animation_2").removeClass('active show');
            $("#home_animation_2").removeClass('active show');

            if ($user == 'group') {
                $("#tabGroup").addClass('active');
                $("#tabGroup").attr('att_active', 1);
                $("#home_animation_2").addClass('active show');

            } else if ($user == 'student') {
                $("#tabStudent").addClass('active');
                $("#tabStudent").attr('att_active', 1);
                $("#student_animation_2").addClass('active show');
            } else if ($user == 'classes') {
                $("#tabClasses").addClass('active');
                $("#tabClasses").attr('att_active', 1);
                $("#profile_animation_2").addClass('active show');
            }
            $("#home_animation_2").removeClass('fadeInRight');
            $("#student_animation_2").removeClass('fadeInRight');
            $("#profile_animation_2").removeClass('fadeInRight');
            $(".page-loader-wrapper").hide();
            $(".page-loader-wrapper").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error')
        }
    });
}

function getAssignUsers($page, $idhomework, $users) {
    $(".page-loader-wrapper").show();

    $.ajax({
        url: url + "/" + $idhomework + '/viewassign',
        type: 'GET',
        data: {
            _token: _token,
            'page': $page,
            'type': $users,
        },
        dataType: 'html',
        success: function (data) {
            $("#popup_content").html(data);

            $("#tabClasses").removeClass('active');
            $("#tabStudent").removeClass('active');
            $("#tabGroup").removeClass('active');
            $("#tabGroup").attr('att_active', 0);
            $("#tabClasses").attr('att_active', 0);
            $("#tabStudent").attr('att_active', 0);
            $("#profile_animation_2").removeClass('active show');
            $("#student_animation_2").removeClass('active show');
            $("#home_animation_2").removeClass('active show');

            if ($users == 'group') {
                $("#tabGroup").addClass('active');
                $("#tabGroup").attr('att_active', 1);
                $("#home_animation_2").addClass('active show');

            } else if ($users == 'student') {
                $("#tabStudent").addClass('active');
                $("#tabStudent").attr('att_active', 1);
                $("#student_animation_2").addClass('active show');
            } else if ($users == 'classes') {
                $("#tabClasses").addClass('active');
                $("#tabClasses").attr('att_active', 1);
                $("#profile_animation_2").addClass('active show');
            }
            $("#home_animation_2").removeClass('fadeInRight');
            $("#student_animation_2").removeClass('fadeInRight');
            $("#profile_animation_2").removeClass('fadeInRight');
            $(".page-loader-wrapper").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error')
        }
    });
}

function deletemymedia(_this) {
    $idmymedia = $(_this).attr("att_id");
    $idhomework = $(_this).attr("att_idhomework");
    swal({
        title: window.Lang.lang.RUSure,
        text: window.Lang.lang.RUSureDeleteMedia,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: window.Lang.lang.Yes,
        closeOnConfirm: false,
        dangerMode: true,
    }, function (value) {
        if (value == true) {
            $(".page-loader-wrapper").show();
            $.ajax({
                url: url + '/deletemymedia',
                type: 'GET',
                data: {_token: _token, 'id': $idmymedia, 'idhomework': $idhomework},
                dataType: 'html',
                success: function (data) {
                    $("#popup_content").html(data);
                    $("#home_animation_2").removeClass('fadeInRight');
                    $("#profile_animation_2").removeClass('fadeInRight');
                    $(".page-loader-wrapper").hide();
                    swal.close();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('error')
                }
            });
        }
    });
}

function addmymedia(_this) {
    var $id = $(_this).attr('att_id');
    $(_this).addClass('disable-item');
    var $type = $("#Type_media").find(':selected').val();
    var $idhomework = $(_this).attr('att_idhomework');
    var $thumbnail = $($(_this).parent().parent()).find('img').attr('src');
    var $title_ar = $($(_this).parent()).attr('title_ar');
    var $title_en = $($(_this).parent()).attr('title_en');
    var $url=$($(_this).parent()).attr('url');
    $.ajax({
        url: url + '/addmymedia',
        type: 'GET',
        data: {
            _token: _token,
            'id': $id,
            'type': $type,
            'idhomework': $idhomework,
            'thumbnail': $thumbnail,
            'title_ar': $title_ar,
            'title_en': $title_en,
            'url':$url
        },
        dataType: 'html',
        success: function (data) {
            $(".page-loader-wrapper").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error')
        }
    });
}

$(document).on('click', '#tabmymedia,#taballmedia,#tabGroup,#tabClasses,#tabStudent', function (e) {
    $target = $(e.target).parent().attr('id');
    $idhomework = $(this).attr("att_idhomework");
    if ($target == 'tabmymedia' && $("#" + $target).attr('att_active') == 0) {
        getmediaAPI(1, $idhomework, 'mymedia');
    } else if ($target == 'taballmedia' && $("#" + $target).attr('att_active') == 0) {
        getmediaAPI(1, $idhomework, 'AllMedia');
    } else if ($target == 'tabGroup' && $("#" + $target).attr('att_active') == 0) {
        getAssignUsers(1, $idhomework, 'group');
    } else if ($target == 'tabClasses' && $("#" + $target).attr('att_active') == 0) {
        getAssignUsers(1, $idhomework, 'classes');
    } else if ($target == 'tabStudent' && $("#" + $target).attr('att_active') == 0) {
        getAssignUsers(1, $idhomework, 'student')
    }
});
$(document).on('change', '#level_assign_select,#classes_assign_select,#group_assign_select', function () {
    $('#' + this.id + ' option[selected]').removeAttr("selected");
    $('#' + this.id + ' option[value=' + this.value + ']').attr('selected', 'selected');
    var $idhomework = $(this).attr('att_idhomework');
    var $tab = $(this).attr('att_type');
    var $level = $('#level_assign_select option[selected]').val();
    var $class = $('#classes_assign_select option[selected]').val();
    var $group = 0;
    if (this.id == 'level_assign_select') {
        $class = 0;
    } else if (this.id == 'group_assign_select') {
        $group = $('#group_assign_select option[selected]').val();
    }
    getassign_User(0, $idhomework, $tab, $level, $class, $group);
});
$(document).on('click', '.add_assign_homework', function () {
    var $idhomework = $(this).attr('att_idhomework');
    var $tab = $(this).attr('att_tab');
    var alluserchecked = [];
    var $startdate = $("#startdatehomework").val();
    var $enddate = $("#enddatehomework").val();
    if($startdate > $enddate){
        swal(window.Lang.lang.Error_in_Date);
        return;
    }
    $('.dataTable > tbody  > tr >td  ').each(function () {
        if ($($(this).find('input:checkbox:eq(0)')).attr('id') != undefined) {
            if ($($(this).find('input:checkbox:eq(0)')).is(":checked")) {
                var id = $($(this).find('input:checkbox:eq(0)')).attr('att_id');
                alluserchecked.push(id)
            }

        }
    });

    if (alluserchecked.length > 0) {
        $.ajax({
            url: url + '/addassignhomework',
            type: 'GET',
            data: {
                _token: _token,
                'All_user': alluserchecked,
                'type': $tab,
                'idhomework': $idhomework, 'startdate': $startdate, 'enddate': $enddate,

            },
            dataType: 'html',
            success: function (data) {
                $(".page-loader-wrapper").hide();
                if (data == 1) {
                    swal(window.Lang.lang.Successfully_Add);
                } else if (data == 0) {
                    swal(window.Lang.lang.Previously_Sent);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error')
            }
        });
    } else {
        swal(window.Lang.lang.error, window.Lang.lang.UnexpectedError, "error", {
            button: window.Lang.lang.OK
        });
    }

});

$(document).on('click', '.btn-editCurriculums', function () {
    var _id = $(this).attr('att');
    $(".modal-body").empty().load(url + '/' + _id + "/viewedit");
    $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
});
$(document).on('click', '.btn-deleteCurriculums', function () {
    var _id = $(this).attr('att');
    swal({
        title: window.Lang.lang.RUSure,
        text: window.Lang.lang.RUSureDeleteCurriculum,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: window.Lang.lang.Yes,
        closeOnConfirm: false,
        dangerMode: true,
    }, function (value) {
        if (value == true) {
            $(".page-loader-wrapper").show();

            $.ajax({
                url: url + "/" + _id + '/deleteCurriculum',
                type: 'GET',
                data: {_token: _token},
                dataType: 'html',
                success: function (data) {
                    $("#super_content").html(data);
                    hidepopup();
                    $(".page-loader-wrapper").hide();
                    swal.close();
                    Lobibox.notify('success', {
                        msg: Lang.lang.CurriculumsHasBeenDelSuc
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('error')
                }
            });
        }
    });


});
$(document).on('click', '.choosebooks_curricula', function () {
    var _id = $(this).attr('bookid');
    var _title = $(this).attr('att_title');
    $(".jq_book_chosed").attr('bookid', _id);
    $(".jq_book_chosed").attr('curricula_title', _title);
    $(".curricula_title").html(_title);
    $(".thumbnail_img_curricula").attr('src', $("#thumbnail_" + _id).attr('src'));
    $("#cu_book").val(_id);
    $("#thumbnail_img_curricula").val($(".thumbnail_img_curricula").attr('src'));
    $("#curricula_title").val(_title);
    $(".btn-backbook").click();
});
$(document).on('click', '#books_pagination a', function (e) {
    e.preventDefault();
    var page = $(this).attr('href').split('?=').join('');
    var $idcorriculum = $('#books_pagination').attr('att_idcorriculum');
    getbooksAPI(page, $idcorriculum);

});

function getbooksAPI($page, $idcurriculum) {

    var $category = '';
    var $Grade = '';
    var $search = '';
    $(".page-loader-wrapper").show();
    $curriculum = $("#Curriculum").find(':selected').val();
    $categorybooks = $("#categorybooks").find(':selected').val();
    $search = $("#search_books").val();

    var $att_type = $('#books_pagination').attr('att_type');
    var $adddata = {};
    if ($att_type == 'edit') {
        $url = url + "/" + $idcurriculum + '/viewedit';
    } else if ($att_type == 'add') {
        $url = url + '/add';

        var $title_ar = $("#title_ar").val();
        var $title_en = $("#title_en").val();
        var $description_ar = $("#description_ar").val();
        var $description_en = $("#description_en").val();
        var $category = $("#Curriculum").find(':selected').val();
        var $level = $("#level").find(':selected').val();
        $adddata = {
            'title_ar': $title_ar,
            'title_en': $title_en,
            'description_ar': $description_ar,
            'description_en': $description_en,
            'category': $category,
            'level': $level
        }

    } else {
        return 'error'
    }
    $.ajax({
        url: $url,
        type: 'POST',
        data: {
            _token: _token,
            'page': $page,
            'search': $search,
            'curriculum': $curriculum,
            'categorybooks': $categorybooks,
            'adddata': $adddata
        },
        dataType: 'html',
        success: function (data) {
            $("#popup_content").html(data);
            $(".default").removeClass("slideInRight").addClass("animated slideOutRight").hide();
            $(".add-book-div").removeClass("slideOutRight").addClass("animated slideInLeft").fadeIn();
            $("#listbook_api").show();
            $(".page-loader-wrapper").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error')
        }
    });
}

$(document).on('keypress', '#search_books', function (e) {
    if (e.which == 13) {
        e.preventDefault();
        var $idcorriculum = $('#books_pagination').attr('att_idcorriculum');
        getbooksAPI(1, $idcorriculum);
    }
});

//saveedit

$(document).on('click', '.btn-saveeditcorriculm', function (e) {

    var $title_ar = $("#title_ar").val();
    var $title_en = $("#title_en").val();
    var $description_ar = $("#description_ar").val();
    var $description_en = $("#description_en").val();
    var $category = $("#Curriculum").find(':selected').val();
    var $level = $("#level").find(':selected').val();
    var $idbook = $(".jq_book_chosed").attr('bookid');
    var $curricula_title = $(".jq_book_chosed").attr('curricula_title');
    var $thumbnail_img = $(".thumbnail_img_curricula").attr('src');
    var $idcorriculum = $('#books_pagination').attr('att_idcorriculum');

    $(".page-loader-wrapper").show();
    $.ajax({
        url: url + "/" + $idcorriculum + '/saveedit',
        type: 'POST',
        data: {
            _token: _token,
            'title_ar': $title_ar,
            'title_en': $title_en,
            'description_ar': $description_ar,
            'description_en': $description_en,
            'category': $category,
            'level': $level,
            'idbook': $idbook,
            'curricula_title': $curricula_title,
            'thumbnail_img': $thumbnail_img,
            'idcorriculum': $idcorriculum
        },
        dataType: 'html',
        success: function (data) {
            if(isJSON(data))    {
                if(data.code=203){
                    swal({
                        title: window.Lang.lang.AreUSure,
                        text: window.Lang.lang.MessageDeleteLessonCau,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: window.Lang.lang.Yes,
                        closeOnConfirm: false
                    }, function () {
                        $(".page-loader-wrapper").show();
                        data=JSON.parse(data);
                        $.ajax({
                            url: SITE_URL+Language+'/curriculums/delete-and-add-lesson',
                            type: 'GET',
                            data: {category: data.category, curriculumsid:data.curriculumsid , level: data.level,idbook:data.idbook},
                            dataType: 'html',
                            success: function (data) {
                                $("#popup_content").html(data);
                                $(".page-loader-wrapper").hide();
                                swal.close();
                                hidepopup();
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log('error')
                            }
                        });

                    });
                }
            }else{
                $("#super_content").html(data);
                hidepopup();
            }
            $(".page-loader-wrapper").hide();

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error')
        }
    });
});


//
$(document).on('change', '#categorybooks', function (e) {
    e.preventDefault();
    $idcorriculum = $('#books_pagination').attr('att_idcorriculum');
    getbooksAPI(1, $idcorriculum);
});

$(document).on('click', '#categorybooks-search', function (e) {
    e.preventDefault();
    $idcorriculum = $('#books_pagination').attr('att_idcorriculum');
    getbooksAPI(1, $idcorriculum);
});

$(document).on('change', '#curricula_level,#curricula_cat', function (e) {
    e.preventDefault();
    $("#formcurriculum").submit();
});
$(document).on('click', '.btn-deleteLessons', function () {
    var _id = $(this).attr('att_id');
    var cerate_by="-1";
    var level="-1";
    var category="-1";
    var curricula="-1";
    var standard="-1";
    var orderby="-1";
    var descask="-1";
    x=getUrlParameter("cerate_by");
    if(x!=undefined){
        cerate_by=x;
    }
    x=getUrlParameter("level");
    if(x!=undefined){
        level=x;
    }
    x=getUrlParameter("category");
    if(x!=undefined){
        category=x;
    }
    x=getUrlParameter("curricula");
    if(x!=undefined){
        curricula=x;
    }
    x=getUrlParameter("standard");
    if(x!=undefined){
        standard=x;
    }
    x=getUrlParameter("orderby");
    if(x!=undefined){
        orderby=x;
    }
    x=getUrlParameter("descask");
    if(x!=undefined){
        descask=x;
    }


    var filters='?cerate_by='+cerate_by+'&level='+level+'&category='+category+'&curricula='+curricula+'&standard='+standard+'&orderby='+orderby+'&descask='+descask;

    swal({
        title:  window.Lang.lang.RUSure,
        text: window.Lang.lang.RUSureDeleteLesson,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: window.Lang.lang.Yes,
        closeOnConfirm: false,
        dangerMode: true,
    }, function (value) {
        if (value == true) {
            $(".page-loader-wrapper").show();

            $.ajax({
                url: url + "/" + _id + '/deletelesson'+filters,
                type: 'GET',
                data: {_token: _token},
                dataType: 'html',
                success: function (data) {
                    $("#super_content").html(data);
                    hidepopup();
                    $(".page-loader-wrapper").hide();
                    swal.close();
                    Lobibox.notify('success', {
                        msg: Lang.lang.lessonHasBeenDelSuc
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('error')
                }
            });
        }
    });


});
$(document).on('click', '.btn-editLessons', function (e) {
    var _id = $(this).attr('att_id');
    $(".modal-body").empty().load(url + '/' + _id + "/viewedit");
    showpopup();
    $("#popup_header").html(" - "+$(this).closest("tr").find(".name").html());
});
$(document).on('click', '.btn-save_lessonedit', function (e) {
    var _id = $(this).attr('att_id');


    $(".page-loader-wrapper").show();
    $.ajax({
        url: url + "/" + _id + '/saveedit',
        type: 'POST',
        data: {
            _token: _token,
            'title': $("#title").val(),
            'description': $("#description").val(),
            'category':$("#category").find(':selected').val(),
            'level':$("#level").find(':selected').val(),
            'curricula': $("#curricula").find(':selected').val(),
            'teacher': $("#teacher").find(':selected').val(),
            'Start_Date': $("#Start_Date").val(),
            'End_Date': $("#End_Date").val(),
            'min_point': $("#min_point").val(),
            'max_point': $("#max_point").val(),
            'domain': $("#se-les-domain").val(),
            'pivot': $("#sc-les-pivot").val(),
            'standard': $("#sc-les-standard").val(),
        },
        dataType: 'html',
        success: function (HTML) {
            $(".page-loader-wrapper").hide();

            if(isJSON (HTML)){
                $.each(JSON.parse(HTML), function(i, errors){
                    $.each(errors, function(y, error){
                        showErrorMessage(error);
                        return false;
                    });
                });
            }else{
                $("#super_content").html(HTML);
                hidepopup();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error')
        }
    });


});
$(document).on('click', '.btn-save_lessonadd', function (e) {

    $(".page-loader-wrapper").show();
    $.ajax({
        url: url +  '/saveadd',
        type: 'POST',
        data: {
            _token: _token,
            'title': $("#title").val(),
            'description': $("#description").val(),
            'category':$("#category").find(':selected').val(),
            'level':$("#level").find(':selected').val(),
            'curricula': $("#curricula").find(':selected').val(),
            'teacher': $("#teacher").find(':selected').val(),
            'Start_Date': $("#Start_Date").val(),
            'End_Date': $("#End_Date").val(),
            'min_point': $("#min_point").val(),
            'max_point': $("#max_point").val(),
            'domain': $("#se-les-domain").val(),
            'pivot': $("#sc-les-pivot").val(),
            'standard': $("#sc-les-standard").val(),
        },
        dataType: 'html',
        success: function (HTML) {
            $(".page-loader-wrapper").hide();

            if(isJSON (HTML)){
                $.each(JSON.parse(HTML), function(i, errors){
                    $.each(errors, function(y, error){
                        showErrorMessage(error);
                        return false;
                    });
                });
            }else{
                $("#super_content").html(HTML);
                hidepopup();
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error')
        }
    });

});


$(document).on('click', '.TeacherSortingName,.TeacherSortingEmail,.TeacherSortingPhone,.TeacherSortingCreated_At', function (e) {
    e.preventDefault();
    var orderby='fullname';
    var sorting = getUrlParameter('descask');
    if (sorting == 'ASC') {
        sorting = 'DESC';
    } else if (sorting == 'DESC') {
        sorting = 'ASC';
    } else {
        sorting = 'ASC';
    }

    if ($(this).hasClass('TeacherSortingName')) {
        orderby = 'fullname';
    } else if ($(this).hasClass('TeacherSortingEmail')) {
        orderby = 'email';
    } else if ($(this).hasClass('TeacherSortingPhone')) {
        orderby = 'phone';
    } else if ($(this).hasClass('TeacherSortingCreated_At')) {
        orderby = 'created_at';
    }
    $('#orderby').attr({
        value: orderby
    }).appendTo('#orderby');
    $('#sort').attr({
        value: sorting
    }).appendTo('#sort');

    document.getElementById('search-form-teacher').submit();
});

$(document).on('click', '.StudentSortingName,.StudentSortingEmail,.StudentSortingPhone,.StudentSortingBirth_of_Date,.StudentSortingLevel,.StudentSortingClass', function (e) {
    e.preventDefault();
    var orderby='fullname';
    var sorting = getUrlParameter('descask');
    if (sorting == 'ASC') {
        sorting = 'DESC';
    } else if (sorting == 'DESC') {
        sorting = 'ASC';
    } else {
        sorting = 'ASC';
    }

    if ($(this).hasClass('StudentSortingName')) {
        orderby = 'fullname';
    } else if ($(this).hasClass('StudentSortingEmail')) {
        orderby = 'email';
    } else if ($(this).hasClass('StudentSortingPhone')) {
        orderby = 'phone';
    } else if ($(this).hasClass('StudentSortingBirth_of_Date')) {
        orderby = 'birthdate';
    } else if ($(this).hasClass('StudentSortingLevel')) {
        orderby = 'level_id';
    } else if ($(this).hasClass('StudentSortingClass')) {
        orderby = 'class_id';
    }
    $('#orderby').attr({
        value: orderby
    }).appendTo('#orderby');
    $('#sort').attr({
        value: sorting
    }).appendTo('#sort');

    document.getElementById('filter-search-student').submit();
});

$(document).on('click', '.AdminSortingName,.AdminSortingEmail,.AdminSortingPhone,.AdminSortingCreateDate', function (e) {
    e.preventDefault();
    var orderby='fullname';
    var sorting = getUrlParameter('descask');
    if (sorting == 'ASC') {
        sorting = 'DESC';
    } else if (sorting == 'DESC') {
        sorting = 'ASC';
    } else {
        sorting = 'ASC';
    }

    if ($(this).hasClass('AdminSortingName')) {
        orderby = 'fullname';
    } else if ($(this).hasClass('AdminSortingEmail')) {
        orderby = 'email';
    } else if ($(this).hasClass('AdminSortingPhone')) {
        orderby = 'phone';
    } else if ($(this).hasClass('AdminSortingCreateDate')) {
        orderby = 'created_at';
    }
    $('#orderby').attr({
        value: orderby
    }).appendTo('#orderby');
    $('#sort').attr({
        value: sorting
    }).appendTo('#sort');

    document.getElementById('filter-search-admin').submit();
});


$(document).on('click', '.ParentSortingName,.ParentSortingEmail,.ParentSortingPhone,.ParentSortingCreated_date', function (e) {
    e.preventDefault();
    var orderby='fullname';
    var sorting = getUrlParameter('descask');
    if (sorting == 'ASC') {
        sorting = 'DESC';
    } else if (sorting == 'DESC') {
        sorting = 'ASC';
    } else {
        sorting = 'ASC';
    }

    if ($(this).hasClass('ParentSortingName')) {
        orderby = 'fullname';
    } else if ($(this).hasClass('ParentSortingEmail')) {
        orderby = 'email';
    } else if ($(this).hasClass('ParentSortingPhone')) {
        orderby = 'phone';
    } else if ($(this).hasClass('ParentSortingCreated_date')) {
        orderby = 'created_at';
    }
    $('#orderby').attr({
        value: orderby
    }).appendTo('#orderby');
    $('#sort').attr({
        value: sorting
    }).appendTo('#sort');

    document.getElementById('filter-search-parent').submit();
});

// sorting Curriculums
$(document).on('click', '.CurriculumsSortingTitle,.CurriculumsSortingDescription', function (e) {
    e.preventDefault();
    var orderby='cu_title_'+Language;
    var sorting = getUrlParameter('descask');
    if (sorting == 'ASC') {
        sorting = 'DESC';
    } else if (sorting == 'DESC') {
        sorting = 'ASC';
    } else {
        sorting = 'ASC';
    }

    if ($(this).hasClass('CurriculumsSortingTitle')) {
        orderby = 'cu_title_'+Language;
    } else if ($(this).hasClass('CurriculumsSortingDescription')) {
        orderby = 'cu_description_'+Language;
    }
    $('#orderby').attr({
        value: orderby
    }).appendTo('#orderby');
    $('#sort').attr({
        value: sorting
    }).appendTo('#sort');

    document.getElementById('formcurriculum').submit();
});

// sorting lessons
$(document).on('click', '.LessonsSortingTitle,.LessonsSortingDescription', function (e) {
    e.preventDefault();
    var orderby='title';
    var sorting = getUrlParameter('descask');
    if (sorting == 'ASC') {
        sorting = 'DESC';
    } else if (sorting == 'DESC') {
        sorting = 'ASC';
    } else {
        sorting = 'ASC';
    }

    if ($(this).hasClass('LessonsSortingTitle')) {
        orderby = 'title';
    } else if ($(this).hasClass('LessonsSortingDescription')) {
        orderby = 'description';
    }
    $('#orderby').attr({
        value: orderby
    }).appendTo('#orderby');
    $('#sort').attr({
        value: sorting
    }).appendTo('#sort');

    document.getElementById('lesson_form').submit();
});

//Soing Standars
$(document).on('click', '.StandardSortingNumber,.StandardSortingTitle,.StandardSortingDescription,.StandardSortingCategory,.StandardSortingDomain,.StandardSortingPivot', function (e) {
    e.preventDefault();
    var orderby='title_'+Language;
    var sorting = getUrlParameter('descask');
    if (sorting == 'ASC') {
        sorting = 'DESC';
    } else if (sorting == 'DESC') {
        sorting = 'ASC';
    } else {
        sorting = 'ASC';
    }

    if ($(this).hasClass('StandardSortingNumber')) {
        orderby='standard_number';
    } else if ($(this).hasClass('StandardSortingTitle')) {
        orderby='title_'+Language;
    }else if ($(this).hasClass('StandardSortingDescription')) {
        orderby = 'description_'+Language;
    }
    else if ($(this).hasClass('StandardSortingCategory')) {
        orderby = 'category';
    } else if ($(this).hasClass('StandardSortingDomain')) {
        orderby = 'domain';
    }else if ($(this).hasClass('StandardSortingPivot')) {
        orderby = 'pivot';
    }

    $('#orderby').attr({
        value: orderby
    }).appendTo('#orderby');
    $('#sort').attr({
        value: sorting
    }).appendTo('#sort');

    document.getElementById('filter-standard-search').submit();
});

// sorting pivots
$(document).on('click', '.PivotSortingNumber,.PivotSortingTitle,.PivotSortingDescription,.PivotSortingCategory,.PivotSortingDomain', function (e) {
    e.preventDefault();
    var orderby='title_'+Language;
    var sorting = getUrlParameter('descask');
    if (sorting == 'ASC') {
        sorting = 'DESC';
    } else if (sorting == 'DESC') {
        sorting = 'ASC';
    } else {
        sorting = 'ASC';
    }

    if ($(this).hasClass('PivotSortingNumber')) {
        orderby='pivotnumber';
    } else if ($(this).hasClass('PivotSortingTitle')) {
        orderby='title_'+Language;
    }else if ($(this).hasClass('PivotSortingDescription')) {
        orderby = 'description_'+Language;
    }
    else if ($(this).hasClass('PivotSortingCategory')) {
        orderby = 'category';
    } else if ($(this).hasClass('PivotSortingDomain')) {
        orderby = 'domain';
    }

    $('#orderby').attr({
        value: orderby
    }).appendTo('#orderby');
    $('#sort').attr({
        value: sorting
    }).appendTo('#sort');

    document.getElementById('filter-pivot-search').submit();
});

//sorting competencies
$(document).on('click', '.CompetenciesSortingTitle,.CompetenciesSortingDescription', function (e) {
    e.preventDefault();
    var orderby='title_'+Language;
    var sorting = getUrlParameter('descask');
    if (sorting == 'ASC') {
        sorting = 'DESC';
    } else if (sorting == 'DESC') {
        sorting = 'ASC';
    } else {
        sorting = 'ASC';
    }

    if ($(this).hasClass('CompetenciesSortingTitle')) {
        var orderby='title_'+Language;
    } else if ($(this).hasClass('CompetenciesSortingDescription')) {
        var orderby='description_'+Language;
    }

    $('#orderby').attr({
        value: orderby
    }).appendTo('#orderby');
    $('#sort').attr({
        value: sorting
    }).appendTo('#sort');

    document.getElementById('filter-competencies-search').submit();
});
//sorting domins
$(document).on('click', '.DomainSortingNumber,.DomainSortingTitle,.DomainSortingDescription,.DomainSortingCategory', function (e) {
    e.preventDefault();
    var orderby='title_'+Language;
    var sorting = getUrlParameter('descask');
    if (sorting == 'ASC') {
        sorting = 'DESC';
    } else if (sorting == 'DESC') {
        sorting = 'ASC';
    } else {
        sorting = 'ASC';
    }

    if ($(this).hasClass('DomainSortingNumber')) {
        orderby='domainnumber';
    } else if ($(this).hasClass('DomainSortingTitle')) {
        orderby='title_'+Language;
    }else if ($(this).hasClass('DomainSortingDescription')) {
        orderby = 'description_'+Language;
    }
    else if ($(this).hasClass('DomainSortingCategory')) {
        orderby = 'category';
    }

    $('#orderby').attr({
        value: orderby
    }).appendTo('#orderby');
    $('#sort').attr({
        value: sorting
    }).appendTo('#sort');

    document.getElementById('filter-domain-search').submit();
});
//sorting quiz
$(document).on('click', '.QuizSortingTitle,.QuizSortingDescription', function (e) {
    e.preventDefault();
    var orderby='title';
    var sorting = getUrlParameter('descask');
    if (sorting == 'ASC') {
        sorting = 'DESC';
    } else if (sorting == 'DESC') {
        sorting = 'ASC';
    } else {
        sorting = 'ASC';
    }

    if ($(this).hasClass('QuizSortingTitle')) {
        orderby='title';
    } else if ($(this).hasClass('QuizSortingDescription')) {
        orderby='description';
    }

    $('#orderby').attr({
        value: orderby
    }).appendTo('#orderby');
    $('#sort').attr({
        value: sorting
    }).appendTo('#sort');

    document.getElementById('filter-search-quiz').submit();
});

//sorting badges
$(document).on('click', '.BadgesSortingTitle,.BadgesSortingDescription', function (e) {
    e.preventDefault();
    var orderby='title_'+Language;
    var sorting = getUrlParameter('descask');
    if (sorting == 'ASC') {
        sorting = 'DESC';
    } else if (sorting == 'DESC') {
        sorting = 'ASC';
    } else {
        sorting = 'ASC';
    }

    if ($(this).hasClass('BadgesSortingTitle')) {
        orderby='title_'+Language;
    } else if ($(this).hasClass('BadgesSortingDescription')) {
        orderby='description_'+Language;
    }

    $('#orderby').attr({
        value: orderby
    }).appendTo('#orderby');
    $('#sort').attr({
        value: sorting
    }).appendTo('#sort');

    document.getElementById('filter-search-badges').submit();
});

// sorting Groups
$(document).on('click', '.GroupsSortingName', function (e) {
    e.preventDefault();
    var orderby='title_'+Language;
    var sorting = getUrlParameter('descask');
    if (sorting == 'ASC') {
        sorting = 'DESC';
    } else if (sorting == 'DESC') {
        sorting = 'ASC';
    } else {
        sorting = 'ASC';
    }

    if ($(this).hasClass('GroupsSortingName')) {
        orderby='title_'+Language;
    }
    $('#orderby').attr({
        value: orderby
    }).appendTo('#orderby');
    $('#sort').attr({
        value: sorting
    }).appendTo('#sort');

    document.getElementById('filter-search-group').submit();
});

////////////  for append in select box in Standards/////////////////////////////
$(document).on('change', '.category-standards', function () {
    var  categoryId=$(this).val();

    var url=SITE_URL+Language+'/domains/get-domains-category'
    $.ajax({
        url: url,
        type: 'get',
        data: {categoryId:categoryId},
        success: function (data) {
            appendTodomainStanderdsByClass(data);
            $('.pivot-standards').html(' <option  disabled selected>-----</option>') ;
            $('.standard-standards').html(' <option  disabled selected>-----</option>') ;
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error')
        }
    });

});

$(document).on('change', '.domain-standards', function () {
    var  domain=$(this).val();

    var url=SITE_URL+Language+'/pivots/get-pivots-domain';
    $.ajax({
        url: url,
        type: 'get',
        data: {domain:domain},
        success: function (data) {

            appendToPivotsStanderdsByClass(data);
            $('.standard-standards').html(' <option  disabled selected>-----</option>') ;
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error')
        }
    });

});

$(document).on('change', '.pivot-standards', function () {
    var  pivot=$(this).val();

    var url=SITE_URL+Language+'/standards/get-standards-pivot';
    $.ajax({
        url: url,
        type: 'get',
        data: {pivot:pivot},
        success: function (data) {

            appendToStandardStandardsByClass(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error')
        }
    });

});

////////////  for append in select box in Standards end /////////////////////////////



////////////  for append in select box in Standards/////////////////////////////
function appendTodomainStanderdsByClass(data) {
    var content;
    $('.domain-standards').html('<option  disabled selected>-----</option>') ;
    $.each( data, function( key, value ) {
        content+='<option value="'+value.domain_id+'">'+value['title_'+Language]+'</option>';
    });
    $('.domain-standards').append(content) ;
}
function appendToPivotsStanderdsByClass(data) {
    var content;
    $('.pivot-standards').html(' <option  disabled selected>-----</option>') ;
    $.each( data, function( key, value ) {
        content+='<option value="'+value.pivot_id+'">'+value['title_'+Language]+'</option>';
    });
    $('.pivot-standards').append(content) ;
}
function appendToStandardStandardsByClass(data) {
    var content;
    $('.standard-standards').html(' <option  disabled selected>-----</option>') ;
    $.each( data, function( key, value ) {
        content+='<option value="'+value.standard_id+'">'+value['title_'+Language]+'</option>';
    });
    $('.standard-standards').append(content) ;
}
////////////  for append in select box in Standards end /////////////////////////////

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
$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
$(document).on('click', '.btn-addMedia', function () {
    $(".default").removeClass("slideInRight").addClass("animated slideOutRight").hide();
    $(".add-media-div").removeClass("slideOutRight").addClass("animated slideInLeft").fadeIn();
});
$(document).on('click', '.btn-backMedia', function () {
    $(".default").removeClass("slideOutRight").addClass("animated slideInRight").fadeIn();
    $(".add-media-div").removeClass("slideInLeft").addClass("animated slideOutRight").hide();
});
$(document).on('click', '.btn-addbook', function () {
    $(".default").removeClass("slideInRight").addClass("animated slideOutRight").hide();
    $(".add-book-div").removeClass("slideOutRight").addClass("animated slideInLeft").fadeIn();
    $("#listbook_api").show();
});
$(document).on('click', '.btn-backbook', function () {
    $(".default").removeClass("slideOutRight").addClass("animated slideInRight").fadeIn();
    $(".add-book-div").removeClass("slideInLeft").addClass("animated slideOutRight").hide();
});
$(document).on('change', '.jq_formdata', function () {
    $(this).parent().parent().closest(".form-line").find(".upload-view").html($(this).val())
});
$(document).on('click', '.btn-choosebook', function () {
    setTimeout(function () {
        $(".default").removeClass("slideInRight").addClass("animated slideOutRight").hide();
        $(".add-book-div").removeClass("slideOutRight").addClass("animated slideInLeft").fadeIn();
    }, 100)
});
$(document).on('click', '.nav-tabs li', function () {
    $(this).addClass("active").siblings().removeClass("active");
});
$(document).on('click', '.fullscreen-icon', function () {
    // $(".card").addClass("animated zoomIn");
    setTimeout(function () {
        $(".card").addClass("card-fullscreen");
    },50);
    $(this).hide();
    $(".fullscreen-exit-icon").show();
});
$(document).on('click', '.fullscreen-exit-icon', function () {
    // $(".card").removeClass("animated zoomIn");
    // $(".card").addClass("animated zoomOut");
    setTimeout(function () {
        $(".card").removeClass("card-fullscreen");
        // $(".card").removeClass("animated zoomOut");
    },50);
    $(this).hide();
    $(".fullscreen-icon").show();
});
$(document).on('click', '.parent .change-between-tablechart a', function () {
    $(this).addClass("active").siblings().removeClass("active");
    if($(this).hasClass("Table"))
    {
        $('#tab').val('table');
        $(".table-student-chart").show();
        $("#chart_div").hide();
        $(".ul-table-print").show();
        $(".ul-charts-print").hide();
    }
    else
    {
        $('#tab').val('chart');
        $(".table-student-chart").hide();
        $("#chart_div").show();
        $(".ul-table-print").hide();
        $(".ul-charts-print").show();
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawVisualization);

    }
});
$(document).on('click', '.parent-details .change-between-tablechart a', function () {
    $(this).addClass("active").siblings().removeClass("active");
    if($(this).hasClass("Table"))
    {
        $('#tab').val('table');
        $(".table-parent-chart").show();
        $("#chart_div1").hide();
    }
    else
    {
        $('#tab').val('chart');
        $(".table-parent-chart").hide();
        $("#chart_div1").show();
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawVisualization1);
    }
});

    function printschdule()
    {
        var divToPrint=document.getElementById("schdule-table");
        newWin= window.open("");
        newWin.document.write('<html><head>');
        newWin.document.write(' <style> @media print {* {-webkit-print-color-adjust: exact}.table-bordered thead tr th { padding: 10px;}thead tr {background: #555 !important;color: #FFF;}.bootstrap-select.btn-group .dropdown-menu li,select{display: none !important;}.bootstrap-select.btn-group .dropdown-menu li.selected{display: inline-block} .btn.dropdown-toggle.btn-default{background-color: transparent!important;border:0px !important;text-align: center;display: inline-block;}.body table#table_schedule td {padding: 10px 0px 10px 0px !important;border: 1px solid #eee;text-align: center;}.bootstrap-select{    margin: auto; text-align: center;}.table-striped > tbody > tr:nth-of-type(odd) { background-color: #f2f2f2; text-align: center; }.table-striped > tbody > tr:nth-of-type(even) {text-align: center; }.body table#table_schedule td div{display: inline-block;margin: auto;text-align: center;width: 100%;height: 10px;}.body table#table_schedule td div.dropdown-menu{display: none !important;}}</style>');
        newWin.document.write('</head><body>');
        newWin.document.write('<div style="-webkit-print-color-adjust: exact;width: 100%; min-width: 400px; display: block; overflow: hidden;border-bottom:0px solid #121313"> <div style="-webkit-print-color-adjust: exact;display: block;overflow: hidden;height: 60px;"><div style="float: left;font-size: 24px;color: #000000;display: inline-block;margin: 0px 0 0 20px;font-weight: bold;line-height: 60px;">'+window.Lang.lang.School_schedule+'</div></div> </div><div class="row"><div class="col-sm-12"><div id="table-res" class="card-box table-responsive">'+divToPrint.outerHTML+'</div></div></div>');
        newWin.document.write('</body></html>');
        newWin.print();
        newWin.close();
    }
    $(document).on('click', '.print-schdule', function () {
        printschdule();
    });


    function chart_div()
    {
        var divToPrint=document.getElementById("chart_div");
        newWin= window.open("");
        newWin.document.write('<html><head>');
        newWin.document.write(' <style> @media print {#chart_div{position: absolute;left:0;right: 0;margin: auto},* {-webkit-print-color-adjust: exact}.table-bordered thead tr th { padding: 10px;}thead tr {background: #555 !important;color: #FFF;}.bootstrap-select.btn-group .dropdown-menu li,select{display: none !important;}.bootstrap-select.btn-group .dropdown-menu li.selected{display: inline-block} .btn.dropdown-toggle.btn-default{background-color: transparent!important;border:0px !important;text-align: center;display: inline-block;}.body table#table_schedule td {padding: 10px 0px 10px 0px !important;border: 1px solid #eee;text-align: center;}.bootstrap-select{    margin: auto; text-align: center;}.table-striped > tbody > tr:nth-of-type(odd) { background-color: #f2f2f2; text-align: center; }.table-striped > tbody > tr:nth-of-type(even) {text-align: center; }.body table#table_schedule td div{display: inline-block;margin: auto;text-align: center;width: 100%;height: 10px;}.body table#table_schedule td div.dropdown-menu{display: none !important;}}</style>');
        newWin.document.write('</head><body>');
        newWin.document.write('<div style="-webkit-print-color-adjust: exact;width: 100%; min-width: 400px; display: block; overflow: hidden;border-bottom:0px solid #121313"> <div style="-webkit-print-color-adjust: exact;display: block;overflow: hidden;height: 60px;"><div style="float: left;font-size: 24px;color: #000000;display: inline-block;margin: 0px 0 0 20px;font-weight: bold;line-height: 60px;">'+window.Lang.lang.School_schedule+'</div></div> </div><div class="row"><div class="col-sm-12"><div id="table-res" class="card-box table-responsive">'+divToPrint.outerHTML+'</div></div></div>');
        newWin.document.write('</body></html>');
        newWin.print();
        newWin.close();
    }
    $(document).on('click', '.print-a', function () {
        chart_div();
    });

    function chart_div1()
    {
        var divToPrint=document.getElementById("chart_div1");
        newWin= window.open("");
        newWin.document.write('<html><head>');
        newWin.document.write(' <style> @media print {#chart_div1{position: absolute;left:0;right: 0;margin: auto},* {-webkit-print-color-adjust: exact}.table-bordered thead tr th { padding: 10px;}thead tr {background: #555 !important;color: #FFF;}.bootstrap-select.btn-group .dropdown-menu li,select{display: none !important;}.bootstrap-select.btn-group .dropdown-menu li.selected{display: inline-block} .btn.dropdown-toggle.btn-default{background-color: transparent!important;border:0px !important;text-align: center;display: inline-block;}.body table#table_schedule td {padding: 10px 0px 10px 0px !important;border: 1px solid #eee;text-align: center;}.bootstrap-select{    margin: auto; text-align: center;}.table-striped > tbody > tr:nth-of-type(odd) { background-color: #f2f2f2; text-align: center; }.table-striped > tbody > tr:nth-of-type(even) {text-align: center; }.body table#table_schedule td div{display: inline-block;margin: auto;text-align: center;width: 100%;height: 10px;}.body table#table_schedule td div.dropdown-menu{display: none !important;}}</style>');
        newWin.document.write('</head><body>');
        newWin.document.write('<div style="-webkit-print-color-adjust: exact;width: 100%; min-width: 400px; display: block; overflow: hidden;border-bottom:0px solid #121313"> <div style="-webkit-print-color-adjust: exact;display: block;overflow: hidden;height: 60px;"><div style="float: left;font-size: 24px;color: #000000;display: inline-block;margin: 0px 0 0 20px;font-weight: bold;line-height: 60px;">'+window.Lang.lang.School_schedule+'</div></div> </div><div class="row"><div class="col-sm-12"><div id="table-res" class="card-box table-responsive">'+divToPrint.outerHTML+'</div></div></div>');
        newWin.document.write('</body></html>');
        newWin.print();
        newWin.close();
    }
    $(document).on('click', '.print-b', function () {
        chart_div1();
    });

    function chart_div_class()
    {
        var divToPrint=document.getElementById("chart_div_class");
        newWin= window.open("");
        newWin.document.write('<html><head>');
        newWin.document.write(' <style> @media print {#chart_div_class{position: absolute;left:0;right: 0;margin: auto},* {-webkit-print-color-adjust: exact}.table-bordered thead tr th { padding: 10px;}thead tr {background: #555 !important;color: #FFF;}.bootstrap-select.btn-group .dropdown-menu li,select{display: none !important;}.bootstrap-select.btn-group .dropdown-menu li.selected{display: inline-block} .btn.dropdown-toggle.btn-default{background-color: transparent!important;border:0px !important;text-align: center;display: inline-block;}.body table#table_schedule td {padding: 10px 0px 10px 0px !important;border: 1px solid #eee;text-align: center;}.bootstrap-select{    margin: auto; text-align: center;}.table-striped > tbody > tr:nth-of-type(odd) { background-color: #f2f2f2; text-align: center; }.table-striped > tbody > tr:nth-of-type(even) {text-align: center; }.body table#table_schedule td div{display: inline-block;margin: auto;text-align: center;width: 100%;height: 10px;}.body table#table_schedule td div.dropdown-menu{display: none !important;}}</style>');
        newWin.document.write('</head><body>');
        newWin.document.write('<div style="-webkit-print-color-adjust: exact;width: 100%; min-width: 400px; display: block; overflow: hidden;border-bottom:0px solid #121313"> <div style="-webkit-print-color-adjust: exact;display: block;overflow: hidden;height: 60px;"><div style="float: left;font-size: 24px;color: #000000;display: inline-block;margin: 0px 0 0 20px;font-weight: bold;line-height: 60px;">'+window.Lang.lang.School_schedule+'</div></div> </div><div class="row"><div class="col-sm-12"><div id="table-res" class="card-box table-responsive">'+divToPrint.outerHTML+'</div></div></div>');
        newWin.document.write('</body></html>');
        newWin.print();
        newWin.close();
    }
    $(document).on('click', '.print-c', function () {
        chart_div_class();
    });

    function chart_div_all()
    {
        var divToPrint=document.getElementById("chart_div_all");
        newWin= window.open("");
        newWin.document.write('<html><head>');
        newWin.document.write(' <style> @media print {#chart_div_all{position: absolute;left:0;right: 0;margin: auto},* {-webkit-print-color-adjust: exact}.table-bordered thead tr th { padding: 10px;}thead tr {background: #555 !important;color: #FFF;}.bootstrap-select.btn-group .dropdown-menu li,select{display: none !important;}.bootstrap-select.btn-group .dropdown-menu li.selected{display: inline-block} .btn.dropdown-toggle.btn-default{background-color: transparent!important;border:0px !important;text-align: center;display: inline-block;}.body table#table_schedule td {padding: 10px 0px 10px 0px !important;border: 1px solid #eee;text-align: center;}.bootstrap-select{    margin: auto; text-align: center;}.table-striped > tbody > tr:nth-of-type(odd) { background-color: #f2f2f2; text-align: center; }.table-striped > tbody > tr:nth-of-type(even) {text-align: center; }.body table#table_schedule td div{display: inline-block;margin: auto;text-align: center;width: 100%;height: 10px;}.body table#table_schedule td div.dropdown-menu{display: none !important;}}</style>');
        newWin.document.write('</head><body>');
        newWin.document.write('<div style="-webkit-print-color-adjust: exact;width: 100%; min-width: 400px; display: block; overflow: hidden;border-bottom:0px solid #121313"> <div style="-webkit-print-color-adjust: exact;display: block;overflow: hidden;height: 60px;"><div style="float: left;font-size: 24px;color: #000000;display: inline-block;margin: 0px 0 0 20px;font-weight: bold;line-height: 60px;">'+window.Lang.lang.School_schedule+'</div></div> </div><div class="row"><div class="col-sm-12"><div id="table-res" class="card-box table-responsive">'+divToPrint.outerHTML+'</div></div></div>');
        newWin.document.write('</body></html>');
        newWin.print();
        newWin.close();
    }
    $(document).on('click', '.print-d', function () {
        chart_div_all();
    });

$(document).on('click', '.classes .change-between-tablechart a', function () {
    $(this).addClass("active").siblings().removeClass("active");
    if($(this).hasClass("Table"))
    {
        $('#tab').val('table');
        $(".table-parent-chart").show();
        $("#chart_div_class").hide();
        $(".ul-table-print").show();
        $(".ul-charts-print").hide();
    }
    else
    {
        $('#tab').val('chart');
        $(".table-parent-chart").hide();
        $("#chart_div_class").show();
        $(".ul-table-print").hide();
        $(".ul-charts-print").show();
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawVisualizationclass);
    }
});
$(document).on('click', '.allchart .change-between-tablechart a', function () {
    $(this).addClass("active").siblings().removeClass("active");
    if($(this).hasClass("Table"))
    {
        $('#tab').val('table');
        $(".table-parent-chart").show();
        $("#chart_div_all").hide();
        $(".ul-table-print").show();
        $(".ul-charts-print").hide();
    }
    else
    {
        $('#tab').val('chart');
        $(".table-parent-chart").hide();
        $("#chart_div_all").show();
        $(".ul-table-print").hide();
        $(".ul-charts-print").show();
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawVisualizationall);
    }
});
$(document).on('click', '.tree ul li.marg i.btn-a', function () {
    $(this).siblings("ul").slideToggle();
    if ($(this).attr("data") == "0") {
        $(this).html("add_circle_outline");
        $(this).attr("data", "1");
    }
    else if ($(this).html("add_circle_outline")) {
        $(this).html("remove_circle_outline");
        $(this).attr("data", "0");
    }
});
$(document).ready(function () {
    $(document).click(function(event) {
        if (!$(event.target).closest(".action .add-std-to-group,.action .add-std-to-group .material-icons,.loader,.preloader,.preloader *,#popup_content,.btn.btn-primary,.action a i,.modal-header,.modal-title,.sweet-overlay ,.sweet-overlay *,.sweet-alert ,.sweet-alert *,table.table-bordered.dataTable td.action a,button.close,.add-child,.ui-datepicker ,.ui-widget,.ui-datepicker-next,.ui-datepicker-prev,.page-loader-wrapper").length) {
            // $("body").find(".modal").hide();
            if($('#popup-container').hasClass('in')){
                hidepopupWithConfirm();
            }
        }
    });
    // knob progress loader
    $('.knob').knob({
        draw: function () {
            // "tron" case
            if (this.$.data('skin') == 'tron') {

                var a = this.angle(this.cv)  // Angle
                    , sa = this.startAngle          // Previous start angle
                    , sat = this.startAngle         // Start angle
                    , ea                            // Previous end angle
                    , eat = sat + a                 // End angle
                    , r = true;

                this.g.lineWidth = this.lineWidth;

                this.o.cursor
                && (sat = eat - 0.2)
                && (eat = eat + 0.2);

                if (this.o.displayPrevious) {
                    ea = this.startAngle + this.angle(this.value);
                    this.o.cursor
                    && (sa = ea - 0.2)
                    && (ea = ea + 0.2);
                    this.g.beginPath();
                    this.g.strokeStyle = this.previousColor;
                    this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
                    this.g.stroke();
                }

                this.g.beginPath();
                this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
                this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
                this.g.stroke();

                this.g.lineWidth = 1;
                this.g.beginPath();
                this.g.strokeStyle = this.o.fgColor;
                this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
                this.g.stroke();

                return false;
            }
        }
    });



    $('.column100').on('mouseover', function () {
        var table1 = $(this).parent().parent().parent();
        var table2 = $(this).parent().parent();
        var verTable = $(table1).data('vertable') + "";
        var column = $(this).data('column') + "";

        $(table2).find("." + column).addClass('hov-column-' + verTable);
        $(table1).find(".row100.head ." + column).addClass('hov-column-head-' + verTable);
    });
    $('.column100').on('mouseout', function () {
        var table1 = $(this).parent().parent().parent();
        var table2 = $(this).parent().parent();
        var verTable = $(table1).data('vertable') + "";
        var column = $(this).data('column') + "";
        $(table2).find("." + column).removeClass('hov-column-' + verTable);
        $(table1).find(".row100.head ." + column).removeClass('hov-column-head-' + verTable);
    });
    $(document).on('change', ':file', function () {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });
    // We can watch for our custom `fileselect` event like this
    $(':file').on('fileselect', function (event, numFiles, label) {
        var input = $(this).parents('.form-line').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;

        if (input.length) {
            input.val(log);
        } else {
        }
    });
    $.AdminBSB.browser.activate();
    $.AdminBSB.leftSideBar.activate();
    $.AdminBSB.rightSideBar.activate();
    $.AdminBSB.navbar.activate();
    $.AdminBSB.dropdownMenu.activate();
    $.AdminBSB.input.activate();
    $.AdminBSB.select.activate();
    $.AdminBSB.search.activate();
    skinChanger();
    activateNotificationAndTasksScroll();
    setSkinListHeightAndScroll(true);
    setSettingListHeightAndScroll(true);
    $(window).resize(function () {
        setSkinListHeightAndScroll(false);
        setSettingListHeightAndScroll(false);
    });
    setTimeout(function () {
        $('.page-loader-wrapper').fadeOut();
    }, 50);
    // loadPicker();
    $('.js-sweetalert button').on('click', function () {
        var type = $(this).data('type');
        if (type === 'basic') {
            showBasicMessage();
        }
        else if (type === 'with-title') {
            showWithTitleMessage();
        }
        else if (type === 'success') {
            showSuccessMessage();
        }
        else if (type === 'confirm') {
            showConfirmMessage();
        }
        else if (type === 'cancel') {
            showCancelMessage();
        }
        else if (type === 'with-custom-icon') {
            showWithCustomIconMessage();
        }
        else if (type === 'html-message') {
            showHtmlMessage();
        }
        else if (type === 'autoclose-timer') {
            showAutoCloseTimerMessage();
        }
        else if (type === 'prompt') {
            showPromptMessage();
        }
        else if (type === 'ajax-loader') {
            showAjaxLoaderMessage();
        }
        else if (type === 'basic1') {
            showBasicMessage1();

        }
    });
});
function showpopup() {
    $('#popup-container').fadeIn();
    $('.modal-content').removeClass('animated0 fadeOutUp');
    $('.modal-content').addClass('animated zoomIn');
    $('#popup-container').addClass('in');

//if you click on anything except the modal itself or the "#popup_content,.btn.btn-primary,.action a i,.modal-title" link, close the modal
}

$("body").bind('touchmove', function (e) {
    e.preventDefault();
});
function hidepopup() {
    $('.modal-content').removeClass('animated0 zoomIn');
    $('.modal-content').addClass('animated0 fadeOutUp');
    $('#popup-container').removeClass('in');
    setTimeout(function () {
        $('#popup-container').hide();
    },500);
}
if (typeof jQuery === "undefined") {
    throw new Error("jQuery plugins need to be before this file");
}
$.AdminBSB = {};
$.AdminBSB.options = {
    colors: {
        red: '#F44336',
        pink: '#E91E63',
        purple: '#9C27B0',
        deepPurple: '#673AB7',
        indigo: '#3F51B5',
        blue: '#2196F3',
        lightBlue: '#03A9F4',
        cyan: '#00BCD4',
        teal: '#009688',
        green: '#4CAF50',
        lightGreen: '#8BC34A',
        lime: '#CDDC39',
        yellow: '#ffe821',
        amber: '#FFC107',
        orange: '#FF9800',
        deepOrange: '#FF5722',
        brown: '#795548',
        grey: '#9E9E9E',
        blueGrey: '#607D8B',
        black: '#000000',
        white: '#ffffff',
        manhalgreen: '#6ac13c'
    },
    leftSideBar: {
        scrollColor: 'rgba(0,0,0,0.5)',
        scrollWidth: '4px',
        scrollAlwaysVisible: false,
        scrollBorderRadius: '0',
        scrollRailBorderRadius: '0',
        scrollActiveItemWhenPageLoad: true,
        breakpointWidth: 1170
    },
    dropdownMenu: {
        effectIn: 'fadeIn',
        effectOut: 'fadeOut'
    }
}
/* Left Sidebar - Function =================================================================================================
 *  You can manage the left sidebar menu options
 *
 */
$.AdminBSB.leftSideBar = {
    activate: function () {
        var _this = this;
        var $body = $('body');
        var $overlay = $('.overlay');

        //Close sidebar
        $(window).click(function (e) {
            var $target = $(e.target);
            if (e.target.nodeName.toLowerCase() === 'i') {
                $target = $(e.target).parent();
            }

            if (!$target.hasClass('bars') && _this.isOpen() && $target.parents('#leftsidebar').length === 0) {
                if (!$target.hasClass('js-right-sidebar')) $overlay.fadeOut();
                // $body.removeClass('overlay-open');
            }
        });

        $.each($('.menu-toggle.toggled'), function (i, val) {
            $(val).next().slideToggle(0);
        });

        //When page load
        $.each($('.menu .list li.active'), function (i, val) {
            var $activeAnchors = $(val).find('a:eq(0)');

            $activeAnchors.addClass('toggled');
            $activeAnchors.next().show();
        });
        //Collapse or Expand Menu
        $('.menu-toggle').on('click', function (e) {
            var $this = $(this);
            var $content = $this.next();

            if ($($this.parents('ul')[0]).hasClass('list')) {
                var $not = $(e.target).hasClass('menu-toggle') ? e.target : $(e.target).parents('.menu-toggle');

                $.each($('.menu-toggle.toggled').not($not).next(), function (i, val) {
                    if ($(val).is(':visible')) {
                        $(val).prev().toggleClass('toggled');
                        $(val).slideUp();
                    }
                });
            }
            $this.toggleClass('toggled');
            $content.slideToggle(320);
        });
        //Set menu height
        _this.setMenuHeight();
        _this.checkStatuForResize(true);
        $(window).resize(function () {
            _this.setMenuHeight();
            _this.checkStatuForResize(false);
        });
        //Set Waves
        Waves.attach('.menu .list a', ['waves-block']);
        Waves.init();
    },
    setMenuHeight: function (isFirstTime) {
        if (typeof $.fn.slimScroll != 'undefined') {
            var configs = $.AdminBSB.options.leftSideBar;
            var height = ($(window).height() - ($('.legal').outerHeight() + $('.user-info').outerHeight() + $('.navbar').innerHeight()));
            var $el = $('.list');
            $el.slimscroll({
                height: height-40 + "px",
                color: configs.scrollColor,
                size: configs.scrollWidth,
                alwaysVisible: configs.scrollAlwaysVisible,
                borderRadius: configs.scrollBorderRadius,
                railBorderRadius: configs.scrollRailBorderRadius
            });
            //Scroll active menu item when page load, if option set = true
            if ($.AdminBSB.options.leftSideBar.scrollActiveItemWhenPageLoad) {
                // var activeItemOffsetTop = $('.menu .list li.active')[0].offsetTop
                //if (activeItemOffsetTop > 150) $el.slimscroll({ scrollTo: activeItemOffsetTop + 'px' });
            }
        }
    },
    checkStatuForResize: function (firstTime) {
        var $body = $('body');
        var $openCloseBar = $('.navbar .navbar-header .bars');
        var width = $body.width();
        if (firstTime) {
            $body.find('.content, .sidebar').addClass('no-animate').delay(1000).queue(function () {
                $(this).removeClass('no-animate').dequeue();
            });
        }
        if (width > $.AdminBSB.options.leftSideBar.breakpointWidth) {
            $body.addClass('ls-closed');
            // $openCloseBar.fadeIn();
        }
        else {
            $body.removeClass('ls-closed');
            // $openCloseBar.fadeOut();
        }
    },
    isOpen: function () {
        return $('body').hasClass('overlay-open');
    }
};
//==========================================================================================================================
/* Right Sidebar - Function ================================================================================================
 *  You can manage the right sidebar menu options
 */
$.AdminBSB.rightSideBar = {
    activate: function () {
        var _this = this;
        var $sidebar = $('#rightsidebar');
        var $overlay = $('.overlay');
        //Close sidebar
        $(window).click(function (e) {
            var $target = $(e.target);
            if (e.target.nodeName.toLowerCase() === 'i') {
                $target = $(e.target).parent();
            }
            if (!$target.hasClass('js-right-sidebar') && _this.isOpen() && $target.parents('#rightsidebar').length === 0) {
                if (!$target.hasClass('bars')) $overlay.fadeOut();
                $sidebar.removeClass('open');
            }
        });
        $('.js-right-sidebar').on('click', function () {
            $sidebar.toggleClass('open');
            if (_this.isOpen()) {
                $overlay.fadeIn();
            } else {
                $overlay.fadeOut();
            }
        });
    },
    isOpen: function () {
        return $('.right-sidebar').hasClass('open');
    }
}
//==========================================================================================================================
/* Searchbar - Function ================================================================================================
 *  You can manage the search bar
 */
$.AdminBSB.search = {
    activate: function () {
        var _this = this;
        //Search button click event
        $('.js-search').on('click', function () {
            _this.showSearchBar();
        });
        //Close search click event
        $('.search-bar').find('.close-search').on('click', function () {
            _this.hideSearchBar();
        });
        //ESC key on pressed
        $('.search-bar').find('input[type="text"]').on('keyup', function (e) {
            if (e.keyCode == 27) {
                _this.hideSearchBar();
            }
        });
    },
    showSearchBar: function () {
        $('.search-bar').addClass('open');
        $('.search-bar').find('input[type="text"]').focus();
    },
    hideSearchBar: function () {
        $('.search-bar').removeClass('open');
        $('.search-bar').find('input[type="text"]').val('');
    }
}
//==========================================================================================================================
/* Navbar - Function =======================================================================================================
 *  You can manage the navbar
 *
 */
$.AdminBSB.navbar = {
    activate: function () {
        var $body = $('body');
        var $overlay = $('.overlay');

        if($(window).width()<=768)
        {
            $body.removeClass('overlay-open');
            $(".write-message-container").removeClass('active');

            $body.addClass('full-width-admin');

        }
        //Open left sidebar panel
        $('.bars').on('click', function () {
                $body.toggleClass('overlay-open');
                $body.toggleClass('full-width-admin');
                $(".write-message-container").toggleClass('active');


            //if ($body.hasClass('overlay-open')) { $overlay.fadeOut(); } else { $overlay.fadeOut(); }
        });

        //Close collapse bar on click event
        $('.nav [data-close="true"]').on('click', function () {
            var isVisible = $('.navbar-toggle').is(':visible');
            var $navbarCollapse = $('.navbar-collapse');

            if (isVisible) {
                $navbarCollapse.slideUp(function () {
                    $navbarCollapse.removeClass('in').removeAttr('style');
                });
            }
        });
    }
}
//==========================================================================================================================
/* Input - Function ========================================================================================================
 *  You can manage the inputs(also textareas) with name of class 'form-control'
 *
 */
$.AdminBSB.input = {
    activate: function () {
        //On focus event
        $('.form-control').focus(function () {
            $(this).parent().addClass('focused');
        });

        //On focusout event
        $('.form-control').focusout(function () {
            var $this = $(this);
            if ($this.parents('.form-group').hasClass('form-float')) {
                if ($this.val() == '') {
                    $this.parents('.form-line').removeClass('focused');
                }
            }
            else {
                $this.parents('.form-line').removeClass('focused');
            }
        });

        //On label click
        $('body').on('click', '.form-float .form-line .form-label', function () {
            $(this).parent().find('input').focus();
        });

        //Not blank form
        $('.form-control').each(function () {
            if ($(this).val() !== '') {
                $(this).parents('.form-line').addClass('focused');
            }
        });
    }
}
//==========================================================================================================================
/* Form - Select - Function ================================================================================================
 *  You can manage the 'select' of form elements
 *
 */
$.AdminBSB.select = {
    activate: function () {
        if ($.fn.selectpicker) {
            $('select:not(.ms)').selectpicker();
        }
    }
}
//==========================================================================================================================
/* DropdownMenu - Function =================================================================================================
 *  You can manage the dropdown menu
 *
 */
$.AdminBSB.dropdownMenu = {
    activate: function () {
        var _this = this;

        $('.dropdown, .dropup, .btn-group').on({
            "show.bs.dropdown": function () {
                var dropdown = _this.dropdownEffect(this);
                _this.dropdownEffectStart(dropdown, dropdown.effectIn);
            },
            "shown.bs.dropdown": function () {
                var dropdown = _this.dropdownEffect(this);
                if (dropdown.effectIn && dropdown.effectOut) {
                    _this.dropdownEffectEnd(dropdown, function () {
                    });
                }
            },
            "hide.bs.dropdown": function (e) {
                var dropdown = _this.dropdownEffect(this);
                if (dropdown.effectOut) {
                    e.preventDefault();
                    _this.dropdownEffectStart(dropdown, dropdown.effectOut);
                    _this.dropdownEffectEnd(dropdown, function () {
                        dropdown.dropdown.removeClass('open');
                    });
                }
            }
        });
        //Set Waves
        Waves.attach('.dropdown-menu li a', ['waves-block']);
        Waves.init();
    },
    dropdownEffect: function (target) {
        var effectIn = $.AdminBSB.options.dropdownMenu.effectIn, effectOut = $.AdminBSB.options.dropdownMenu.effectOut;
        var dropdown = $(target), dropdownMenu = $('.dropdown-menu', target);

        if (dropdown.length > 0) {
            var udEffectIn = dropdown.data('effect-in');
            var udEffectOut = dropdown.data('effect-out');
            if (udEffectIn !== undefined) {
                effectIn = udEffectIn;
            }
            if (udEffectOut !== undefined) {
                effectOut = udEffectOut;
            }
        }

        return {
            target: target,
            dropdown: dropdown,
            dropdownMenu: dropdownMenu,
            effectIn: effectIn,
            effectOut: effectOut
        };
    },
    dropdownEffectStart: function (data, effectToStart) {
        if (effectToStart) {
            data.dropdown.addClass('dropdown-animating');
            data.dropdownMenu.addClass('animated dropdown-animated');
            data.dropdownMenu.addClass(effectToStart);
        }
    },
    dropdownEffectEnd: function (data, callback) {
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        data.dropdown.one(animationEnd, function () {
            data.dropdown.removeClass('dropdown-animating');
            data.dropdownMenu.removeClass('animated dropdown-animated');
            data.dropdownMenu.removeClass(data.effectIn);
            data.dropdownMenu.removeClass(data.effectOut);

            if (typeof callback == 'function') {
                callback();
            }
        });
    }
}
//==========================================================================================================================

/* Browser - Function ======================================================================================================
 *  You can manage browser
 *
 */
var edge = 'Microsoft Edge';
var ie10 = 'Internet Explorer 10';
var ie11 = 'Internet Explorer 11';
var opera = 'Opera';
var firefox = 'Mozilla Firefox';
var chrome = 'Google Chrome';
var safari = 'Safari';

$.AdminBSB.browser = {
    activate: function () {
        var _this = this;
        var className = _this.getClassName();

        if (className !== '') $('html').addClass(_this.getClassName());
    },
    getBrowser: function () {
        var userAgent = navigator.userAgent.toLowerCase();

        if (/edge/i.test(userAgent)) {
            return edge;
        } else if (/rv:11/i.test(userAgent)) {
            return ie11;
        } else if (/msie 10/i.test(userAgent)) {
            return ie10;
        } else if (/opr/i.test(userAgent)) {
            return opera;
        } else if (/chrome/i.test(userAgent)) {
            return chrome;
        } else if (/firefox/i.test(userAgent)) {
            return firefox;
        } else if (!!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/)) {
            return safari;
        }

        return undefined;
    },
    getClassName: function () {
        var browser = this.getBrowser();

        if (browser === edge) {
            return 'edge';
        } else if (browser === ie11) {
            return 'ie11';
        } else if (browser === ie10) {
            return 'ie10';
        } else if (browser === opera) {
            return 'opera';
        } else if (browser === chrome) {
            return 'chrome';
        } else if (browser === firefox) {
            return 'firefox';
        } else if (browser === safari) {
            return 'safari';
        } else {
            return '';
        }
    }
}
//==========================================================================================================================

//Skin changer
function skinChanger() {
    $('.right-sidebar .demo-choose-skin li').on('click', function () {
        var $body = $('body');
        var $this = $(this);

        var existTheme = $('.right-sidebar .demo-choose-skin li.active').data('theme');
        $('.right-sidebar .demo-choose-skin li').removeClass('active');
        $body.removeClass('theme-' + existTheme);
        $this.addClass('active');
        $body.addClass('theme-' + $this.data('theme'));
    });
}

//Skin tab content set height and show scroll
function setSkinListHeightAndScroll(isFirstTime) {
    var height = $(window).height() - ($('.navbar').innerHeight() + $('.right-sidebar .nav-tabs').outerHeight());
    var $el = $('.demo-choose-skin');

    if (!isFirstTime) {
        $el.slimScroll({destroy: true}).height('auto');
        $el.parent().find('.slimScrollBar, .slimScrollRail').remove();
    }

    $el.slimscroll({
        height: height-40 + "px",
        color: 'rgba(0,0,0,0.5)',
        size: '6px',
        alwaysVisible: false,
        borderRadius: '0',
        railBorderRadius: '0'
    });
}

//Setting tab content set height and show scroll
function setSettingListHeightAndScroll(isFirstTime) {
    var height = $(window).height() - ($('.navbar').innerHeight() + $('.right-sidebar .nav-tabs').outerHeight());
    var $el = $('.right-sidebar .demo-settings');

    if (!isFirstTime) {
        $el.slimScroll({destroy: true}).height('auto');
        $el.parent().find('.slimScrollBar, .slimScrollRail').remove();
    }

    $el.slimscroll({
        height: height-40 + "px",
        color: 'rgba(0,0,0,0.5)',
        size: '6px',
        alwaysVisible: false,
        borderRadius: '0',
        railBorderRadius: '0'
    });
}

//Activate notification and task dropdown on top right menu
function activateNotificationAndTasksScroll() {
    $('.navbar-right .dropdown-menu .body .menu').slimscroll({
        height: '254px',
        color: 'rgba(0,0,0,0.5)',
        size: '4px',
        alwaysVisible: false,
        borderRadius: '0',
        railBorderRadius: '0'
    });
}

//Google Analiytics ======================================================================================
addLoadEvent(loadTracking);
var trackingId = 'UA-30038099-6';

function addLoadEvent(func) {
    var oldonload = window.onload;
    if (typeof window.onload != 'function') {
        window.onload = func;
    } else {
        window.onload = function () {
            oldonload();
            func();
        }
    }
}

function loadTracking() {
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

    ga('create', trackingId, 'auto');
    ga('send', 'pageview');
}
//========================================================================================================


function loadPicker() {
    $( ".selector" ).datepicker( "refresh" );
    $('.datepicker').datepicker({
        dateFormat: "yy-mm-dd",
        changeYear: true,
        changeMonth: true,
        yearRange: "1950:2019"
    });
}
//These codes takes from http://t4t5.github.io/sweetalert/
function showBasicMessage1() {
    swal({
        title: 'The time Finshed',
        text: 'quiz time',
        showCancelButton: false,
        showConfirmButton: false
    });
}
function showBasicMessage() {
    swal("Here's a message!");
}

function showWithTitleMessage() {
    swal("Here's a message!", "It's pretty, isn't it?");
}

function showSuccessMessage() {
    swal("Good job!", "You clicked the button!", "success");
}

function showConfirmMessage() {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary file!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    }, function () {
        swal("Deleted!", "Your imaginary file has been deleted.", "success");
    });
}

function showCancelMessage() {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary file!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plx!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            swal("Deleted!", "Your imaginary file has been deleted.", "success");
        } else {
            swal("Cancelled", "Your imaginary file is safe :)", "error");
        }
    });
}

function showWithCustomIconMessage() {
    swal({
        title: "Sweet!",
        text: "Here's a custom image.",
        imageUrl: "../../images/thumbs-up.png"
    });
}

function showHtmlMessage() {
    swal({
        title: "HTML <small>Title</small>!",
        text: "A custom <span style=\"color: #CC0000\">html<span> message.",
        html: true
    });
}

function showAutoCloseTimerMessage() {
    swal({
        title: "Auto close alert!",
        text: "I will close in 2 seconds.",
        timer: 2000,
        showConfirmButton: false
    });
}

function showPromptMessage() {
    swal({
        title: "An input!",
        text: "Write something interesting:",
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Write something"
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            swal.showInputError("You need to write something!");
            return false
        }
        swal("Nice!", "You wrote: " + inputValue, "success");
    });
}

function showAjaxLoaderMessage() {
    swal({
        title: "Ajax request example",
        text: "Submit to run ajax request",
        type: "info",
        showCancelButton: true,
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
    }, function () {
        setTimeout(function () {
            swal("Ajax request finished!");
        }, 2000);
    });
}

//start google charts function


// function drawVisualization() {
//     // Some raw data (not necessarily accurate)
//     var data = google.visualization.arrayToDataTable([
//         ['Category', 'Result', 'Progress', 'Awards'],
//         ['Books',     100,           90,         4   ],
//         ['Storys',    2,           50,         5    ],
//         ['Games',     8,           30,         7    ],
//         ['Worksheet', 7,           20,         9    ],
//         ['Quizzes ',  4,           100,        1    ]
//     ]);
//     var options = {
//         title : 'Student Chart for parents',
//         vAxis: {title: 'percentage'},
//         hAxis: {title: 'Categories'},
//         seriesType: 'bars',
//         series: {10: {type: 'line'}},
//         isStacked:'number'
//
//     };
//     var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
//     chart.draw(data, options);
// }
//
// function drawVisualization1() {
//     // Some raw data (not necessarily accurate)
//     var data = google.visualization.arrayToDataTable([
//         ['Lessons', 'Result', 'Progress'],
//         ['Lesson 1', 1, 90],
//         ['Lesson 2', 2, 50],
//         ['Lesson 3', 8, 30],
//         ['Lesson 4', 7, 20],
//         ['Lesson 5', 4, 100]
//     ]);
//     var options = {
//         title : 'Student Chart for parents details',
//         vAxis: {title: 'percentage'},
//         hAxis: {title: 'Lessons'},
//         seriesType: 'bars',
//         series: {10: {type: 'line'}},
//         isStacked:'number'
//
//     };
//     var chart = new google.visualization.ComboChart(document.getElementById('chart_div1'));
//     chart.draw(data, options);
// }

/*
function drawVisualizationclass() {
    // Some raw data (not necessarily accurate)
    var data = google.visualization.arrayToDataTable([
        ['Student Name', 'Result', 'Progress','Awards'],
        ['Oday', 1, 90, 100],
        ['Hussam', 2, 50 , 50],
        ['Khalid', 8, 30, 30],
        ['Mahmoud', 7, 20, 20],
        ['Hassan', 4, 100, 80]
    ]);
    var options = {
        title : 'Student Chart for classes',
        vAxis: {title: 'percentage'},
        hAxis: {title: 'Student Name'},
        seriesType: 'bars',
        series: {10: {type: 'line'}},
        isStacked:'number'

    };
    var chart = new google.visualization.ComboChart(document.getElementById('chart_div_class'));
    chart.draw(data, options);
}

function drawVisualizationall() {
    // Some raw data (not necessarily accurate)
    var data = google.visualization.arrayToDataTable([
        ['Student Name', 'Math', 'Arabic','English','French','Science','Average'],
        ['Oday', 10, 90, 100 , 20, 50 , 50],
        ['Hussam', 20, 50 , 50, 70, 20, 20],
        ['Khalid', 80, 30, 30, 30, 90, 100],
        ['Mahmoud', 70, 20, 204, 100, 80,80],
        ['Hassan', 40, 100, 80, 20, 50 , 50]
    ]);
    var options = {
        title : 'Student Chart for Categories',
        vAxis: {title: 'percentage'},
        hAxis: {title: 'Student Name'},
        seriesType: 'bars',
        series: {10: {type: 'line'}},
        isStacked:'number'
    };
    var chart = new google.visualization.ComboChart(document.getElementById('chart_div_all'));
    chart.draw(data, options);
}*/
//End google charts function

//start scroll chat function
function scrollChat() {
    var element = $(".chat");
    var Hheight = element.get(0).scrollHeight;
    element.animate({scrollTop: Hheight});
}
//end scroll chat function
