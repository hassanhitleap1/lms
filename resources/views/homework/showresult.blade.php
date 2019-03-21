@section('title', 'Show_result')
@php $lang=Lang::getLocale();@endphp
{{App::setLocale($lang)}}

<div class="row clearfix assign-homework-container">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="body table-responsive p-t-0">
                <div class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        @foreach ($data as $object)
                        <div class="col-sm-8 float-left">
                            <label class="float-left col-manhalgreen">@lang('lang.Homework_name'): </label>
                            <span class="float-left p-l-5">{{$object->title}}</span>
                            <label class="clear float-left col-manhalgreen">@lang('lang.Category'): </label>
                            <span class="float-left p-l-5">{{$object->{"title_".Lang::getLocale()} }}</span>
                            <label class="clear float-left col-manhalgreen">@lang('lang.Description'): </label>
                            <p class="float-left p-l-5">{{$object->description}}</p>
                        </div>
                        <div class="col-sm-4 float-right">
                            <span class="float-right  p-l-5">{{$object->startdate}}</span>
                            <label class="float-right col-manhalgreen">@lang('lang.Start_Date'): </label>
                            <span class="float-right clear  p-l-5">{{$object->enddate}}</span>
                            <label class=" float-right col-manhalgreen">@lang('lang.End_Date'): </label>
                        </div>
                        @endforeach
                    </div>
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                        @if(isset($users)&&count($users)>0)
                        <tr>
                            <th>#</th>
                            <th>@lang('lang.Student_name')</th>
                            <th>@lang('lang.Level')</th>
                            <th>@lang('lang.Class')</th>
                            <th>@lang('lang.Final_result')</th>
                        </tr>
                        </thead>
                        <tbody>

                            @foreach($users as $items=>$item)
                        <tr>
                            <td>{{$items}}</td>
                            <td>{{$item->uname}}</td>
                            <td>{{$item-> {"ltitle_".Lang::getLocale()} }}</td>
                            <td>{{$item-> {"ctitle_".Lang::getLocale()} }}</td>
                            <td>{{$item->result}}</td>
                        </tr>
                            @endforeach

                        </tbody>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
