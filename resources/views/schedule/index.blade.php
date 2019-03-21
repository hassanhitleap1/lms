@extends('layout.app')
@section('title',__('lang.School_schedule'))
@section('content')
    <script type="text/javascript">
        var url = "{{url(Lang::getLocale().'/schedule')}}";
        var _token = "{{ csrf_token() }}";
        var lang = "{{Lang::getLocale()}}";
    </script>
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            @if(!\App\Users::isStudent())
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 float-left m-b-10">
                                    <form id="FormClass" class="form-horizontal">
                                        <div class="row clearfix">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                                <label class="float-left">@lang('lang.Levels')</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                <div class="form-group" style="margin: 0">
                                                    <div class="form-line float-left">
                                                        {!! Form::hidden('class',$Classroom) !!}
                                                        {!! Form::hidden('Division',$Classroom) !!}
                                                        {!! Form::hidden('steacher',$steacher) !!}
                                                        <select id="ClassRoom" class="form-control show-tick">
                                                            <option value="-1">-------</option>
                                                            @if(count($allclass)>0)
                                                                @foreach($allclass as $key=>$level)
                                                                    <?php $select = '' ?>
                                                                    @if($level->level_id==$Classroom)
                                                                        <?php $select = 'selected' ?>
                                                                    @endif
                                                                    <option {{$select}} att="{{$level->level_id}}"
                                                                            value="{{$level->level_id}}"><?=$level->{'ltitle_'.App::getLocale()}?> </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 float-left m-b-10">
                                    <form class="form-horizontal">
                                        <div class="row clearfix">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                                <label class="float-left">@lang('lang.Class')</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                <div class="form-group" style="margin: 0">
                                                    <div class="form-line float-left">
                                                        <select id="Division" class="form-control show-tick">
                                                            <option value="">----------</option>
                                                            @if(count($section)>0)
                                                                @foreach($section as $key=>$seca)
                                                                    <?php $select = '' ?>
                                                                    @if($Division==$seca->class_id)
                                                                        <?php $select = 'selected' ?>
                                                                    @endif
                                                                    <option {{$select}}  att="{{$seca->class_id}}"

                                                                            value="{{$seca->class_id}}"><?=$seca->{'ctitle_'.App::getLocale()}?></option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 float-left m-b-10">
                                    <form class="form-horizontal">
                                        <div class="row clearfix">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                                <label class="float-left">@lang('lang.Teacher')</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                <div class="form-group" style="margin: 0">
                                                    <div class="form-line float-left">
                                                        <select id="Teacher" class="form-control show-tick">
                                                            <option value="-1">------------</option>
                                                            @if(count($teacher)>0)
                                                                @foreach($teacher as $key=>$teac)
                                                                    <?php $select = '' ?>
                                                                    @if($teac->userid==$steacher)
                                                                        <?php $select = 'selected' ?>
                                                                    @endif
                                                                    <option {{$select}}  att="{{$teac->userid}}"
                                                                            value="{{$teac->userid}}">{{$teac->fullname}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif

                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 float-right m-b-10">
                                <ul class="header-dropdown float-right mobile-hide">
                                    <li class="dropdown">
                                        <a class="dropdown-toggle" style="cursor: pointer">
                                            <i class="material-icons fullscreen-icon">fullscreen</i>
                                            <i style="display: none" class="material-icons fullscreen-exit-icon">fullscreen_exit</i>
                                        </a>
                                        <a class="print-schdule">
                                            <i class="material-icons">local_printshop</i>
                                        </a>
                                        <ul class="dropdown-menu pull-right"></ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="limiter">
                            <div class="container-table100">
                                <div class="wrap-table100">
                                    <div class="table100 ver1">
                                        <div class="table-responsive" id="schdule-table" >
                                            <table data-vertable="ver1"
                                               class="table table-bordered table-striped table-hover dataTable js-exportable"
                                               id="table_schedule">
                                            <thead>
                                            <tr class="row100 head">
                                                <th class="text-center column100 column1" data-column="column1"></th>
                                                <th class="text-center column100 column2"
                                                    data-column="column2">@lang('lang.Saturday')</th>
                                                <th class="text-center column100 column3"
                                                    data-column="column3">@lang('lang.Sunday')</th>
                                                <th class="text-center column100 column4"
                                                    data-column="column4">@lang('lang.Monday')</th>
                                                <th class="text-center column100 column5"
                                                    data-column="column5">@lang('lang.Tuesday')</th>
                                                <th class="text-center column100 column6"
                                                    data-column="column6">@lang('lang.Wednesday')</th>
                                                <th class="text-center column100 column7"
                                                    data-column="column7">@lang('lang.Thursday')</th>
                                                <th class="text-center column100 column8"
                                                    data-column="column8">@lang('lang.Friday')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @for($j=0;$j<8;$j++)
                                                <tr class="row100">
                                                    <td class="text-center column100 column1" data-column="column1">
                                                        <div> @lang('lang.Period')  {!! $j+1 !!}</div>
                                                    </td>

                                                    @for($i=0;$i<7;$i++)
                                                        <td class="column100 column{!! ($i+2) !!}"
                                                            data-column="column{{($i+2)}} ">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <div class="form-line float-left"
                                                                         id="<?=$i . '_' . $j ?>">
                                                                        <select onchange="showselected(this);" id="cat<?=$i . '_' . $j ?>" class="form-control show-tick ">
                                                                            <?php
                                                                            $cate_val = \Illuminate\Support\Facades\Lang::get('lang.Empty');
                                                                            $tea_val2 = -1;
                                                                            ?>
                                                                            @if(count($schedule)>0)
                                                                                @foreach($schedule as $key=>$sche)
                                                                                    @if($sche->dayofweek==$j+1&&$sche->period==$i+1)
                                                                                        <?php $cate_val = $sche->{'title_'.App::getLocale()};$tea_val2 = $sche->teacher;?>
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif

                                                                            <option selected cat="-1" value="-1">@lang('lang.Empty')</option>
                                                                            @if(count($categories)>0)
                                                                                @foreach($categories as $key=>$cate)
                                                                                        <?php $select = '' ?>
                                                                                    @if($cate->{'title_'.App::getLocale()}==$cate_val)
                                                                                      <?php $select = 'selected'; ?>

                                                                                    @endif
                                                                                            <option {{$select}} cat="{{$cate->category_id}}" value="{{$cate->category_id}}"><?=$cate->{'title_'.App::getLocale()}?></option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                        <select onchange="showselected(this);" id="teac<?=$i . '_' . $j ?>" class="form-control show-tick">
                                                                            <option  teac="-1" value="-1">@lang('lang.Empty')</option>
                                                                            @if(count($teacher)>0)
                                                                                @foreach($teacher as $key=>$teac)
                                                                                    <?php $select = '' ?>
                                                                                    @if($teac->userid==$tea_val2)
                                                                                            <?php $select = 'selected'; ?>
                                                                                    @endif
                                                                                        <option {{$select}} teac="{{$teac->userid}}" value="{{$teac->userid}}">{{$teac->fullname}}</option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                            @endfor
                                            @endfor
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 float-right">
                                @if(\App\Users::isManhalAdmin() or \App\Users::isSchoolManger() or \App\Users::isSchoolAdmin())
                                    <a class="btn btn-primary waves-effect float-right  {{(!count($section))?"disabled":"btn-saveschedule"}} " >@lang('lang.Save')</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var count=parseInt('<?= count($section) ?>');
            var messge="<?=Lang::get('lang.MustBeCHLevelCdtHvC');?>";

            if(!count){
                swal(messge);
            }
        });

    </script>
@endsection







