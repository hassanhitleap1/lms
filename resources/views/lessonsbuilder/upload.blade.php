<div>
    <small>Allowed files (Excel,word, pdf,power point, video(mp4), Sound(pdf)</small>
</div>
<form action="/" id="frmFileUpload" class="dropzone" method="post" enctype="multipart/form-data">
    <div class="dz-message">
        <div class="drag-icon-cph">
            <i class="material-icons">touch_app</i>
        </div>
        <h3>Drop files here or click to upload.</h3>
        <em>(This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.)</em>
    </div>
    <div class="fallback">
        <input name="file" type="file" multiple/>
    </div>
</form>
<a class="btn btn-primary waves-effect btn-addbook float-right" onclick="hidepopup();">@lang('lang.Upload')</a>
<script>
    $(document).ready(function () {
        Dropzone.options.frmFileUpload = {
            paramName: "file",
            maxFilesize: 2
        };
    });
</script>