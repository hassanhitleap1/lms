let mix = require('laravel-mix');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sassEn/appEn.scss', 'public/css')
    .sass('resources/assets/sassAr/appAr.scss', 'public/css')

    .sass('resources/assets/sasslessonviewerEn/lessonviewerEn.scss', 'public/css')
    .sass('resources/assets/sasslessonviewerAr/lessonviewerAr.scss', 'public/css')
    .copy('resources/assets/images/', 'public/images/', true)
    .combine([
        'resources/assets/js/library/node-waves/waves.js',
        'resources/assets/js/library/jquery-countto/jquery.countTo.js',
        'resources/assets/js/library/jquery-slimscroll/jquery.slimscroll.js',
        'resources/assets/js/library/bootstrap-notify/bootstrap-notify.js',
        'resources/assets/js/library/notifications.js',
        'resources/assets/js/library/bootstrap-select/js/bootstrap-select.js',
        'resources/assets/js/library/moment.js',
        'resources/assets/js/library/jquery-ui.js',
        'resources/assets/js/library/sweetalert.min.js',
        'resources/assets/js/library/html2canvas.js',
        'resources/assets/js/library/ajaxform.js',
        'resources/assets/js/library/ckeditor/ckeditor.js',
        'resources/assets/js/library/dropzone/dropzone.js',
        'resources/assets/js/library/moment.min.js',
        'resources/assets/js/library/fullcalendar.min.js',
        'resources/assets/js/library/jquery-knob/jquery.knob.min.js',

        'resources/assets/js/library/jquery-datatable/jquery.dataTables.js',
        'resources/assets/js/library/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js',
        'resources/assets/js/library/jquery-datatable/extensions/export/dataTables.buttons.min.js',
        'resources/assets/js/library/jquery-datatable/extensions/export/buttons.flash.min.js',
        'resources/assets/js/library/jquery-datatable/extensions/export/jszip.min.js',
        'resources/assets/js/library/jquery-datatable/extensions/export/pdfmake.min.js',
        'resources/assets/js/library/jquery-datatable/extensions/export/vfs_fonts.js',
        'resources/assets/js/library/jquery-datatable/extensions/export/buttons.html5.min.js',
        'resources/assets/js/library/jquery-datatable/extensions/export/buttons.print.min.js',
        'resources/assets/js/library/jquery-datatable/jquery-datatable.js',
        'resources/assets/js/library/lobibox.js',
        'resources/assets/js/library/xepOnline.jqPlugin.js'

    ], 'public/js/librarys.js')
    .combine([
        'resources/assets/js/frontend/main.js',
        'resources/assets/js/frontend/ajax.js',
        'resources/assets/js/frontend/quiz.js',
        'resources/assets/js/frontend/admin.js'


    ], 'public/js/frontend.js')
    .combine([
        'resources/assets/js/library/node-waves/waves.js',
        'resources/assets/js/library/jquery-slimscroll/jquery.slimscroll.js'
    ], 'public/js/lessonlibrarys.js')
    .combine(
        [
        'resources/assets/js/lessonviewer/lessonviewer.js',
        'resources/assets/js/lessonviewer/lessonbuilder.js'], 'public/js/lessonviewer.js'
    )
    .combine(
            [
            'resources/assets/js/lessonviewer/homeworkviwer.js',

            ], 'public/js/homeworkviwer.js'
        )
    .combine(
        [
            'resources/assets/js/lessonviewer/quizviwer.js',

        ], 'public/js/quizviwer.js'
    );