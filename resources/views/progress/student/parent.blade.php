@extends('layout.app')
@section('title', __('lang.Parent'))
@section('content')
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body parent">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 col-lg-4 col-xs-12 float-left">
                                    <div class="form-horizontal" >
                                        <div class="row clearfix">
                                            <div class="col-lg-3 col-md-3 col-sm-2 col-xs-12 form-control-label">
                                                <label class="float-left">{{\Illuminate\Support\Facades\Auth::user()->fullname}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-sm-12 col-md-2 col-lg-2 col-xs-12 float-right ">
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
                                                <a class="dt-button waves-effect waves-block hide-last-col print-a" tabindex="0" aria-controls="DataTables_Table_0" href="#"><span><i class="material-icons">local_printshop</i> @lang('lang.Print')</span></a>
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
                        <div class="table-responsive">
                            <table class="table-student-chart table table-bordered table-striped table-hover js-basic-example dataTable js-exportable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('lang.Category')</th>
                                    <th>@lang('lang.Result')</th>
                                    <th>@lang('lang.Progress')</th>
                                    <th>@lang('lang.Awards')</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = $categories->currentpage() * $categories->perpage() - $categories->perpage();
                                ?>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td><a href="{{url('/')."/".Lang::getLocale()}}/progress/parentdetails/?student={{$user->userid}}&category={{$category->category_id}}">{{$category['title_'.Lang::getLocale()]}} </a></td>
                                        <td>
                                            <a class="c100 p{{ intval($category['result'])}} small" href="{{url('/')."/".Lang::getLocale()}}/progress/parentdetails/?student={{$user->userid}}&category={{$category->category_id}}">
                                                <span>{{ intval($category['result'])}}%</span>
                                                <div class="slice">
                                                    <div class="bar"></div>
                                                    <div class="fill"></div>
                                                </div>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{url('/')."/".Lang::getLocale()}}/progress/parentdetails/?student={{$user->userid}}&category={{$category->category_id}}" class="progress">
                                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="{{  intval($category['percent'])}}%" aria-valuemin="0" aria-valuemax="100" style="width:{{ intval($category['percent'])}}%">
                                                    {{ intval($category['percent'])}}%
                                                </div>
                                            </a>
                                        </td>
                                        <td><a  id="popup_awards" class="btn-awards">{{$user->badges($category->category_id)->count()}}</a></td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="row table-student-chart">
                            <div class="col-sm-5">
                                <div class="dataTables_info" >
                                    <span class="pull-left">@lang('lang.Showing') </span><span class="pull-left">{{($categories->currentpage()-1)*$categories->perpage()+1}} </span> <span class="pull-left">@lang('lang.to') </span>
                                    <span class="pull-left">{{(($categories->currentpage()-1)*$categories->perpage())+$categories->count()}} </span><span class="pull-left">@lang('lang.of')</span><span class="pull-left">{{$categories->total()}}</span><span class="pull-left">@lang('lang.Parent')</span>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                    {{$categories->links()}}
                                </div>
                            </div>
                        </div>
                        <div id="chart_div" style="width: 100%; height: 100%;min-height: 450px;display: none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var click="return xepOnline.Formatter.Format('chart_div', {render:'download', srctype:'svg'})";
            jQuery('#chart_div-header').append('<a class="dt-button buttons-pdf buttons-html5 waves-effect waves-block hide-last-col" onclick="'+ click +'"><span><i class="material-icons">picture_as_pdf</i>@lang('lang.pdf') </span></a>');

        });
        function AddNamespace(){
            var svg = jQuery('#chart_div svg');
            svg.attr("xmlns", "http://www.w3.org/2000/svg");
            svg.css('overflow','visible');
        }
        function drawVisualization() {
            var x='{{$arrProgressJson}}';

            x=x.replace(/&quot;/g,'"');
            var progress = JSON.parse(x);
            var arr=([progress['title']].concat(progress['categories']));
            // Some raw data (not necessarily accurate)
            var data = google.visualization.arrayToDataTable(arr);
            var options = {
                title : '@lang('lang.StudentChartparents')',
                vAxis: {title: '@lang('lang.percentage')'},
                hAxis: {title: '@lang('lang.Categories')'},
                seriesType: 'bars',
                series: {10: {type: 'line'}},
                isStacked:'number'

            };
            var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
            google.visualization.events.addListener(chart, 'ready', AddNamespace);
            chart.draw(data, options);
        }
    </script>
@endsection
