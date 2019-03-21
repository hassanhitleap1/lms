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