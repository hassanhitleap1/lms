@section('title', 'Add')
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-l-0 p-r-0">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tab-nav-right" role="tablist">
        <li role="presentation" class="active" id="pres_1"><a href="#home_animation_2" data-toggle="tab"  >@lang('lang.Child')</a></li>
        <li role="presentation" id="pres_2"><a href="#profile_animation_2" data-toggle="tab">@lang('lang.Add_Child')</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content" parent_id="{{$parent->userid}}">
        <div role="tabpanel" class="tab-pane animated fadeInRight active" id="home_animation_2">
            <div class="dataTables_wrapper form-inline dt-bootstrap">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th >@lang('lang.Name')</th>
                        <th >@lang('lang.Email')</th>
                        <th>@lang('lang.Level')</th>
                        <th>@lang('lang.Class')</th>
                        <th>@lang('lang.Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1;?>
                    @foreach($childs as $student)
                        @if(! is_null($student))
                        <tr id="tr{{$student->userid}}">
                            <td>{{$i++}}</td>
                            <td>{{$student->fullname}}</td>
                            <td>{{$student->email}}</td>
                            <td>{{$student->userLevel['ltitle_'.Lang::getLocale()]}}</td>
                            <td>{{$student->userClass['ctitle_'.Lang::getLocale()]}}</td>
                            <td class="action">
                                <a class="delete-child-user"  data-id="{{$student->userid}}" ><i class="material-icons" title="@lang('lang.Delete')">delete</i></a>
                            </td>
                        @endif
                    @endforeach
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-sm-7">
                        <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                            <?php
//                            $pagination=str_replace("page-item","page-item-left",(string) $childs->links());
//                            $pagination=str_replace("href","url",$pagination);
                            ?>

                            <?php
                            //print $pagination
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane animated fadeInRight without-scroll" id="profile_animation_2">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body table-responsive">
                            <div class="dataTables_wrapper form-inline dt-bootstrap">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 float-left">
                                        <form class="form-horizontal">
                                            <div class="row clearfix">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 form-control-label">
                                                    <label class="float-left">@lang('lang.Level')</label>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="form-line float-left">
                                                            <select class="form-control show-tick jq_formdata level" name="level"id="levels" >
                                                                <option  value="-1" >----</option>
                                                                <?php $modelClasses=null; ?>
                                                                @foreach($levels as $level)
                                                                    @if(isset($_GET['level']))

                                                                        @if($level->level_id==$_GET['level'])
                                                                            <option data-id="{{$level->level_id}}" value="{{$level->level_id}}" selected>{{$level["ltitle_".Lang::getLocale()] }}</option>

                                                                            <?php $modelClasses= (!$_GET['level']==-1)?$level->classesInfo:null ;?>
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
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 float-left">
                                        <form class="form-horizontal">
                                            <div class="row clearfix">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 form-control-label">
                                                    <label class="float-left">@lang('lang.Class')</label>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                    <div class="form-group">
                                                        <div class="form-line float-left">
                                                            <select class="form-control show-tick jq_formdata get-student-chiled" name="class" id="class" >
                                                                <option value="-1">-----</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th >@lang('lang.Name')</th>
                                        <th >@lang('lang.Email')</th>
                                        <th>@lang('lang.Level')</th>
                                        <th>@lang('lang.Class')</th>
                                        <th>@lang('lang.Action')</th>
                                    </tr>
                                    </thead>
                                    <tbody  id="list-view-student">

                                    </tbody>
                                </table>
                                <div class="col-sm-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                        <div  id="ul-pagination">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>


    $(document).ready(function () {

        $('#pres_1').click(function () {
            $(".page-loader-wrapper").show();
            parent_id=$('.tab-content').attr('parent_id');
            Url=SITE_URL+Language+'/parents/childs/'+parent_id;
            $.ajax({
                url: Url,
                type: 'GET',

            }).done(function(data) {
                $(".page-loader-wrapper").hide();
                $('#popup_content').html('');
                $('#popup_content').append(data);
            }).fail(function(data) {
                console.log( data );
            });
        });

        $('#pres_2').click(function () {
            $(".page-loader-wrapper").show();
            idClass=$(this).val();
            idlevel=$('#level').val();
            parent_id=$('.tab-content').attr('parent_id');
            Url=SITE_URL+Language+'/parents/getStudent/';
            $.ajax({
                url: Url,
                type: 'GET',
                data:{idClass:idClass,idlevel:idlevel,parent_id:parent_id}
            }).done(function(data) {
                $(".page-loader-wrapper").hide();
                appendListViewStudent( data );


            }).fail(function(data) {
                console.log( data );
            });
        });


        $('.get-student-chiled').change(function () {
            $(".page-loader-wrapper").show();
            idClass=$(this).val();
            idlevel=$('#level').val();
            parent_id=$('.tab-content').attr('parent_id');
            Url=SITE_URL+Language+'/parents/getStudent/';
            $.ajax({
                url: Url,
                type: 'GET',
                data:{idClass:idClass,idlevel:idlevel,parent_id:parent_id},
            }).done(function(data) {
                $(".page-loader-wrapper").hide();
                appendListViewStudent( data );
            }).fail(function(data) {
                console.log( data );
            });

        });

    });


    function getStudentChild(){
        $(".page-loader-wrapper").show();
        idClass=$(this).val();
        idlevel=$('#level').val();
        parent_id=$('.tab-content').attr('parent_id');
        Url=SITE_URL+Language+'/parents/getStudent/';
        $.ajax({
            url: Url,
            type: 'GET',
            data:{idClass:idClass,idlevel:idlevel,parent_id:parent_id}
        }).done(function(data) {
            $(".page-loader-wrapper").hide();
            appendListViewStudent( data );


        }).fail(function(data) {
            console.log( data );
        });
    }

    function appendListViewStudent(data){
        $('#list-view-student').html('');
        console.log(data.users);
        i=1;
        var ctitle='';
        var ltitle='';
        $.each( data.users.data, function( key, value ) {
            ctitle=(value.user_class==null)?'':value.user_class['ctitle_'+Language];
            ltitle=(value.user_level==null)?'':value.user_level['ltitle_'+Language];

            x="<tr id="+"tr_"+value.userid+">"+
                    "<td>"+i+++"</td>" +
                    "<td>"+value.fullname+"</td>" +
                    "<td>"+value.email+"</td>" +
                   "<td>"+ltitle+"</td>" +
                    "<td>"+ctitle+"</td>" +
                    "<td class='action'>" +
                        "<a  class='add-child' data-id="+value.userid+" >"+
                            "<i  class='material-icons' title="+'add'+">add</i>" +
                        "</a>" +
                    "</td>" +
                "</tr>"+
            "</td>";

            $('#list-view-student').append(x);

        });

        $('#ul-pagination').html('');
        $('#ul-pagination').append(data.pagination);


    }

    $(document).on('click', '.page-link-ajax-child', function() {
        event.preventDefault();
        var url=$(this).attr('url');
        $(".page-loader-wrapper").show();
        idClass=$(this).val();
        idlevel=$('#level').val();
        parent_id=$('.tab-content').attr('parent_id');
        $.ajax({
            url: url,
            type: 'GET',
            data:{idClass:idClass,idlevel:idlevel,parent_id:parent_id}
        }).done(function(data) {
            $(".page-loader-wrapper").hide();
            appendListViewStudent( data );


        }).fail(function(data) {
            console.log( data );
        });
    });

    $(document).on('click', '.add-child',  function (e) {
        e.preventDefault();
        var parent_id=$('.tab-content').attr('parent_id');
        var student_id=$(this).attr('data-id');
        var url=SITE_URL+Language+'/parents/'+parent_id+'/add-child/'+student_id;
        showLoader();
        $.ajax({
            url: url,
            type: 'GET',
        }).done(function(data) {
            hideLoader();
            $('#tr_'+student_id).remove();

        }).fail(function(data) {
            console.log( data );
        });
    });

    $(document).on('click', '.delete-child-user',  function (e) {
        e.preventDefault();
        var data={};
        var action=SITE_URL+Language+'/parents/delete-child';
        data["student_id"]=$(this).attr("data-id");
        data['parent_id']=$('.tab-content').attr('parent_id');
        swal({
            title: window.Lang.lang.AreUSure,
            text: window.Lang.lang.DoUWDelThisUser,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: window.Lang.lang.Yes,
            cancelButtonText: window.Lang.lang.No,
            closeOnConfirm: true
        }, function () {
            showLoader();
            $.ajax({
                url: action,
                type: 'GET',
                data: data,
                success: function (JSON) {
                    hideLoader();
                    $('#tr'+data["student_id"]).html('');

                }
            });

        });

    });

</script>