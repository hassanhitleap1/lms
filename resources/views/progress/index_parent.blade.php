@extends('layout.app')
@section('title', 'Progress')
@section('content')
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
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
            chart.draw(data, options);
        }
        function drawVisualization1() {
            // Some raw data (not necessarily accurate)
            var x='{{$arrProgressJsonDitales}}';
            x=x.replace(/&quot;/g,'"');
            var progress = JSON.parse(x);
            var arr=([progress['title']].concat(progress['lessons']));
            console.log(arr) ;
            var data = google.visualization.arrayToDataTable(arr);
            var options = {
                title : '@lang('lang.StudentChartdetails')',
                vAxis: {title: '@lang('lang.percentage')'},
                hAxis: {title: '@lang('lang.Lessons')'},
                seriesType: 'bars',
                series: {10: {type: 'line'}},
                isStacked:'number'

            };
            var chart = new google.visualization.ComboChart(document.getElementById('chart_div1'));
            chart.draw(data, options);
        }

        function drawVisualizationclass() {
            // Some raw data (not necessarily accurate)
            var x='{{$arrProgressJsonStudent}}';

            x=x.replace(/&quot;/g,'"');
            var progress = JSON.parse(x);
            var title=['@lang('lang.Student_name')', '@lang('lang.Result')', '@lang('lang.Progress')','@lang('lang.Awards')'];
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
        function drawVisualizationall() {
            var x='{{$arrProgressJsonAll}}';
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
            chart.draw(data, options);
        }

        $(document).ready(function () {
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawVisualization);
            google.charts.setOnLoadCallback(drawVisualization1);
            google.charts.setOnLoadCallback(drawVisualizationclass);
            google.charts.setOnLoadCallback(drawVisualizationall);

        });
    </script>
    <div class="row clearfix progress-main-index">
        <div class="row clearfix">
            <!-- Line Chart -->
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card m-b-30">
                    <div class="header">
                        <a href="{{url('/')."/".Lang::getLocale()}}/progress/parent?student={{$user->userid}}"><h2>@lang('lang.StudentChartparents')</h2></a>
                    </div>
                    <div class="body p-t-0 p-b-0">
                        <div id="chart_div" style="width: 100%; height: 100%;min-height: 350px;"></div>
                    </div>
                </div>
            </div>
            <!-- #END# Line Chart -->
            <!-- Bar Chart -->
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card m-b-30">
                    <div class="header">
                        <a href="{{url('/')."/".Lang::getLocale()}}/progress/parentdetails?student={{$user->userid}}"><h2>@lang('lang.StudentChartdetails')</h2></a>
                    </div>
                    <div class="body p-t-0 p-b-0">
                        <div id="chart_div1" style="width: 100%; height: 100%;min-height: 350px;"></div>
                    </div>
                </div>
            </div>
            <!-- #END# Bar Chart -->
        </div>
        <div class="row clearfix">
            <!-- Line Chart -->
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card m-b-30">
                    <div class="header">
                        <a href="{{url('/')."/".Lang::getLocale()}}/progress/class"><h2>@lang('lang.StudentChartclasses')</h2></a>
                    </div>
                    <div class="body p-t-0 p-b-0">
                        <div id="chart_div_class" style="width: 100%; height: 100%;min-height: 350px;"></div>
                    </div>
                </div>
            </div>
            <!-- #END# Line Chart -->
            <!-- Bar Chart -->
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card m-b-30">
                    <div class="header">
                        <a href="{{url('/')."/".Lang::getLocale()}}/progress/all"><h2>@lang('lang.StudentChartCategories')</h2></a>
                    </div>
                    <div class="body p-t-0 p-b-0">
                        <div id="chart_div_all" style="width: 100%; height: 100%;min-height: 350px;"></div>
                    </div>
                </div>
            </div>
            <!-- #END# Bar Chart -->
        </div>
    </div>
@endsection
