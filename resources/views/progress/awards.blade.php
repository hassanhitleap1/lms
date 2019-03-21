<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 award-main-container">
        <div class="card">
            <div class="body ">
                <div class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-lg-4 col-xs-12 float-left">
                            <form class="form-horizontal">
                                <div class="row clearfix">
                                    <div class="col-lg-4 col-md-4 col-sm-2 col-xs-12 form-control-label">
                                        <label class="float-left">@lang('lang.Student_name')</label>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-10 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line float-left">
                                                <select class="form-control show-tick">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4 col-xs-12 float-left">
                            <form class="form-horizontal">
                                <div class="row clearfix">
                                    <div class="col-lg-4 col-md-4 col-sm-2 col-xs-12 form-control-label">
                                        <label class="float-left">@lang('lang.Level')</label>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-10 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line float-left">
                                                <select class="form-control show-tick level-classes" name="level" id="levelclasses"  >
                                                    <option  value="-1" >----</option>
                                                    <?php $modelClasses=null; ?>
                                                    @foreach($levels as $level)
                                                        @if(isset($_GET['level']))

                                                            @if($level->level_id==$_GET['level'])
                                                                <option data-id="{{$level->level_id}}" value="{{$level->level_id}}" selected>{{$level["ltitle_".Lang::getLocale()] }}</option>
                                                                <?php $modelClasses= $level->classesInfo ;?>
                                                            @else
                                                                <option data-id="{{$level->level_id}}" value="{{$level->level_id}}" >{{$level["ltitle_".Lang::getLocale()] }}</option>
                                                            @endif
                                                        @else
                                                            <option data-id="{{$level->level_id}}" value="{{$level->level_id}}" >{{$level["ltitle_".Lang::getLocale()] }}</option>
                                                        @endif

                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4 col-xs-12 float-left">
                            <form class="form-horizontal">
                                <div class="row clearfix">
                                    <div class="col-lg-4 col-md-4 col-sm-2 col-xs-12 form-control-label">
                                        <label class="float-left">@lang('lang.Class')</label>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-10 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line float-left">
                                                <select class="form-control show-tick" name="class" id="class"> </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('lang.Award_name')</th>
                            <th>@lang('lang.Description')</th>
                            <th>@lang('lang.Date')</th>
                            <th>@lang('lang.Lesson')</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>Award name</td>
                            <td>Description</td>
                            <td>1-8-2018</td>
                            <td>Lesson</td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function appendSelectOptions(data){
        $('#class').html('');
        $('#class').append('<option  value="-1" >----</option>')
        for(var i =0;i < data.class.length-1;i++)
        {
            $('#class').append('<option value="' + data.class[i].class_id + '">' +data.class[i]['ctitle_'+'{{Lang::getLocale()}}']+ '</option>');
        }
    }
    $(document).ready(function () {
        $(document).on('change', "#levelclasses", function () {
            $(".page-loader-wrapper").show();
            idlevel = $(this).val();
            Url = "{{asset(Lang::getLocale().'/students/get-classes-level/')}}/" + idlevel;
            $.ajax({
                url: Url,
                type: 'GET',
            }).done(function (data) {
                appendSelectOptions(data);
                $(".page-loader-wrapper").hide();

            }).fail(function () {
                alert("error");
            });
        });
    });
</script>