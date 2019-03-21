<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Pivot;
use App\Categories;
use App\Domains;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Response;




class PivotController extends  BaseController{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index($lang, Request $request)
  {
    $query =Pivot::where([]);
    if(isset($_GET["domain"])  && $_GET["domain"]!=-1){
        $query->where('domain',$_GET["domain"]);
    }

      if (Input::has('category')&& $request->category != null && $request->category != -1){

          $query->where('pivot.category',$request->category);
      }

      if (Input::has('domain')&& $request->domain!= null && $request->domain!= -1){

          $query->where('pivot.domain', $request->domain);

      }
      if (Input::has('orderby') && $request->orderby!= '' && Input::has('descask') && $request->descask!= ''){
          $orderby= $request->orderby;
          $descask=$request->descask;
          $query->orderBy($orderby, $descask);
      }

    $pivots=$query->paginate(config('lms.pagination'));
    $categories=Categories::all();
    $domains =Domains::all();
    return view('pivots.index')->with('pivots',$pivots)->with('categories',$categories)->with('domains',$domains);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    $categories=Categories::all();
    $domains=Domains::all();
    return view('pivots.add')->with(['categories'=>$categories,'domains'=>$domains]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    $rules = array(
      'pivotnumber' => 'required',
      'title_ar' => 'required',
      'title_en'=> 'required',
      'description_ar'=> 'required',
      'description_en'=> 'required',
      'category'=> 'required',
      'domain'=>'required',
      
    );
    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails()){
      $errors=$validator->errors()->all();
      return Response::json([
          'errors' => $errors
      ], 201);
      
    }
    $pivot=new Pivot;
    $pivot->created_at=date('Y-m-d h:m:s');
    $pivot->pivotnumber=$request->pivotnumber;
    $pivot->title_ar=$request->title_ar;
    $pivot->title_en=$request->title_en;
    $pivot->description_ar=$request->description_ar;
    $pivot->description_en=$request->description_en;
    $pivot->category=$request->category;
    $pivot->domain=$request->domain;
    $pivot->school=0;
    $pivot->save();
    $domains =Domains::all();
    $pivots=Pivot::paginate(config('lms.pagination'));
    $categories=Categories::all();
    $pivots->setPath('');  
    return view('pivots.index')->with('pivots',$pivots)->with('categories',$categories)->with('domains',$domains)->renderSections()['content'];
  }


  public function edit($lang,$id)
  {
    $pivot= Pivot::where("pivot_id",$id)->first();
    $categories=Categories::all();
    $domains=Domains::all();
    return view('pivots.edit')->with(['categories'=>$categories,'domains'=>$domains,'pivot'=>$pivot]);
  }



  public function update(Request $request,$lang,$id)
  {
    $rules = array(
      'pivotnumber' => 'required',
      'title_ar' => 'required',
      'title_en'=> 'required',
      'description_ar'=> 'required',
      'description_en'=> 'required',
      'category'=> 'required',
      'domain'=>'required',
      
    );
    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails()){
      $errors=$validator->errors()->all();
      return Response::json([
          'errors' => $errors
      ], 201);
      
    }
    $pivot= Pivot::where("pivot_id",$id)->first();
    $pivot->created_at=date('Y-m-d h:m:s');
    $pivot->pivotnumber=$request->pivotnumber;
    $pivot->title_ar=$request->title_ar;
    $pivot->title_en=$request->title_en;
    $pivot->description_ar=$request->description_ar;
    $pivot->description_en=$request->description_en;
    $pivot->category=$request->category;
    $pivot->domain=$request->domain;
    $pivot->school=0;
    $pivot->save();
      $domains =Domains::all();
    $pivots=Pivot::paginate(config('lms.pagination'));
    $categories=Categories::all();
    $pivots->setPath('');  
    return view('pivots.index')->with('pivots',$pivots)->with('categories',$categories)->with('domains',$domains)->renderSections()['content'];
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($lang,$id)
  {
    $pivot=Pivot::where("pivot_id",$id)->delete();
    $pivots=Pivot::paginate(config('lms.pagination'));
    $domains =Domains::all();
    $categories=Categories::all();
      $pivots->setPath('');
    return view('pivots.index')->with('pivots',$pivots)->with('categories',$categories)->with('domains',$domains)->renderSections()['content'];
  }


  public  function filter(Request $request){
      $categories=Categories::all();
      $domains =Domains::all();
      $pivots=  Pivot::where([]);


      if (Input::has('category')&& $request->category != null){

          $pivots->where('pivot.category',$request->category);
      }

      if (Input::has('domain')&& $request->domain!= null){

          $pivots->where('pivot.domain', $request->domain);

      }
      if (Input::has('orderby') && $request->orderby!= '' && Input::has('descask') && $request->descask!= ''){
          $orderby= $request->orderby;
          $descask=$request->descask;
          $pivots->orderBy($orderby, $descask);
      }

      $pivots=$pivots->paginate(config('lms.pagination'));

      return view('pivots.index')->with('pivots',$pivots)->with('categories',$categories)->with('domains',$domains);
  }


    /*
     * show pivots doamin
     */
  public  function  showPivotsDomain($lang,$domain){
      $pivots=Pivot::where('domain',$domain)->paginate(config('lms.pagination'));
      $categories=Categories::all();
      $domains =Domains::all();
      return view('pivots.index')->with('pivots',$pivots)->with('categories',$categories)->with('domains',$domains);
  }


  public function getPivotsDomain($lang,Request $request){
      $paivots=Pivot::where('domain',$request->domain)->get();
      return response()->json($paivots,200);
  }

  
}

?>