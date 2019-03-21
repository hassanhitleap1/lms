var idli=0;
var countLi =0;
$(window).load(function(){
    //$('.vertical-slider-viewer ul li').first().click();
    $('.vertical-slider-viewer ul li').eq(idli).click();
    countLi =document.getElementById("lesson_item_container").getElementsByTagName("li").length;
});
function AnimationNext(){
    $("a.arrow-next").addClass("animated1 pulse1 infinite active");
    setTimeout(function () {
        $(".button-wrap").addClass("active");
        setTimeout(function () {
            $(".button-wrap").removeClass("active");
            $("a.arrow-next").removeClass("animated1 pulse1 infinite active");
        },6000)
    },600)
}

//function to set value for worksheet & stories - By Hussam
function setMediaScore(src,isFinish){
    if((src.substring(src.length - 4, src.length)).toLowerCase()==".pdf" || src.indexOf('https://www.manhal.com/platform/stories/demo/index.php?storyid=')!=-1 ||isFinish){
        var data=['cmi.score.min',0];
        runScorm(data);
        data=['cmi.score.max',100];
        runScorm(data);
        data=['cmi.score.raw',100];
        runScorm(data);
        data=['cmi.success_status','passed'];
        runScorm(data);
        data=['cmi.completion_status','completed'];
        runScorm(data);
    }
}

