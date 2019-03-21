@include('lessonsbuilder.header')
<section>
    @include('lessonsbuilder.leftsidebar')
</section>
<div class="row clearfix">
    <a class="arrow-next"></a>
    <iframe class="lesson-viewer-iframe"
            src="" onload="onloadIframe(this)"></iframe>
    <a class="arrow-prev pause"></a>
</div>
@include('lessonsbuilder.footer')

