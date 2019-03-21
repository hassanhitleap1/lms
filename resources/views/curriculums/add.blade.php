@section('title', 'Add')
        <div class="row clearfix default">
            <form class="jq_curriculmform">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <div class="form-line">
                        <label>@lang('lang.Title_Ar')</label>
                        <input id="title_ar" name="title_ar" type="text" value="@if(isset($adddata['title_ar'])){{$adddata['title_ar']}}@endif" class="form-control" placeholder="@lang('lang.Title')"/>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <div class="form-line">
                        <label>@lang('lang.Title_En')</label>
                        <input id="title_en" name="title_en" type="text" value="@if(isset($adddata['title_en'])){{$adddata['title_en']}}@endif" class="form-control" placeholder="@lang('lang.Title')"/>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <div class="form-line">
                        <label>@lang('lang.Description_Ar')</label>
                        <textarea id="description_ar" name="description_ar" rows="2" class="form-control no-resize" placeholder="@lang('lang.Description_Ar')">@if(isset($adddata['description_ar'])){{$adddata['description_ar']}}@endif</textarea>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <div class="form-line">
                        <label>@lang('lang.Description_En')</label>
                        <textarea id="description_en" name="description_en" rows="2" class="form-control no-resize" placeholder="@lang('lang.Description_En')">@if(isset($adddata['description_en'])){{$adddata['description_en']}}@endif</textarea>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                <div class="form-group">
                    <label class="float-left">@lang('lang.Level')</label>
                    <div class="form-line float-left">
                        <select id="level" name="level" class="form-control show-tick">
                            <?php
                            foreach($levels as $level){
                            if(isset($adddata['level'])&& $adddata['level']==$level["level_id"]){
                            ?>
                            <option selected value="<?=$level["level_id"];?>"><?=$level["ltitle_" . Lang::getLocale()];?></option>
                            <?php
                            }else{
                            ?>
                            <option value="<?=$level["level_id"];?>"><?=$level["ltitle_" . Lang::getLocale()];?></option>
                            <?php
                            }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                <div class="form-group">
                    <label class="float-left">@lang('lang.Category')</label>
                    <div class="form-line float-left">
                        <select id="category" name="category" class="form-control show-tick">
                            <?php
                            foreach($categories as $category){

                            if(isset($adddata['category'])&& $adddata['category']==$category["category_id"]){
                            ?>
                            <option selected value="<?=$category["category_id"];?>"><?=$category["title_" . Lang::getLocale()];?></option>
                            <?php
                            }else{
                            ?>
                            <option  value="<?=$category["category_id"];?>"><?=$category["title_" . Lang::getLocale()];?></option>
                            <?php
                            }}
                            ?>
                        </select>
                    </div>
                </div>
            </div>
                <div class="col-sm-2 col-md-2">
                    <div class="thumbnail jq_book_chosed" curricula_title="" bookid="">
                        <img  class="thumbnail_img_curricula" src="">
                        <div class="caption">
                            <h3  class="curricula_title"></h3>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="cu_book" name="cu_book" value="0">
                <input type="hidden" id="thumbnail_img_curricula" name="thumbnail_img_curricula" value="0">
                <input type="hidden" id="curricula_title" name="curricula_title" value="0">
                <input type="hidden" class="jq_formdata" name="_token" value="{{ csrf_token() }}">

            </form>
            @if(\Illuminate\Support\Facades\Auth::user()->permession ==1)
            <div class="modal-footer col-sm-12" >
                <a class="btn btn-primary waves-effect btn-addbook jq_cu_chosebook">@lang("lang.Choose_book")</a>
                <a class="btn btn-primary waves-effect jq_insert_curriculum">@lang("lang.Save")</a>
            </div>
             @endif
        </div>
<div class="row clearfix add-book-div" style="display: none">
    <div class="row dataTables_wrapper">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 m-b-10 float-left">
            <div class="form-horizontal">
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group search-container-a">
                            <div class="form-line float-left">
                                <input  id="search_books" type="search" value="@if(isset($search) ){{$search}}@endif" class="form-control input-sm"  placeholder="@lang('lang.Search')" aria-controls="DataTables_Table_0">
                            </div>
                            <button class="btn btn-primary waves-effect float-left search-absolute1" id="categorybooks-search"><i class="material-icons">search</i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 float-left">
            <form class="form-horizontal">
                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label class="float-left">@lang('lang.Curriculum')</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="form-group">
                            <div class="form-line float-left">
                                <select id="categorybooks" class="form-control show-tick">
                                    <option  value="">-------</option>
                                    @foreach($categories_books as $key=>$cat)
                                        @if($categorybooks==$cat)
                                            <option selected value="{{$cat}}">{{$key}}</option>
                                        @else
                                            <option value="{{$cat}}">{{$key}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="listbook_api"  class="row m-t-20 " style="display:none">
        @if(isset($books)&&count($books)>0 )
            @foreach($books as $book)
                @if($book['id']>0)
                    <div class="col-sm-2 col-md-2">
                        <div class="thumbnail choosebooks_curricula" att_title="{{$book["title_".Lang::getLocale()]}}"  bookid="{{$book['id']}}">
                            <?php
                            $URLImage = str_replace("###", $book['id'], $book['thumbnail']);
                            ?>
                            <img id="thumbnail_{{ $book['id']}}"  src="{{$URLImage}}">
                            <div class="caption">
                                <h3>{{$book["title_".Lang::getLocale()]}}</h3>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
    </div>
    @if(count($books)>5)
        <div class="row">
            <div class="col-sm-5">
                <div class="dataTables_info">
                    <span class="pull-left">@lang('lang.Showing')</span><span
                            class="pull-left">{{($books->currentpage()-1)*$books->perpage()+1}}</span>
                    <span class="pull-left">@lang('lang.to') </span>
                    <span class="pull-left"> {{(($books->currentpage()-1)*$books->perpage())+$books->count()}}</span><span
                            class="pull-left">@lang('lang.of')</span><span
                            class="pull-left">{{$books->total()}}</span><span
                            class="pull-left">@lang('lang.Books')</span>
                </div>
            </div>
            @endif
            <div class="col-sm-7">
                <div  att_idcorriculum="" class="dataTables_paginate paging_simple_numbers"
                      id="books_pagination" att_type="add">
                    {{$books->links()}}
                </div>
            </div>
        </div>


    @endif

    <div class="modal-footer col-sm-12">

        <div class="col-sm-5">
            <a class="btn btn-primary waves-effect btn-backbook pull-left">@lang("lang.Back")</a>
        </div>
    </div>
</div>
