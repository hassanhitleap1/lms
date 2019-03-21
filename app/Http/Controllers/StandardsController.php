<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Standards;
use App\Categories;
use App\Domains;
use App\Pivot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Response;

class StandardsController extends BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index($lang,Request $request)
  {
      $query=  Standards::where([]);

      if (Input::has('category') && $request->category!= -1){
          $query= $query->where('category','=',$request->category);
      }
      if (Input::has('domain') && $request->domain!= -1){
          $query= $query->where('domain','=',$request->domain);
      }
      if (Input::has('pivot') && $request->pivot!= -1){
          $query= $query->where('pivot','=',$request->pivot);
      }
      if (Input::has('orderby') && $request->orderby!= '' && Input::has('descask') && $request->descask!= ''){
          $orderby= $request->orderby;
          $descask=$request->descask;
          $query->orderBy($orderby, $descask);
      }

      $domains=Domains::all();
      $pivots=Pivot::all();
      $categories=Categories::all();
      $standards= $query->paginate(config('lms.pagination'));
      return view('standards.index',[
          'categories'=>$categories,
          'domains'=>$domains,
          'pivots'=>$pivots,
          'standards'=>$standards
      ]);
    
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
    $paivots=Pivot::all();
    return view('standards.add')->with(['categories'=>$categories,'domains'=>$domains,'paivots'=>$paivots]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    $rules = array(
      'standard_number' => 'required',
      'title_ar' => 'required',
      'title_en'=> 'required',
      'description_ar'=> 'required',
      'description_en'=> 'required',
      'category'=> 'required',
      'domain'=>'required',
      'pivot'=>'required',
      
    );
    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails()){
      $errors=$validator->errors()->all();
      return Response::json([
          'errors' => $errors
      ], 201);
      
    }
    $standard=new Standards;
    $standard->created_at=date('Y-m-d h:m:s');
    $standard->standard_number=$request->standard_number;
    $standard->title_ar=$request->title_ar;
    $standard->title_en=$request->title_en;
    $standard->description_ar=$request->description_ar;
    $standard->description_en=$request->description_en;
    $standard->category=$request->category;
    $standard->domain=$request->domain;
    $standard->pivot=$request->pivot;
    $standard->school=0;

    $standard->save();

    $standards=Standards::paginate(config('lms.pagination'));
    $standards->setPath('');  
    $domains=Domains::all();
    $pivots=Pivot::all();
    $categories=Categories::all();

    $standards->setPath('');  
    return view('standards.index',[
      'categories'=>$categories,
        'domains'=>$domains,
        'pivots'=>$pivots,
        'standards'=>$standards
      ])->renderSections()['content'];
  }


 

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($lang,$id)
  {
    $standard= Standards::where("standard_id",$id)->first();
    $pivots= Pivot::all();
    $categories=Categories::all();
    $domains=Domains::all();
    return view('standards.edit')->with(['categories'=>$categories,'domains'=>$domains,'pivots'=>$pivots,'standard'=>$standard]);
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
      'standard_number' => 'required',
      'title_ar' => 'required',
      'title_en'=> 'required',
      'description_ar'=> 'required',
      'description_en'=> 'required',
      'category'=> 'required',
      'domain'=>'required',
      'pivot'=>'required',
      
    );
    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails()){
      $errors=$validator->errors()->all();
      return Response::json([
          'errors' => $errors
      ], 201);
      
    }

    $standard=Standards::where("standard_id",$id)->first();
    $standard->created_at=date('Y-m-d h:m:s');
    $standard->standard_number=$request->standard_number;
    $standard->title_ar=$request->title_ar;
    $standard->title_en=$request->title_en;
    $standard->description_ar=$request->description_ar;
    $standard->description_en=$request->description_en;
    $standard->category=$request->category;
    $standard->domain=$request->domain;
    $standard->pivot=$request->pivot;
    $standard->school=0;

    $standard->save();
    $domains=Domains::all();
    $pivots=Pivot::all();
    $categories=Categories::all();
    $standards=Standards::paginate(config('lms.pagination'));
    $standards->setPath('');  
    return view('standards.index',[
      'categories'=>$categories,
        'domains'=>$domains,
        'pivots'=>$pivots,
        'standards'=>$standards
      ])->renderSections()['content'];
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($lang,$id)
  {
    $standard=Standards::where("standard_id",$id)->delete();
    $standards=Standards::paginate(config('lms.pagination'));
    $domains=Domains::all();
    $pivots=Pivot::all();
    $categories=Categories::all();
    $standards->setPath('');  
    return view('standards.index',[
      'categories'=>$categories,
        'domains'=>$domains,
        'pivots'=>$pivots,
        'standards'=>$standards
    ])->renderSections()['content'];
    
  }


  public function showStandersPivot($lang,$idPivot){
      $standards=Standards::where('pivot',$idPivot)->paginate(config('lms.pagination'));
      $domains=Domains::all();
      $pivots=Pivot::all();
      $categories=Categories::all();
    
      return view('standards.index',[
        'categories'=>$categories,
          'domains'=>$domains,
          'pivots'=>$pivots,
          'standards'=>$standards
      ]);
  }

    public function showStandersDomain($lang,$domain){

        $standards=Standards::where('domain',$domain)->paginate(config('lms.pagination'));
        $domains=Domains::all();
        $pivots=Pivot::all();
        $categories=Categories::all();
      
        return view('standards.index',[
          'categories'=>$categories,
            'domains'=>$domains,
            'pivots'=>$pivots,
            'standards'=>$standards
        ]);
        

    }


    public function filter(Request $request){
      $query=  Standards::where([]);

      if (Input::has('category') && $request->category!= -1){
        $query= $query->where('category','=',$request->category);
    }
      if (Input::has('domain') && $request->domain!= -1){
          $query= $query->where('domain','=',$request->domain);
  }
      if (Input::has('pivot') && $request->pivot!= -1){
          $query= $query->where('pivot','=',$request->pivot);
      }
        if (Input::has('orderby') && $request->orderby!= '' && Input::has('descask') && $request->descask!= ''){
            $orderby= $request->orderby;
            $descask=$request->descask;
            $query->orderBy($orderby, $descask);
        }

      $domains=Domains::all();
      $pivots=Pivot::all();
      $categories=Categories::all();
      $standards= $query->paginate(config('lms.pagination'));
      return view('standards.index',[
        'categories'=>$categories,
          'domains'=>$domains,
          'pivots'=>$pivots,
          'standards'=>$standards
      ]);
  }



    public function getStandardsPivot($lang,Request $request){
        $standards=Standards::where('pivot',$request->pivot)->get();
        return response()->json($standards,200);
    }
}

?>