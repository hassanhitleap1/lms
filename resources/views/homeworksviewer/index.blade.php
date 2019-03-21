@include('homeworksviewer.header')
<section>
    @include('homeworksviewer.leftsidebar')
</section>
@if(!$canview)
    <script>
        showTimeFinshedHomework();
    </script>
@endif
<div class="row clearfix">
    <div class="button-wrap">
        <div class="button">
            <a class="arrow-next"></a>
        </div>
        <div class="tool-tip">@lang('lang.Next')</div>
    </div>
    <iframe class="lesson-viewer-iframe" src=""></iframe>
    <a class="arrow-prev pause"></a>
</div>
@include('homeworksviewer.footer')

