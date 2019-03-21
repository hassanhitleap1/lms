@extends('layout.app')
@section('title', 'User Profile')
@section('content')
<div class="block-header">
    <h2>@yield('title')</h2>
</div>
<form  action="{{URL::to('/').'/'.Lang::getLocale().'/userprofile'}}" enctype="multipart/form-data" method="POST">
    <div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 student-view-option">
        <div class="card">
            @if(\App\Users::isStudent())
                <div class="header">
                    <div class="col-sm-4">
                        <label>@lang('lang.Type'):</label>
                        <span>@lang('lang.Students')</span>
                    </div>
                    <div class="col-sm-4">
                        <label>@lang('lang.Level'):</label>
                        <span><?= \Auth::user()->userLevel->{'ltitle_'.App::getLocale()}?></span>
                    </div>
                    <div class="col-sm-4">
                        <label>@lang('lang.Class'):</label>
                        <span><?= \Auth::user()->userClass->{'ctitle_'.App::getLocale()}?></span>
                    </div>
                </div>
            @endif
            <div class="body">
                <div class="row clearfix">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-line">
                                <label>@lang('lang.Username')</label>
                                <input type="text" class="form-control" placeholder="@lang('lang.Username')" name="uname" value="<?=$user->uname?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-line">
                                <label>@lang('lang.First_Name')</label>
                                <input type="text" class="form-control" placeholder="@lang('lang.First_Name')" value="<?=$user->fullname?>" name="fullname"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-line">
                                <label>@lang('lang.Password')</label>
                                <input type="password" class="form-control" placeholder="@lang('lang.Password')" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-line">
                                <label>@lang('lang.Phone')</label>
                                <input type="text" class="form-control" placeholder="@lang('lang.Phone')" value="<?=$user->phone?>" name="phone" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-line">
                                <label>@lang('lang.Email')</label>
                                <input type="text" class="form-control" placeholder="@lang('lang.Email')"  value="<?=$user->email?>" name="email"  />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-line">
                                <label>@lang('lang.Birth_of_Date')</label>
                                <input type="text" class="datepicker form-control" placeholder="@lang('lang.Please_choose_a_date')" data-dtp="dtp_ZYZzi"  value="<?=$user->birthdate?>" name="birthdate" onclick="loadPicker()"  onfocus="loadPicker()" onmouseover="loadPicker()">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-line">
                                <label>Country</label>
                                <select class="form-control show-tick" name="country">
                                        <option value="">----</option>
                                    @foreach ($countris as $country)
                                        @if($user->country ==$country->id)
                                            <option value="{{$country->id}}" selected>{{$country->country_name}}</option>
                                        @else
                                            <option value="{{$country->id}}">{{$country->country_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-line">
                                <label>@lang('lang.Gender')</label>
                                <select class="form-control show-tick" name="gender">
                                    <option value={{\App\Users::MALE}} {{$user->gender==\App\Users::MALE?'selected':''}}>@lang('lang.Male')</option>
                                    <option value={{\App\Users::FEMALE}} {{$user->gender==\App\Users::FEMALE?'selected':''}}>@lang('lang.Female')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-line">
                                <label>@lang('lang.Address')</label>
                                <input type="text" class="form-control" placeholder="@lang('lang.Address')" name="address" value="<?=$user->address?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-line">
                                <label>@lang('lang.Upload_avatar')</label>
                                    <label class="input-group-btn ">
                                        <span class="btn btn-primary file-upload-button">
                                            <i class="material-icons">file_upload</i>
                                            <input type="file" class="jq_formdata" name="avatar" id="avatar" style="display: none;">
                                        </span>
                                    </label>
                                    <div class="upload-view" style=""></div>
                                    <input type="text" class="form-control" style="display: none" readonly="">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <input type="hidden" class="jq_formdata" name="_token" value="{{ csrf_token() }}">
                            <button class="btn btn-primary waves-effect float-right" type="submit" >@lang('lang.Update')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</form>
@if ($errors->count())
    <script> swal('{{$errors->first()}}');</script>
@endif
@if (Session::has('update'))
    <script>
        Lobibox.notify('success', {
            msg: '{{Session::get('update')}}'
        });
    </script>
    {{session()->forget('update')}}

@endif
@endsection
