<textarea id="ckeditor"> </textarea>
<a class="btn btn-primary waves-effect btn-addbook float-right" onclick="hidepopup();">@lang('lang.Save')</a>
<script>
    $(document).ready(function () {
        //start ckeditor
        CKEDITOR.replace('ckeditor');
        CKEDITOR.config.height = 300;
        //end ckeditor
    });
</script>