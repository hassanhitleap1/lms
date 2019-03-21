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
                                                                            <div class="text-center font-weight-bold">
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
                                                                                <?php $selectCategory = Illuminate\Support\Facades\Lang::get('lang.Empty'); ?>
                                                                                @if(count($categories)>0)
                                                                                    @foreach($categories as $key=>$cate)
                                                                                        @if($cate->{'title_'.App::getLocale()}==$cate_val)
                                                                                            <?php $selectCategory = $cate->{'title_'.App::getLocale()}; ?>
                                                                                        @endif
                                                                                    @endforeach
                                                                                @endif
                                                                                {{$selectCategory}}
                                                                            </div>
                                                                            <div class="text-center font-weight-bold">
                                                                                <?php $selectTeatcher = Illuminate\Support\Facades\Lang::get('lang.Empty'); ?>
                                                                                @if(count($teacher)>0)
                                                                                    @foreach($teacher as $key=>$teac)
                                                                                        <?php $select = '' ?>
                                                                                        @if($teac->userid==$tea_val2)
                                                                                            <?php $selectTeatcher = $teac->fullname; ?>
                                                                                        @endif
                                                                                    @endforeach
                                                                                @endif
                                                                                {{$selectTeatcher}}
                                                                            </div>
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

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
