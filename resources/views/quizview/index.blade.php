@include('quizview.header')
<section>
    @include('quizview.leftsidebar')
</section>
@if(!$canview)
    <script>
        showTimeFinshedQuiz();
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
@include('quizview.footer')

