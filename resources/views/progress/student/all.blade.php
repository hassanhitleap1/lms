@extends('layout.app')
@section('title', __('lang.Category'))
@section('content')
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body allchart">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-sm-12 col-md-2 col-lg-2 col-xs-12 float-right">
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
                                            <li class="dt-buttons" id="chart_div-header1">
                                                <a class="dt-button waves-effect waves-block hide-last-col print-d" tabindex="0" aria-controls="DataTables_Table_0" href="#"><span><i class="material-icons">local_printshop</i> Print</span></a>
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
                                    @foreach($categories as $category)
                                        <th>{{$category['title_'.Lang::getLocale()]}}</th>
                                    @endforeach
                                    <th>@lang('lang.Average')</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = $students->currentpage() * $students->perpage() - $students->perpage();
                                ?>
                                @foreach($students as $student)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$student->fullname}}</td>
                                        @foreach($categories as $category)
                                            <?php $avgCatgory=App\Helper\UserHelper::calculateUesrMarkCategory($student->userid,$category->category_id)?>
                                            <td><?= intval($avgCatgory['result'])?></td>
                                        @endforeach

                                        <td>
                                            <a class="c100 p{{intval (\App\Helper\UserHelper::AvgAllCategories($student->userid))}} small">
                                                <span>{{intval (\App\Helper\UserHelper::AvgAllCategories($student->userid))}}%</span>
                                                <div class="slice">
                                                    <div class="bar"></div>
                                                    <div class="fill"></div>
                                                </div>
                                            </a>
                                        </td>
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
                        <div id="chart_div_all" style="width: 100%; height: 100%;min-height: 450px;display: none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $("#level,#class").change(function(){
                $(this).closest("form").submit();
            });
            var click="return xepOnline.Formatter.Format('chart_div_all', {render:'download', srctype:'svg'})";
            jQuery('#chart_div-header1').append('<a class="dt-button buttons-pdf buttons-html5 waves-effect waves-block hide-last-col" onclick="'+ click +'"><span><i class="material-icons">picture_as_pdf</i> pdf</span></a>');
        });
        function AddNamespace(){
            var svg = jQuery('#chart_div_all svg');
            svg.attr("xmlns", "http://www.w3.org/2000/svg");
            svg.css('overflow','visible');
        }
        function drawVisualizationall() {
            var x='{{$arrProgressJson}}';
            x=x.replace(/&quot;/g,'"');
            var progress = JSON.parse(x);
            var arr=([progress['title']].concat(progress['students']));
            // Some raw data (not necessarily accurate)
            var data = google.visualization.arrayToDataTable(arr);
            var options = {
                title : '@lang('lang.StudentChartCategories')',
                vAxis: {title: '@lang('lang.percentage')'},
                hAxis: {title: '@lang('lang.Student_name')'},
                seriesType: 'bars',
                series: {10: {type: 'line'}},
                isStacked:'number'
            };
            var chart = new google.visualization.ComboChart(document.getElementById('chart_div_all'));
            google.visualization.events.addListener(chart, 'ready', AddNamespace);

            chart.draw(data, options);
        }
    </script>
@endsection
