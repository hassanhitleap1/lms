<?php
/**
 * Created by Dar Al-Manhal - Hussam Abu Khadijeh.
 * User: Hussam Abu Khadijeh
 * Date: 12/09/2018
 * Time: 12:46 م
 */
?>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body table-responsive">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <form method="GET">
                                <div class="col-md-4 col-sm-12 float-left">
                                    <div class="form-horizontal">
                                        <div class="row clearfix">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 form-control-label">
                                                <label class="float-left">@lang('lang.Level')</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                <div class="form-group">
                                                    <div class="form-line float-left">
                                                        <select name="curricula_level" id="curricula_level" class="form-control show-tick jq_change_submit">
                                                            <option value="-1">------</option>
                                                            <?php
                                                            foreach($levels as $level){
                                                            ?>
                                                            <option value="<?=$level["level_id"];?>" <?php if(isset($_GET["curricula_level"]) && $_GET["curricula_level"]==$level["level_id"]){echo 'selected="selected"';}?> ><?=$level["ltitle_".Lang::getLocale()];?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12 float-left">
                                    <div class="form-horizontal">
                                        <div class="row clearfix">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 form-control-label">
                                                <label class="float-left">@lang('lang.Category')</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                <div class="form-group">
                                                    <div class="form-line float-left">
                                                        <select name="curricula_cat" id="curricula_cat" class="form-control show-tick jq_change_submit">
                                                            <option value="-1">------</option>
                                                            <?php
                                                            foreach($categories as $category){
                                                            ?>
                                                            <option value="<?=$category["category_id"];?>" <?php if(isset($_GET["curricula_cat"]) && $_GET["curricula_cat"]==$category["category_id"]){echo 'selected="selected"';}?> ><?=$category["title_".Lang::getLocale()];?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="col-md-4 col-sm-12 float-right">
                                <a id="addCurricula" class="btn btn-primary waves-effect float-right btn-addCurriculums" >@lang('lang.Add_Curriculums')</a>
                            </div>

                        </div>
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th class="sorting_asc">@lang('lang.Title')</th>
                                <th>@lang('lang.Description')</th>
                                <th>@lang('lang.Category')</th>
                                <th>@lang('lang.Level')</th>
                                <th>@lang('lang.Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach($curricula as $curriculum){
                            $i=$curricula->currentpage()*$curricula->perpage()-$curricula->perpage();
                            ?>
                            <tr>
                                <td><?=$i;?></td>
                                <td><?=$curriculum->{"cu_title_".Lang::getLocale()};?></td>
                                <td><?=$curriculum->{"cu_description_".Lang::getLocale()};?></td>
                                <td><?=$curriculum->{"title_".Lang::getLocale()};?></td>
                                <td><?=$curriculum->{"ltitle_".Lang::getLocale()};?></td>
                                <td class="action">
                                    <a onclick="showpopup();" class="btn-editCurriculums" title="@lang('lang.Edit')"><i class="material-icons">edit</i></a>
                                    <a title="@lang('lang.Delete')"><i class="material-icons">delete</i></a>
                                    <a  href="{{url('/')."/".Lang::getLocale()}}/lessons" class="btn-browseLessons" title="@lang('lang.Browse_Lessons')"><i class="flaticon-business-people-meeting fi"></i></a>
                                    <a class="btn-choosebook" onclick="showpopup();" title="@lang('lang.Choose_book')"><i class="material-icons">book</i></a>
                                    <a class="btn-syllabus" href="{{url('/')."/".Lang::getLocale()}}/curriculums/syllabus" title="@lang('lang.Syllabus')"><i class="material-icons">assignment</i></a>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>

                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info">
                                    <span class="pull-left">@lang('lang.Showing') </span><span class="pull-left">{{($curricula->currentpage()-1)*$curricula->perpage()+1}} </span> <span class="pull-left">@lang('lang.to') </span>
                                    <span class="pull-left">{{(($curricula->currentpage()-1)*$curricula->perpage())+$curricula->count()}} </span><span class="pull-left">@lang('lang.of')</span><span class="pull-left">{{$curricula->total()}}</span><span class="pull-left">@lang('lang.Curriculums')</span>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                    {{$curricula->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
