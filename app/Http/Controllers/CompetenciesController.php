<?php
namespace App\Http\Controllers;

use App\Categories;
use App\Competencies;
use App\Domains;
use App\Pivot;
use App\Standards;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;
use Illuminate\Support\Facades\Lang;


class CompetenciesController extends BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index($lang,Request $request)
  {
      $domains=Domains::all();
      $pivots=Pivot::all();
      $query=  Competencies::where([]);
      if (Input::has('domain') && $request->domain!= -1){
          $query= $query->where('domain','=',$request->domain);
      }
      if (Input::has('pivot') && $request->pivot!=-1){
          $query= $query->where('pivot','=',$request->pivot);
      }
      if (Input::has('standard') && $request->standard!=-1){
          $query= $query->where('standard','=',$request->standard);
      }
      if (Input::has('search')&& $request->search!== null){
          $search= $request->search;
          $query->where(function($query) use ($search){
              if(Lang::getLocale()=='en'){
                  $query->orwhere('title_en','like','%'.$search .'%')
                      ->orwhere('description_en','like','%'.$search .'%');
              }else{
                  $query->orwhere('title_ar','like','%'.$search .'%')
                      ->orwhere('description_ar','like','%'.$search .'%');
              }
          });
      }
      if (isset($request->orderby) && $request->orderby!= '' && isset($request->descask) && $request->descask!= ''){
          $orderby= $request->orderby;
          $descask=$request->descask;
          $query->orderBy($orderby, $descask);
      }
      $standards=Standards::all();
      $competencies= $query->paginate(config('lms.pagination'));
    return view('competencies.index',[
        'competencies'=>$competencies,
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
      $pivots=Pivot::all();
      $standards=Standards::all();

   return view('competencies.add')
       ->with('categories',$categories)
       ->with('domains',$domains)
       ->with('pivots',$pivots)
       ->with('standards',$standards);

  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request  $request)
  {
      $rules=[
          'title_ar'=>'required',
          'title_en'=>'required',
          'description_ar'=>'required',
          'description_en'=>'required',
          'domain'=>'required',
          'pivot'=>'required',
          'standard'=>'required',
      ];
      $validator = Validator::make(Input::all(), $rules);

      if ($validator->fails()) {

          $errors = $validator->errors()->all();
          return Response::json([
              'errors' => $errors
          ], 201);

      }
      $competencie= new Competencies();
      $competencie->title_ar=$request->title_ar;
      $competencie->title_en=$request->title_en;
      $competencie->description_ar=$request->description_ar;
      $competencie->description_en=$request->description_en;
      $competencie->domain=$request->domain;
      $competencie->pivot=$request->pivot;
      $competencie->standard=$request->standard;
      $competencie->level=0;
      $competencie->school=0;

      $competencie->save();
      $domains=Domains::all();
      $pivots=Pivot::all();
      $standards=Standards::all();
      $competencies= Competencies::paginate(config('lms.pagination'));
      $competencies->setPath('');
      return view('competencies.index')->with([
          'competencies'=>$competencies,
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
      $categories=Categories::all();
      $domains=Domains::all();
      $pivots=Pivot::all();
      $standards=Standards::all();
      $competencie=Competencies::where("compentence_id",$id)->first();

      return view('competencies.edit')
          ->with('categories',$categories)
          ->with('domains',$domains)
          ->with('pivots',$pivots)
          ->with('standards',$standards)
          ->with('competencie',$competencie);


  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Request $request,$lang,$id)
  {
      $rules=[
          'title_ar'=>'required',
          'title_en'=>'required',
          'description_ar'=>'required',
          'description_en'=>'required',
          'domain'=>'required',
          'pivot'=>'required',
          'standard'=>'required',
      ];
      $validator = Validator::make(Input::all(), $rules);

      if ($validator->fails()) {

          $errors = $validator->errors()->all();
          return Response::json([
              'errors' => $errors
          ], 201);

      }
      $competencie= Competencies::where('compentence_id',$id)->first();
      $competencie->title_ar=$request->title_ar;
      $competencie->title_en=$request->title_en;
      $competencie->description_ar=$request->description_ar;
      $competencie->description_en=$request->description_en;
      $competencie->domain=$request->domain;
      $competencie->pivot=$request->pivot;
      $competencie->standard=$request->standard;
      $competencie->level=0;
      $competencie->school=0;

      $competencie->save();
      $domains=Domains::all();
      $pivots=Pivot::all();
      $standards=Standards::all();
      $competencies= Competencies::paginate(config('lms.pagination'));
      $competencies->setPath('');
      return view('competencies.index')->with([
          'competencies'=>$competencies,
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
      $competencie= Competencies::where('compentence_id',$id)->delete();
      $domains=Domains::all();
      $pivots=Pivot::all();
      $standards=Standards::all();
      $competencies= Competencies::paginate(config('lms.pagination'));
      $competencies->setPath('');
      return view('competencies.index')->with([
          'competencies'=>$competencies,
          'domains'=>$domains,
          'pivots'=>$pivots,
          'standards'=>$standards
      ])->renderSections()['content'];
  }

  public function filter(Request $request){
      $query=  Competencies::where([]);
      if (Input::has('domain') && $request->domain!= -1){
          $query= $query->where('domain','=',$request->domain);
  }
      if (Input::has('pivot') && $request->pivot!=-1){
          $query= $query->where('pivot','=',$request->pivot);
      }
      if (Input::has('standard') && $request->standard!=-1){
          $query= $query->where('standard','=',$request->standard);
      }
      if (Input::has('search')&& $request->search!== null){
        $search= $request->search;
        $query->where(function($query) use ($search){
            if(Lang::getLocale()=='en'){
                $query->orwhere('title_en','like','%'.$search .'%')
                ->orwhere('description_en','like','%'.$search .'%');
            }else{
                $query->orwhere('title_ar','like','%'.$search .'%')
                ->orwhere('description_ar','like','%'.$search .'%');
            }
        });
    }

      $domains=Domains::all();
      $pivots=Pivot::all();
      $standards=Standards::all();
      $competencies= $query->paginate(config('lms.pagination'));
      return view('competencies.index',[
          'competencies'=>$competencies,
          'domains'=>$domains,
          'pivots'=>$pivots,
          'standards'=>$standards
      ]);
  }



    public  function showCompetenciesPivot($lang,$idPivot){

        $domains=Domains::all();
        $pivots=Pivot::all();
        $standards=Standards::all();
        $competencies= Competencies::where('pivot',$idPivot)->paginate(config('lms.pagination'));
        return view('competencies.index',[
            'competencies'=>$competencies,
            'domains'=>$domains,
            'pivots'=>$pivots,
            'standards'=>$standards
        ]);

    }


    public function showCompetenciesDomain($lang, $domain)
    {
        $domains=Domains::all();
        $pivots=Pivot::all();
        $standards=Standards::all();
        $competencies= Competencies::where('domain',$domain)->paginate(config('lms.pagination'));
        return view('competencies.index',[
            'competencies'=>$competencies,
            'domains'=>$domains,
            'pivots'=>$pivots,
            'standards'=>$standards
        ]);
    }

    public function showCompetenciesStandard($lang, $standard)
    {
        $domains=Domains::all();
        $pivots=Pivot::all();
        $standards=Standards::all();
        $competencies= Competencies::where('standard',$standard)->paginate(config('lms.pagination'));
        return view('competencies.index',[
            'competencies'=>$competencies,
            'domains'=>$domains,
            'pivots'=>$pivots,
            'standards'=>$standards
        ]);
    }

}

?>