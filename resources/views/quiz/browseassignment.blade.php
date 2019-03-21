@extends('layout.app')
@section('title', __('lang.Browse_Assignment'))
@section('content')
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body ">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <form id="search-form-browseassignment-quiz" action="{{URL::to('/').'/'.Lang::getLocale().'/quiz/browseassignment'}}"  method="GET">
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 m-b-10 float-left">
                                    <div class="form-horizontal">
                                        <div class="row clearfix">
                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                <div class="form-group">
                                                    <div class="form-line float-left">
                                                        <select class="form-control show-tick jq_formdata homework" name="quiz"id="quiz-search">
                                                            <option value="-1">-----</option>
                                                            @foreach($quizs as $quiz)
                                                                @if(isset($_GET['quiz']))
                                                                    @if($quiz->quiz_id==$_GET['quiz'])
                                                                        <option value="{{$quiz->quiz_id}}" selected>{{$quiz->title }}</option>
                                                                    @else
                                                                        <option  value="{{$quiz->quiz_id}}" >{{$quiz->title }}</option>
                                                                    @endif
                                                                @else
                                                                    <option value="{{$quiz->quiz_id}}" >{{$quiz->title }}</option>
                                                                @endif

                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 m-b-10 float-right">
                                    <ul class="header-dropdown float-right mobile-hide">
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
                                </div>

                            </form>
                        </div>
                        <div class="table-responsive">
                            <div class="dataTables_wrapper form-inline dt-bootstrap">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('lang.Quiz')</th>
                                        <th>@lang('lang.Type')</th>
                                        <th>@lang('lang.Name')</th>
                                        <th>@lang('lang.Level')</th>
                                        <th>@lang('lang.Send_date')</th>
                                        <th>@lang('lang.Action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($data)&&count($data)>0)
                                        @foreach($data as $items=>$item)
                                            <tr>
                                                <td>{{$items}}</td>
                                                <td>{{$item->name_quiz}}</td>
                                                <td>{{$item->assigntype}}</td>
                                                <td><?=$item->{"title_".Lang::getLocale()}?></td>
                                                <td><?=$item->{"ltitle_".Lang::getLocale()}?></td>
                                                <td>{{$item->senddate}}</td>

                                                <td class="action">
                                                    <a att_id_target="{{$item->id_target}}" att_idquiz="{{$item->id_quiz}}" att_startdate="{{$item->startdate}}" att_enddate="{{$item->enddate}}"  onclick="showpopup();" class="btn-showresultquiz" title="@lang('lang.Show_result')"><i class="material-icons">remove_red_eye</i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#quiz-search').change(function () {
                $(this).closest("form").submit();
            })
        })
    </script>
@endsection
