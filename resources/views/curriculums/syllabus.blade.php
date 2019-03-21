@extends('layout.app')
@section('title', __('lang.Curriculums'))
@section('content')
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>
<div class="row clearfix default">
    <div class="col-sm-12 col-md-12">
        <div class="tree">
            <ul>
                <li class="marg">
                    <i class="material-icons">home</i><i class="material-icons btn-a btn-close" data="0">remove_circle_outline</i><a class="open">Arabic</a>
                    <ul style="display: block;">
                        <li><i class="material-icons">home</i><a href="#">A</a>
                        </li>
                        <li><i class="material-icons">home</i><a href="#">B</a>
                        </li>
                        <li><i class="material-icons">home</i><a href="#">C</a>
                        </li>
                        <li class="level2">
                            <i class="material-icons">home</i><i class="material-icons btn-a btn-close" data="0">remove_circle_outline</i><a href="#">D</a>
                            <ul style="display: block;">
                                <li><i class="material-icons">home</i><a href="#">A</a>
                                </li>
                                <li><i class="material-icons">home</i><a href="#">B</a>
                                </li>
                                <li><i class="material-icons">home</i><a href="#">C</a>
                                </li>
                                <li><i class="material-icons">home</i><a href="#">D</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="marg">
                    <i class="material-icons">edit</i><i class="material-icons btn-a btn-open">add_circle_outline</i><a class="open">English</a>
                    <ul>
                        <li><i class="material-icons">home</i><a href="#">A</a>
                        </li>
                        <li><i class="material-icons">home</i><a href="#">B</a>
                        </li>
                        <li><i class="material-icons">home</i><a href="#">C</a>
                        </li>
                        <li>
                            <i class="material-icons">home</i><a href="#">D</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection