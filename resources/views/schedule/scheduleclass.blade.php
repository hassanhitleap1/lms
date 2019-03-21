@extends('layout.app')
@section('title',__('lang.School_schedule'))
@section('content')
    <script type="text/javascript">
        var lang = "{{Lang::getLocale()}}";
        var url = "{{url(Lang::getLocale().'/schedule')}}";
        var _token = "{{ csrf_token() }}";

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
                                                                            <div class="text-center teacher-view-schdule">
                                                                                <?php
                                                                                $period=-1;
                                                                                $title_en_val = \Illuminate\Support\Facades\Lang::get('lang.Empty');$title_ar_val = \Illuminate\Support\Facades\Lang::get('lang.Empty');
                                                                                $ctitle_en_val = \Illuminate\Support\Facades\Lang::get('lang.Empty');$ctitle_ar_val = \Illuminate\Support\Facades\Lang::get('lang.Empty');
                                                                                $category_val=-1;
                                                                                ?>
                                                                                @if(count($schedule)>0)
                                                                                    @foreach($schedule as $key=>$sche)
                                                                                        @if($sche->dayofweek==($j+1)&&$sche->period==($i+1))
                                                                                            <?php $category_val=$sche->category;  $ctitle_en_val = $sche->ctitle_en;$ctitle_ar_val=$sche->ctitle_ar;$title_en_val = $sche->title_en;$title_ar_val=$sche->title_ar;?>
                                                                                        @endif

                                                                                    @endforeach
                                                                                @endif

                                                                                @if(count($allclass)>0)
                                                                                        <?php $selectLevel=\Illuminate\Support\Facades\Lang::get('lang.Empty')?>
                                                                                    @foreach($allclass as $key=>$level)
                                                                                        @if($level->ltitle_en==$title_en_val)
                                                                                            <?php
                                                                                            $period=$level->level_id ?>
                                                                                                <?php $selectLevel=$level->{'ltitle_'.App::getLocale()}?>
                                                                                        @endif
                                                                                    @endforeach
                                                                                        {{$selectLevel}}
                                                                                @endif
                                                                            </div>
                                                                            <div class="text-center teacher-view-schdule" >
                                                                                @if(count($section2)>0)
                                                                                    <?php $selectClass=\Illuminate\Support\Facades\Lang::get('lang.Empty')?>
                                                                                    @foreach($section2 as $key=>$seca)
                                                                                        @if($ctitle_en_val==$seca->ctitle_en)
                                                                                            <?php $selectClass = $seca->{'ctitle_'.App::getLocale()} ?>
                                                                                        @endif
                                                                                    @endforeach
                                                                                @endif
                                                                                {{$selectClass}}
                                                                            </div>
                                                                            <div class="text-center teacher-view-schdule" >
                                                                                <?php $selectCategory=\Illuminate\Support\Facades\Lang::get('lang.Empty')?>
                                                                                @if(count($categories)>0)
                                                                                    @foreach($categories as $key=>$cate)
                                                                                        <?php $select = '' ?>
                                                                                        @if($cate->category_id==$category_val)
                                                                                            <?php $selectCategory = $cate->{'title_'.App::getLocale()} ?>
                                                                                        @endif
                                                                                    @endforeach
                                                                                @endif
                                                                                {{$selectCategory}}
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
