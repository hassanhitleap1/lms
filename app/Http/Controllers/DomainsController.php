<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Domains;
use Illuminate\Support\Facades\DB;
use App\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Response;

class DomainsController extends BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index($lang,Request $request)
  {
      $query= DB::table('domains')
      ->select('domains.domain_id','domains.created_at','domains.title_ar','domains.title_en','domains.description_ar',
          'domains.description_en','domains.category','domains.domainnumber','categories.title_ar as ctitle_ar',
          'categories.title_en as ctitle_en')
      ->join('categories','categories.category_id','=','domains.category')
  ;
      $categories=Categories::all();
      if (Input::has('category') && $request->category!== null && $request->category!= -1){
          $query->where('category', '=',$request->category);
      }
      if (Input::has('orderby') && $request->orderby!= '' && Input::has('descask') && $request->descask!= ''){
          $orderby= $request->orderby;
          $descask=$request->descask;
          $query->orderBy($orderby, $descask);
      }
      $domaines= $query->paginate(config('lms.pagination'));
      return view('domains.index')->with('domaines',$domaines)->with('categories',$categories) ;
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    $categories=Categories::all();
    return view('domains.add')->with('categories',$categories);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    $rules = array(
      'domainnumber' => 'required',
      'title_ar' => 'required',
      'title_en'=> 'required',
      'description_ar'=> 'required',
      'description_en'=> 'required',
      'category'=> 'required',
      
    );
    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails()){
      $errors=$validator->errors()->all();
      return Response::json([
          'errors' => $errors
      ], 201);
      
    }
    $domain=new Domains;
    $domain->created_at=date('Y-m-d h:m:s');
    $domain->domainnumber=$request->domainnumber;
    $domain->title_ar=$request->title_ar;
    $domain->title_en=$request->title_en;
    $domain->description_ar=$request->description_ar;
    $domain->description_en=$request->description_en;
    $domain->category=$request->category;
    $domain->school=0;
    $domain->save();
      $categories=Categories::all();
    $domaines= DB::table('domains')
          ->select('domains.domain_id','domains.created_at','domains.title_ar','domains.title_en','domains.description_ar',
          'domains.description_en','domains.category','domains.domainnumber','categories.title_ar as ctitle_ar',
          'categories.title_en as ctitle_en')
          ->join('categories','categories.category_id','=','domains.category')
          ->paginate(config('lms.pagination'));
    $domaines->setPath('');  
    return view('domains.index')->with('domaines',$domaines)->with('categories',$categories)->renderSections()['content'];
  }



  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($lang,$id)
  {
    $categories=Categories::all();

    $domain=Domains::where("domain_id",$id)->first();
    return view('domains.edit')->with(['domain'=>$domain,'categories'=>$categories]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Request $request,$lang,$id)
  {
    $rules = array(
      'domainnumber' => 'required',
      'title_ar' => 'required',
      'title_en'=> 'required',
      'description_ar'=> 'required',
      'description_en'=> 'required',
      'category'=> 'required',
      
    );
    

    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails()){
      $errors=$validator->errors()->all();
      return Response::json([
          'errors' => $errors
      ], 201);
      
    }
    $domain=Domains::where("domain_id",$id)->first();
    $domain->updated_at=date('Y-m-d h:m:s');
    $domain->domainnumber=$request->domainnumber;
    $domain->title_ar=$request->title_ar;
    $domain->title_en=$request->title_en;
    $domain->description_ar=$request->description_ar;
    $domain->description_en=$request->description_en;
    $domain->category=$request->category;
    $domain->school=0;  
    $domain->save();
      $categories=Categories::all();
    $domaines= DB::table('domains')
            ->select('domains.domain_id','domains.created_at','domains.title_ar','domains.title_en','domains.description_ar',
            'domains.description_en','domains.category','domains.domainnumber','categories.title_ar as ctitle_ar',
            'categories.title_en as ctitle_en')
            ->join('categories','categories.category_id','=','domains.category')
            ->paginate(config('lms.pagination'));
    $domaines->setPath('');  
    return view('domains.index')->with('domaines',$domaines)->with('categories',$categories)->renderSections()['content'];
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($lang,$id)
  {
    $domain=Domains::where("domain_id",'=',$id)->delete();
      $categories=Categories::all();
    $domaines= DB::table('domains')
              ->select('domains.domain_id','domains.created_at','domains.title_ar','domains.title_en','domains.description_ar',
              'domains.description_en','domains.category','domains.domainnumber','categories.title_ar as ctitle_ar',
              'categories.title_en as ctitle_en')
              ->join('categories','categories.category_id','=','domains.category')
              ->paginate(config('lms.pagination'));
    $domaines->setPath('');  
    return view('domains.index')->with('domaines',$domaines)->with('categories',$categories)->renderSections()['content'];
  }



    /**
     * filter the specified DOAMIN.
     *
     * @return Response
     */
    public function filter(Request $request){
        $query= DB::table('domains')
            ->select('domains.domain_id','domains.created_at','domains.title_ar','domains.title_en','domains.description_ar',
                'domains.description_en','domains.category','domains.domainnumber','categories.title_ar as ctitle_ar',
                'categories.title_en as ctitle_en')
            ->join('categories','categories.category_id','=','domains.category')
           ;
        $categories=Categories::all();
        if (Input::has('category') && $request->category!== null){
          $query->where('category', '=',$request->category);
      }
        if (Input::has('orderby') && $request->orderby!= '' && Input::has('descask') && $request->descask!= ''){
            $orderby= $request->orderby;
            $descask=$request->descask;
            $query->orderBy($orderby, $descask);
        }
        $domaines= $query->paginate(config('lms.pagination'));
        return view('domains.index')->with('domaines',$domaines)->with('categories',$categories) ;
    }

    public function getDomainsCategory($lang,Request $request){
        $domains=Domains::where('category',$request->categoryId)->get();
        return response()->json($domains,200);
    }
}

?>