$(document).ready(function () {
    $(".exit-container.viewer-exit").click(function(){
        var urls=window.location.href.split("lessonsviewer");
        window.location.href=urls[0];
    });
    $( window ).on( "orientationchange", function( event ) {
        $(".lesson-viewer .vertical-slider-viewer").height(($(window).height() - ($(".navbar").height() + $(".legal").height())) - 18);
        $(".lesson-builder .vertical-slider-viewer").height(($(window).height() - ($(".navbar").height() + $(".legal").height() + $(".addlesson-viewer-container").height())) - 21);
        // $("section.content").height($(window).height() - $(".navbar").height());
        $(".lesson-viewer-iframe").height($(window).height() - $(".navbar").height());
    });
    $(".lesson-viewer .vertical-slider-viewer").height(($(window).height() - ($(".navbar").height() + $(".legal").height())) - 18);
    $(".lesson-builder .vertical-slider-viewer").height(($(window).height() - ($(".navbar").height() + $(".legal").height() + $(".addlesson-viewer-container").height())) - 21);
    $("section.content").height($(window).height() - $(".navbar").height());
    $(".lesson-viewer-iframe").height($(window).height() - $(".navbar").height());
    $(document).on('click', '.vertical-slider-viewer ul li', function () {
        var attrhref = $(this).attr("src-attr");
        idli=$(this).index();
        if(attrhref.indexOf('?') > -1){
            $(".lesson-viewer-iframe").attr("src", attrhref+"&scorm=true&origin="+SITE_URL);
        }else {
            $(".lesson-viewer-iframe").attr("src", attrhref+"?scorm=true&origin="+SITE_URL);
        }
        $(".lesson-viewer-iframe").attr("media_id",  $(this).attr("media_id"));

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
        setMediaScore(attrhref,false);
    });
    $(document).on('click', '.arrow-next', function () {

        $(".lesson-viewer .vertical-slider-viewer").animate({scrollTop: parseInt($(".vertical-slider-viewer ul li.selected").offset().top-103)});
        $(".lesson-builder .vertical-slider-viewer").animate({scrollTop: parseInt($(".vertical-slider-viewer ul li.selected").offset().top-183)});

        if ($(".vertical-slider-viewer ul li.selected").index() != -1) {
            $(".vertical-slider-viewer ul li").eq($(".vertical-slider-viewer ul li.selected").index() + 1).click();
        }

    });
    $(document).on('click', '.arrow-prev', function () {
        $(".lesson-viewer .vertical-slider-viewer").animate({scrollTop: parseInt($(".vertical-slider-viewer ul li.selected").offset().top-103)});
        $(".lesson-builder .vertical-slider-viewer").animate({scrollTop: parseInt($(".vertical-slider-viewer ul li.selected").offset().top-183)});

        if ($(".vertical-slider-viewer ul li.selected").index() != $(".vertical-slider-viewer ul li.selected").length - 1) {
            $(".vertical-slider-viewer ul li").eq($(".vertical-slider-viewer ul li.selected").index() - 1).click();
        }

    });
    $(document).on('click', '.vertical-slider-viewer ul li .item-header .item-delete', function () {
        $(this).parent().parent().fadeOut();
        setTimeout(function () {
            $(this).parent().parent().remove();
        }, 1000);
    });

    // $(document).on('click', '.addlesson-viewer-container a', function (e) {
    //     $(".modal-body").empty().load("http://127.0.0.1:8000/en/lessonsbuilder/add");
    // });
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

});
function showpopup() {
    $('#popup-container').show();
    $('#popup-container').addClass('in');
}
function hidepopup() {
    $('#popup-container').hide();
    $('#popup-container').removeClass('in');
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
                height: height + "px",
                color: configs.scrollColor,
                size: configs.scrollWidth,
                alwaysVisible: configs.scrollAlwaysVisible,
                borderRadius: configs.scrollBorderRadius,
                railBorderRadius: configs.scrollRailBorderRadius
            });

            //Scroll active menu item when page load, if option set = true
            // if ($.AdminBSB.options.leftSideBar.scrollActiveItemWhenPageLoad) {
            // var activeItemOffsetTop = $('.menu .list li.active')[0].offsetTop
            // if (activeItemOffsetTop > 150) $el.slimscroll({ scrollTo: activeItemOffsetTop + 'px' });
            // }
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
 *
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
 *
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

        //Open left sidebar panel
        $('.bars').on('click', function () {
            $body.toggleClass('overlay-open');
            $body.toggleClass('full-width-admin');
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
        height: height + 'px',
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
        height: height + 'px',
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
//These codes takes from http://t4t5.github.io/sweetalert/
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

window.addEventListener("touchmove", function (event) {
    event.preventDefault();
}, {passive: false});

function Scorm() {

    var apiurl='';

    if(window.location.href.toLowerCase().indexOf("homework")==-1){//it's lesson


        apiurl="api";
    }else{//it's homehowrk
        apiurl="api/homework-api";
    }

    this.Initialize = function (emptyVal) {
        if (true) {
            return true;
        } else {
            return false;
        }
    };
    this.Terminate = function (emptyVal) {
        if (true) {
            return true;
        } else {
            return false;
        }
    };
    this.GetLastError = function () {
        return "0";//integer in the range from 0 to 65536 inclusive, 0=no errors
    };
    this.GetDiagnostic = function (ErrCode) {
        return "error description and how to solve it";//maximum length of 255
    };
    this.Commit = function (emptyVal) {
        if (true) {
            return true;
        } else {
            return false;
        }
    };
    this.GetErrorString = function (ErrCode) {
        return 'error description';//maximum length of 255
    };
    this.GetValue = function (cmi) {

        var url = '';
        switch (cmi) {
            case 'cmi.learner_name':
                url = SITE_URL+apiurl+'/learner_name';
                break;
        }
        $.ajax({
            url: url,
            type: 'GET',
            callback: '?',
            cmi: cmi,
            data: {},
            dataType: 'application/json',
            success: function (data) {
                switch (cmi) {
                    case 'cmi.learner_name':
                        return data.username;
                        break;
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error', {"username": "yosifkamals"});
                return {"username": "yosifkamals"};
            }
        });
    };

    this.SetValue = function (cmi, val) {
        console.log(cmi, val);

        var url = '';
        var $data = {};
        var mediaid=$(".lesson-viewer-iframe").attr("media_id");
        var lessonid=$('.lesson-viewer').attr('lessonid');
        if(mediaid==null || mediaid==undefined)return;
        switch (cmi) {
            case 'cmi.success_status':
                url = SITE_URL+apiurl+'/success_status';
                $data = {mediaid:mediaid, status: val,lessonid:lessonid};
                break;
            case 'cmi.score.min':
                url = SITE_URL+apiurl+'/scoremin';
                $data = {mediaid: mediaid, scoremin: val,lessonid:lessonid};
                break;
            case 'cmi.score.max':
                url = SITE_URL+apiurl+'/scoremax';
                $data = {mediaid: mediaid, scoremax: val,lessonid:lessonid};
                break;
            case 'cmi.session_time':
                url = SITE_URL+apiurl+'/sessiontime';
                $data = {mediaid: mediaid, time: val,lessonid:lessonid};
                break;
            case 'cmi.score.raw':
                url = SITE_URL+apiurl+'/scoreraw';
                $data = {mediaid: mediaid, result: val,lessonid:lessonid};
                break;
            case 'cmi.completion_status':
                url = SITE_URL+apiurl+'/completionstatus';
                $data = {mediaid: mediaid, completion: val,lessonid:lessonid};
                $(".jq_media_item.selected").find(".item-seen").addClass("selected");

                setTimeout(function(){
                    if((idli+ 1 ) < countLi ){
                        $('.vertical-slider-viewer ul li').eq(++idli).click();
                    }
                },600);
                break;
            default:
                return;
        }

        console.log("vdata",$data);
        $.ajax({
            url: url,
            type: 'GET',
            callback: '?',
            cmi: cmi,
            data: $data,
            dataType: 'Json',
            success: function (data) {
                console.log("ajax scorm ",data);
                switch (cmi) {
                    case 'cmi.learner_name':
                        return data.username;
                        break;
                }
                if(data.action_media=='compleate'){
                    $('.user-points').html(data.points);
                    $('.user-awards').html(data.awards);
                    $('.user-progress').html(data.progress+'%');
                    $('#seen_'+data.mediaId).addClass('item-seen');
                }
                setTimeout(function(){
                    window.ajaxProcess=false;
                },100);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error');
                setTimeout(function(){
                    window.ajaxProcess=false;
                },100);
            }
        });
    };
}

window.API_1484_11 = new Scorm();
window.ajaxProcess=false;
window.addEventListener( "message",
    function (e) {
        var data= e.data.split("#");
        if(data.length==2){
            runScorm(data);
        }
    },
    false);
/**
 * Created by Dar Al-Manhal Hussam Abu Khadijeh on 23/09/2018.
 */
function runScorm(data){
    if(window.ajaxProcess==false){
        window.ajaxProcess=true;
        window.API_1484_11.SetValue(data[0],data[1]);
    }else{
        setTimeout(function(){
            runScorm(data);
        },200);
    }
}

var SITE_URL;
if (location.hostname === "localhost" || location.hostname === "127.0.0.1"){
    SITE_URL="http://localhost:8000/";
}else {
    SITE_URL="https://lms.manhal.com/";
}
Language="en";
$(document).ready(function(){

    //$("#lesson_item_container").sortable();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });



    // $(document).on('click', '.addlesson-viewer-container a', function (e) {
    //     alert();
    //     showLoading();
    //     $(".modal-body").empty().load(SITE_URL+Language+"/lessonsbuilder/"+$("#lessonid").val()+"/viewmedia",function(){
    //         //$(".types-main-container").empty().load(SITE_URL+Language+"/lessonsbuilder/item");
    //         console.log("loaded",SITE_URL+Language+"/lessonsbuilder/"+$("#lessonid").val()+"/edit");
    //         showpopup();
    //         hideLoading();
    //
    //     });
    // });

    // $(document).on("click",".jq_addmedialesson",function(){
    //     var mediaID=$(this).attr("media_id");
    // var category='';
    // if($("#Categories_media option[selected]").html()){
    //     category=$("#Categories_media option[selected]").html();
    // }else{
    //     category= 'All';
    // }
    //     html='<li class="col-sm-12 col-md-12 col-lg-12 jq_media_item" media_agree="'+mediaID+'" media_type="'+mediaID+'" media_cat="'+$("#Categories_media").val()+'" media_id="'+mediaID+'" src-attr="'+$(this).attr("media_src")+'">';
    //     html+='<div class="item-header">';
    //     html+='<div class="item-category" title="Category">'+$("#Categories_media option[selected]").html()+'</div>';
    //     html+='<div class="item-delete float-right" title="Delete"></div>';
    //     html+='</div>';
    //     html+='<a class="thumbnail jq_media_title" id="carousel-selector-'+$("#lesson_item_container li").length+'" title="'+$(this).closest(".thumbnail").find("h3").html()+'">';
    //     html+='<img src="'+$(this).closest(".thumbnail").find("img").attr("src")+'">';
    //     html+='</a>';
    //     html+='</li>';
    //     $("#lesson_item_container").append(html);
    //     $(this).addClass("disable-item");
    // });

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

    // $(document).on("click",".page-link",function(e){
    //     e.preventDefault();
    //     showLoading();
    //     $(".modal-body").load(SITE_URL+Language+"/lessonsbuilder/"+$("#lessonid").val()+"/viewmedia?page="+$(this).html()+"&mediatype="+$("#Type_media").val()+"&category="+$("#Categories_media").val()+"&grade="+$("#Grade_media").val()+"&search="+$("#searchmedia").val(),function(){
    //         hideLoading();
    //     });
    // });

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
    $(".modal-body").load(SITE_URL+Language+"/lessonsbuilder/"+$("#lessonid").val()+"/viewmedia?page=1&mediatype="+$("#Type_media").val()+"&category="+$("#Categories_media").val()+"&grade="+$("#Grade_media").val()+"&search="+$("#searchmedia").val(),function(){
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