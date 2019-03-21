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
