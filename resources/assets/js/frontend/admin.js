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
