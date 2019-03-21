<?php
namespace App\Http\Controllers;

use App\Users;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use Response;

use App\Categories;

class CategoriesController extends BaseController
{


    public function indexCategory()
    {
        $orderBy='order';
        $DescAsk='ASC';
        $search='';
        $category = Categories::where([]);
        if (request()->isMethod('GET')) {
            $input = request()->all();
            if(isset($input['sorting'])&&$input['sorting']!=''){
                $orderBy=$input['sorting'];
            }
            if (isset($input['search'])&&$input['search'] != '') {
                $search=$input['search'];
                 $category->where('title_ar','like', '%' . $search . '%')->orWhere('title_en', 'like', '%' . $search . '%');
            }
            if(isset($input['descask'])&&$input['descask']!=''){
                $DescAsk=$input['descask'];
            }
            if (isset($input['search'])&&$input['search'] != '') {
                $search=$input['search'];
               $category->where('title_ar', 'like', '%' . $search . '%')->orWhere('title_en', 'like', '%' . $search . '%');
            }
            if(Users::isTeacher()){
                $arrCategory = DB::table('schedule')->select('category')->where('teacher',Auth::user()->userid)->get()->pluck('category')->toArray();
                $category->whereIn('category_id',$arrCategory );
            }
            $category = $category->orderBy($orderBy,$DescAsk)->get();
            return view('category.index',compact('category'),['search'=>$search,'descask'=>$DescAsk,'orderBy'=>$orderBy]);
        }
    }


    public function addCategories()
    {
        $input = request()->all();


        $rules = array(
            'title_ar' => 'required',
            'title_en' => 'required',

        );
        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails())
        {
            $errors=$validator->errors()->all();
            return Response::json([
                'errors' => $errors
            ], 201);
        }

        $category = new Categories();
        $category->title_ar = $input['title_ar'];
        $category->title_en = $input['title_en'];
        $category->order = $category->get()->count();
        $category->save();
        $orderBy='order';
        $DescAsk='ASC';
        $search='';

        if (isset($input['search'])&&$input['search'] != '') {
            $search=$input['search'];
            $category = Categories::where('title_ar', 'like', '%' . $search . '%')->orWhere('title_en', 'like', '%' . $search . '%')->orderBy($orderBy,$DescAsk)->get();
        } else {
            $category = Categories::orderBy($orderBy,$DescAsk)->get();
        }
        return view('category.index',compact('category'),['search'=>$search])->renderSections()['content'];
    }
    public function editCategories()
    {
        $input = request()->all();
        $rules = array(
            'title_ar' => 'required',
            'title_en' => 'required',

        );
        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails())
        {
            $errors=$validator->errors()->all();
            return Response::json([
                'errors' => $errors
            ], 201);
        }
        $category = Categories::where('category_id', $input['id'])->first();
        $category->title_ar = $input['title_ar'];
        $category->title_en = $input['title_en'];
        $category->update();
        $orderBy='order';
        $DescAsk='ASC';
        $search='';

        if (isset($input['search'])&&$input['search'] != '') {
            $search=$input['search'];
            $category = Categories::where('title_ar', 'like', '%' . $search . '%')->orWhere('title_en', 'like', '%' . $search . '%')->orderBy($orderBy,$DescAsk)->get();
        } else {
            $category = Categories::orderBy($orderBy,$DescAsk)->get();
        }
        return view('category.index',compact('category'),['search'=>$search])->renderSections()['content'];
    }


    public function savesortCategories()
    {
        $input = request()->all();
        foreach ($input['sort'] as $key => $value) {
            $category = Categories::where('category_id', $value)->first();
            $category->order = $key;
            $category->update();
        }
        $orderBy='order';
        $DescAsk='ASC';
        $search='';

        if (isset($input['search'])&&$input['search'] != '') {
            $search=$input['search'];
            $category = Categories::where('title_ar', 'like', '%' . $search . '%')->orWhere('title_en', 'like', '%' . $search . '%')->orderBy($orderBy,$DescAsk)->get();
        } else {
            $category = Categories::orderBy($orderBy,$DescAsk)->get();
        }
        return view('category.index',compact('category'),['search'=>$search])->renderSections()['content'];
    }
    public function viewedit($lang,$id)
    {
        $category = Categories::find($id);
        return view('category.edit',compact('category'));
    }
    public function deletecategory()
    {
        if (request()->isMethod('GET')) {
            $input = request()->all();
            if(isset($input['id'])){
                $id = $input['id'];
                if ($id != '') {
                    $category = Categories::find($id)->delete();
                }
            }
        }
        $orderBy='order';
        $DescAsk='ASC';
        $search='';
        if (isset($input['search'])&&$input['search'] != '') {
            $search=$input['search'];
            $category = Categories::where('title_ar', 'like', '%' . $search . '%')->orWhere('title_en', 'like', '%' . $search . '%')->orderBy($orderBy,$DescAsk)->get();
        } else {
            $category = Categories::orderBy($orderBy,$DescAsk)->get();
        }
        return view('category.index',compact('category'),['search'=>$search])->renderSections()['content'];
    }
    public function viewAddCategory($lang){
        return view('category.add', ['lang' => $lang]);
    }
    public function viewSort($lang){
        $category = Categories::orderBy('order')->get();
        return view('category.sort', compact('category'), ['lang' => $lang]);
    }





}

?>