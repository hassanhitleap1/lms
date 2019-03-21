@section('title', 'Add')
<div class="row clearfix default">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Title_Ar')</label>
                <input id="title_ar" type="text" class="form-control" value="@if(isset($curricula->cu_title_ar)){{$curricula->cu_title_ar}}@endif"
                       placeholder="@lang('lang.Title')"/>

            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Title_En')</label>
                <input id="title_en" value="@if(isset($curricula->cu_title_en)){{$curricula->cu_title_en}}@endif" type="text" class="form-control" placeholder="@lang('lang.Title')"/>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Description_Ar')</label>
                <textarea name="description_ar" rows="2" class="form-control no-resize" placeholder="@lang('lang.Description_Ar')">@if(isset($curricula->cu_description_ar)){{$curricula->cu_description_ar}}@endif</textarea>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="form-line">
                <label>@lang('lang.Description_En')</label>
                <textarea name="description_en" rows="2" class="form-control no-resize" placeholder="@lang('lang.Description_En')">@if(isset($curricula->cu_description_en)){{$curricula->cu_description_en}}@endif</textarea>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
        <div class="form-group">
            <label class="float-left">@lang('lang.Level')</label>
            <div class="form-line float-left">
                <select id="level" name="level" class="form-control show-tick">
                    <?php
                    foreach($levels as $level){
                    if(isset($curricula->cu_level)&& $curricula->cu_level==$level["level_id"]){
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
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
        <div class="form-group">
            <label class="float-left">@lang('lang.Category')</label>
            <div class="form-line float-left">
                <select id="Curriculum" name="Curriculum" class="form-control show-tick">

                    <?php
                    foreach($categories as $category){

                        if(isset($curricula->cu_category)&& $curricula->cu_category==$category["category_id"]){
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

    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
        <div   class="thumbnail jq_book_chosed " curricula_title=""   bookid="@if(isset($curricula->cu_book)){{$curricula->cu_book}}@endif">
            <img class="thumbnail_img_curricula" src="@if(isset($curricula->thumb_book) && ($curricula->title_book !=0)){{$curricula->thumb_book}}@endif">
            <div class="caption">
                <h3 class="curricula_title">@if(isset($curricula->title_book)){{$curricula->title_book}}@endif</h3>
            </div>
        </div>
    </div>
    @if(\Illuminate\Support\Facades\Auth::user()->permession ==1)
    <div class="modal-footer col-sm-12">
        <a class="btn btn-primary waves-effect btn-addbook jq_cu_chosebook">@lang("lang.Choose_book")</a>
        <a class="btn btn-primary waves-effect btn-saveeditcorriculm" >@lang("lang.Save")</a>
    </div>
    @endif
</div>

<div class="row clearfix add-book-div" style="display: none">
    <div class="row dataTables_wrapper">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 float-left">
            <div class="form-horizontal">
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group search-container-a">
                            <div class="form-line float-left">
                                <input  id="search_books" type="search" value="{{$search}}" class="form-control input-sm"  placeholder="@lang('lang.Search')" aria-controls="DataTables_Table_0">
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
                    <div class="col-lg-3 col-md-3 col-sm-2 col-xs-12 form-control-label">
                        <label class="float-left">@lang('lang.Curriculum')</label>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-10 col-xs-12">
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
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
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
        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
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
        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
            <div  att_idcorriculum="{{$id}}" class="dataTables_paginate paging_simple_numbers"
                 id="books_pagination" att_type="edit">
                {{$books->links()}}
            </div>
        </div>
    </div>


    @endif

    <div class="modal-footer col-sm-12">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <a class="btn btn-primary waves-effect btn-backbook pull-right">@lang("lang.Back")</a>
        </div>
    </div>
</div>
