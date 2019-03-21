@extends('layout.app')
@section('title', __('lang.Progress'))
@section('content')
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        function AddNamespace(){
            var svg = jQuery('#chart_div svg');
            svg.attr("xmlns", "http://www.w3.org/2000/svg");
            svg.css('overflow','visible');
        }
        function AddNamespace1() {
            var svg = jQuery('#chart_div1 svg');
            svg.attr("xmlns", "http://www.w3.org/2000/svg");
            svg.css('overflow', 'visible');
        }
        function AddNamespace2() {
            var svg = jQuery('#chart_div_class svg');
            svg.attr("xmlns", "http://www.w3.org/2000/svg");
            svg.css('overflow', 'visible');
        }
        function AddNamespace3() {
            var svg = jQuery('#chart_div_all svg');
            svg.attr("xmlns", "http://www.w3.org/2000/svg");
            svg.css('overflow', 'visible');
        }
        function drawVisualization() {
            var x='{{$arrProgressJson}}';

            x=x.replace(/&quot;/g,'"');
            var progress = JSON.parse(x);
            var def= progress['title'].length-progress['categories'][0].length ;

            if(progress['categories'][0].length != progress['title'].length ){

                for(var i=0; i < progress['categories'].length;i++){
                    for(j=0;j<def;j++){
                        progress['categories'][i].push(0);
                    }
                }
            }
            var arr=([progress['title']].concat(progress['categories']));
            console.log(arr);
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

        function drawVisualization1() {
            // Some raw data (not necessarily accurate)
            var x='{{$arrProgressJsonDitales}}';
            x=x.replace(/&quot;/g,'"');
            var progress = JSON.parse(x);
            var arr=([progress['title']].concat(progress['lessons']));

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
            google.visualization.events.addListener(chart, 'ready', AddNamespace1);
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
            google.visualization.events.addListener(chart, 'ready', AddNamespace2);
            chart.draw(data, options);
        }
        function drawVisualizationall() {
            var x='{{$arrProgressJsonAll}}';
            x=x.replace(/&quot;/g,'"');
            var progress = JSON.parse(x);
            var arr=([progress['title']].concat(progress['students']));
            // Some raw data (not necessarily accurate)
            var data = google.visualization.arrayToDataTable(arr);
            console.log(arr);
            var options = {
                title : '@lang('lang.StudentChartCategories')',
                vAxis: {title: '@lang('lang.percentage')'},
                hAxis: {title: '@lang('lang.Student_name')'},
                seriesType: 'bars',
                series: {10: {type: 'line'}},
                isStacked:'number'
            };
            var chart = new google.visualization.ComboChart(document.getElementById('chart_div_all'));
            google.visualization.events.addListener(chart, 'ready', AddNamespace3);
            chart.draw(data, options);
        }

        $(document).ready(function () {
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawVisualization);
            google.charts.setOnLoadCallback(drawVisualization1);
            google.charts.setOnLoadCallback(drawVisualizationclass);
            google.charts.setOnLoadCallback(drawVisualizationall);

            var click="return xepOnline.Formatter.Format('JSFiddle', {render:'download', srctype:'svg'})";
            jQuery('#chart_div-header').append('<a class="dt-button buttons-pdf buttons-html5 waves-effect waves-block hide-last-col" onclick="'+ click +'"><span><i class="material-icons">picture_as_pdf</i> pdf</span></a>');

            var click="return xepOnline.Formatter.Format('JSFiddle1', {render:'download', srctype:'svg'})";
            jQuery('#chart_div1-header').append('<a class="dt-button buttons-pdf buttons-html5 waves-effect waves-block hide-last-col" onclick="'+ click +'"><span><i class="material-icons">picture_as_pdf</i> pdf</span></a>');

            var click="return xepOnline.Formatter.Format('JSFiddle2', {render:'download', srctype:'svg'})";
            jQuery('#chart_div_class-header').append('<a class="dt-button buttons-pdf buttons-html5 waves-effect waves-block hide-last-col" onclick="'+ click +'"><span><i class="material-icons">picture_as_pdf</i> pdf</span></a>');

            var click="return xepOnline.Formatter.Format('JSFiddle3', {render:'download', srctype:'svg'})";
            jQuery('#chart_div_all-header').append('<a class="dt-button buttons-pdf buttons-html5 waves-effect waves-block hide-last-col" onclick="'+ click +'"><span><i class="material-icons">picture_as_pdf</i> pdf</span></a>');
        })
    </script>

    <div class="clearfix progress-main-index">
        <div class="row clearfix">
            <!-- Line Chart -->
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card m-b-30">
                    <div class="header" >
                        <a href="{{url('/')."/".Lang::getLocale()}}/progress/parent?student={{$user->userid}}" ><h2>@lang('lang.StudentChartparents')</h2></a>

                        <ul class="header-dropdown float-right">
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                                    <i class="material-icons">local_printshop</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li class="dt-buttons"  id="chart_div-header">
                                        <a class="dt-button waves-effect waves-block hide-last-col print-a" tabindex="0" aria-controls="DataTables_Table_0" href="#"><span><i class="material-icons">local_printshop</i>@lang('lang.Print')</span></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>


                    </div>
                    <div class="body p-t-0 p-b-0" id="JSFiddle">
                        <div id="chart_div" style="width: 100%; height: 100%;min-height: 350px;">

                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Line Chart -->
            <!-- Bar Chart -->
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card m-b-30">
                    <div class="header">
                        <a href="{{url('/')."/".Lang::getLocale()}}/progress/parentdetails/?student={{$user->userid}}"><h2>@lang('lang.StudentChartdetails')</h2></a>

                        <ul class="header-dropdown float-right">
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                                    <i class="material-icons">local_printshop</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li class="dt-buttons"  id="chart_div1-header">
                                        <a class="dt-button waves-effect waves-block hide-last-col print-b" tabindex="0" aria-controls="DataTables_Table_0" href="#"><span><i class="material-icons">local_printshop</i> @lang('lang.Print')</span></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body p-t-0 p-b-0" id="JSFiddle1">
                        <div id="chart_div1"  style="width: 100%; height: 100%;min-height: 350px;"></div>
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

                    <ul class="header-dropdown float-right">
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                                <i class="material-icons">local_printshop</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li class="dt-buttons"  id="chart_div_class-header">
                                    <a class="dt-button waves-effect waves-block hide-last-col print-c" tabindex="0" aria-controls="DataTables_Table_0" href="#"><span><i class="material-icons">local_printshop</i>  @lang('lang.Print')</span></a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body p-t-0 p-b-0" id="JSFiddle2">
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
                    <ul class="header-dropdown float-right">
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                                <i class="material-icons">local_printshop</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li class="dt-buttons"  id="chart_div_all-header">
                                    <a class="dt-button waves-effect waves-block hide-last-col print-d" tabindex="0" aria-controls="DataTables_Table_0" href="#"><span><i class="material-icons">local_printshop</i> @lang('lang.Print')</span></a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body p-t-0 p-b-0" id="JSFiddle3">
                    <div id="chart_div_all" style="width: 100%; height: 100%;min-height: 350px;"></div>
                </div>
            </div>
        </div>
        <!-- #END# Bar Chart -->
    </div>
    </div>
@endsection
