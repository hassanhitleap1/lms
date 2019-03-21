@section('title', 'Add')
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-l-0 p-r-0">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tab-nav-right" role="tablist">
        <li role="presentation" class="active" id="pres_1_group"><a href="#home_animation_2" data-toggle="tab" data-id="{{$group->group_id}}" id="assignStudent">@lang('lang.Students_in_group')</a></li>
        <li role="presentation" id="pres_2_group"><a href="#profile_animation_2" data-toggle="tab">@lang('lang.Add_Student_group')</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content" groupId="{{$group->group_id}}">
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
                    <?php
                    $i = $studentsAss->currentpage() * $studentsAss->perpage() - $studentsAss->perpage();
                    ?>
                    @foreach($studentsAss as $student)
                        <tr id="tr{{$student->userid}}">
                            <td>{{++$i}}</td>
                            <td>{{$student->fullname}}</td>
                            <td>{{$student->email}}</td>
                            <td><?=$student->{'ltitle_'.App::getLocale()}?></td>
                            <td><?=$student->{'ctitle_'.Lang::getLocale()}?></td>
                            <td class="action">
                                <a id="delete-assign-user"  data-id="{{$student->userid}}" data-action="{{asset(Lang::getLocale().'/groups/'.$group->group_id.'/delete-assign-user/'.$student->userid)}}"><i class="material-icons" title="@lang('lang.Delete')">delete</i></a>
                            </td>

                    @endforeach

                    </tbody>
                </table>
                <div class="row">
                    <div class="col-sm-7">
                        <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                            <?php 
                                $pagination=str_replace("page-item","page-item-left",(string) $studentsAss->links());
                                $pagination=str_replace("href","url",$pagination);    
                            ?>
                             
                            <?php print $pagination ?>
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
                                                            <select class="form-control show-tick level-classes" name="level" id="levelclasses"  >
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
                                                            <select class="form-control show-tick" name="class" id="class">

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
                                    <?php
                                    $i = $students->currentpage() * $students->perpage() - $students->perpage();
                                    ?>
                                    @foreach($students as $student)
                                        <tr id="tr{{$student->userid}}">
                                            <td>{{++$i}}</td>
                                            <td>{{$student->fullname}}</td>
                                            <td>{{$student->email}}</td>
                                            <td><?=$student->{'ltitle_'.Lang::getLocale()}?></td>
                                            <td><?=$student->{'ctitle_'.Lang::getLocale()}?></td>
                                            <td  class='action' >
                                                <a class='add-std-to-group' group_id="{{$group->group_id}}" data-id="{{$student->userid}}" title='{{Lang::get('lang.Add')}}'>
                                                    <i class='material-icons'>add</i>
                                                </a>
                                         </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                                <div class="col-sm-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                        <div  id="ul-pagination">
                                            <?php
                                            $paginationStudents=str_replace("page-item","page-item-left-std",(string) $students->links());
                                            $paginationStudents=str_replace("href","url",$paginationStudents);
                                            ?>

                                            <?php print $paginationStudents ?>
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
        $("#class").change(function(){
            $(".page-loader-wrapper").show();
            idClass=$(this).val();
            idlevel=$('#levelclasses').val();
            Url="{{asset(Lang::getLocale().'/groups/'.$group->group_id.'/get-users-class')}}/"+idlevel+'/'+idClass;
            $.ajax({
                url: Url,
                type: 'GET',
            }).done(function(data) {
               appendListViewStudent( data ,{{$group->group_id}});
                $(".page-loader-wrapper").hide();

            }).fail(function(data) {
                console.log( data );
            });
        });
        $('.page-item-left a').on('click', function(event) {
            event.preventDefault();
            $('#popup_content').load($(this).attr('url'));

        });
        $('.page-item-left-std a').on('click', function(event) {
            event.preventDefault();
           $('#popup_content').load($(this).attr('url'),function () {
               $('#home_animation_2').removeClass('active');
               $('#profile_animation_2').addClass('active');
               $('#pres_1_group').removeClass('active');
               $('#pres_2_group').addClass('active');
           });
        });
        $(document).on('click', '.page-link-ajax', function() {
            event.preventDefault();
            $(".page-loader-wrapper").show();
            var Url=$(this).attr("url");
            $.ajax({
            url: Url,
            type: 'GET',
            }).done(function(data) {
            appendListViewStudent( data ,{{$group->group_id}});
                $(".page-loader-wrapper").hide();

            }).fail(function(data) {
            console.log( data );
            });

        });
    });
</script>