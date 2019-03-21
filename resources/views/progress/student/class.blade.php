@extends('layout.app')
@section('title', __('lang.Class'))
@section('content')
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body classes">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <form  action="{{URL::to('/').'/'.Lang::getLocale().'/progress/class'}}"  method="GET">
                                <div class="col-sm-12 col-md-3 col-lg-3 col-xs-12 float-left">
                                    <div class="form-horizontal" >
                                        <div class="row clearfix">
                                            <div class="col-lg-3 col-md-3 col-sm-2 col-xs-12 form-control-label">
                                                <label class="float-left">@lang('lang.Category')</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-10 col-xs-12">
                                                <div class="form-group" style="margin: 0">
                                                    <div class="form-line float-left">
                                                        <select class="form-control show-tick level" name="category" id="category">
                                                            @foreach($categories as $category)
                                                                @if(isset($_GET['category']))

                                                                    @if($category->category_id==$_GET['category'])
                                                                        <option value="{{$category->category_id}}" selected>{{$category["title_".Lang::getLocale()] }}</option>
                                                                    @else
                                                                        <option  value="{{$category->category_id}}" >{{$category["title_".Lang::getLocale()] }}</option>
                                                                    @endif
                                                                @else
                                                                    <option  value="{{$category->category_id}}" >{{$category["title_".Lang::getLocale()] }}</option>
                                                                @endif

                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="col-sm-12 col-md-3 col-lg-3 col-xs-12 float-right">
                                <ul class="header-dropdown float-right ul-table-print">
                                    <li class="dropdown">
                                        <a class="dropdown-toggle" style="cursor: pointer">
                                            <i class="material-icons fullscreen-icon">fullscreen</i>
                                            <i style="display: none" class="material-icons fullscreen-exit-icon">fullscreen_exit</i>
                                        </a>
                                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                                            <i class="material-icons">local_printshop</i>
                                        </a>
                                        <ul class="dropdown-menu pull-right"></ul>
                                    </li>
                                </ul>
                                <ul class="header-dropdown float-right ul-charts-print" style="display:none">
                                    <li class="dropdown">
                                        <a class="dropdown-toggle" style="cursor: pointer">
                                            <i class="material-icons fullscreen-icon">fullscreen</i>
                                            <i style="display: none" class="material-icons fullscreen-exit-icon">fullscreen_exit</i>
                                        </a>
                                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                                            <i class="material-icons">local_printshop</i>
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                            <li class="dt-buttons" id="chart_div-header">
                                                <a class="dt-button waves-effect waves-block hide-last-col print-a" tabindex="0" aria-controls="DataTables_Table_0" href="#"><span><i class="material-icons">local_printshop</i> Print</span></a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row change-between-tablechart">
                            <a class="btn btn-primary waves-effect float-right active Table"><i class="material-icons" title="@lang('lang.Table')">view_list</i></a>
                            <a class="btn btn-primary waves-effect float-right Chart"><i class="material-icons" title="@lang('lang.Chart')">insert_chart</i></a>
                        </div>
                        <div class="table-responsive table-parent-chart">
                            <table class="table-student-chart table table-bordered table-striped table-hover js-basic-example dataTable js-exportable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('lang.Student_name')</th>
                                    <th>@lang('lang.Result')</th>
                                    <th>@lang('lang.Progress')</th>
                                    <th>@lang('lang.Awards')</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = $students->currentpage() * $students->perpage() - $students->perpage();
                                ?>
                                @foreach($students as $student)

                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$student->fullname}} </td>
                                        <td>
                                            <a class="c100 p{{intval($student['result'])}} small" href="{{url('/')."/".Lang::getLocale()}}/progress/parentdetails/?student={{$student->userid}}&category={{$categoryModel->category_id}}">
                                                <span>{{$student['result']}}%</span>
                                                <div class="slice">
                                                    <div class="bar"></div>
                                                    <div class="fill"></div>
                                                </div>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{url('/')."/".Lang::getLocale()}}/progress/parentdetails/?student={{$student->userid}}&category={{$categoryModel->category_id}}" class="progress">
                                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="{{$student['percent']}}%" aria-valuemin="0" aria-valuemax="100" style="width: {{$student['percent']}}%;">
                                                    {{$student['percent']}} %
                                                </div>
                                            </a>
                                        </td>
                                        <td><a onclick="showpopup();" class="btn-awards">{{$student->badges($categoryModel->category_id)->count()}}</a></td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="row table-parent-chart">
                            <div class="col-sm-5">
                                <div class="dataTables_info" >
                                    <span class="pull-left">@lang('lang.Showing') </span><span class="pull-left">{{($students->currentpage()-1)*$students->perpage()+1}} </span> <span class="pull-left">@lang('lang.to') </span>
                                    <span class="pull-left">{{(($students->currentpage()-1)*$students->perpage())+$students->count()}} </span><span class="pull-left">@lang('lang.of')</span><span class="pull-left">{{$students->total()}}</span><span class="pull-left">@lang('lang.Students')</span>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                    {{$students->links()}}
                                </div>
                            </div>
                        </div>
                        <div id="chart_div_class" style="width: 100%; height: 100%;min-height: 450px;display:none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $("#category").change(function(){
                $(this).closest("form").submit();
            });
            $("#level").change(function(){
                $(this).closest("form").submit();
            });
            $("#class").change(function(){
                $(this).closest("form").submit();
            });
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawVisualizationclass);
        });


        function drawVisualizationclass() {
            // Some raw data (not necessarily accurate)
            var x='{{$arrProgressJson}}';

            x=x.replace(/&quot;/g,'"');
            var progress = JSON.parse(x);
            var title=['Student Name', 'Result', 'Progress','Awards'];
            var arr=([title].concat(progress['users']));
            var data = google.visualization.arrayToDataTable(arr);
            var options = {
                title : '@lang('lang.StudentChartclasses')',
                vAxis: {title: '@lang('lang.percentage')'},
                hAxis: {title: '@lang('lang.Student_name')'},
                seriesType: 'bars',
                series: {10: {type: 'line'}},
                isStacked:'number'

            };
            var chart = new google.visualization.ComboChart(document.getElementById('chart_div_class'));
            chart.draw(data, options);
        }
    </script>
@endsection
