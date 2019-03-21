<?php
namespace App\Http\Controllers;

use App\Assigns;
use App\Badges;
use App\Categories;
use App\Levels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Lessons;
use Validator;
use Response;

class BadgesController extends BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(Request $request)
  {
      $levels= Levels::all();
      $categories=Categories::all();
     $path='?';
      $query = Badges::where([]);
      if (Input::has('level') && $request->level!= -1){
          $query->where('level_id', '=',$request->level);
          $path.='level='.$request->level;
      }
      if (Input::has('category')&& $request->category!= -1){
          $query->where('category', $request->category);
          $path.='category='.$request->category;
      }
      if (Input::has('search')&& $request->search!== null){
          $search= $request->search;
          $query->where(function($query) use ($search){
              $query->orwhere('title_ar','like', '%' .$search.'%' )
                  ->orwhere('title_en', 'like','%' . $search.'%')
                  ->orwhere('description_ar', 'like','%' . $search.'%')
                  ->orwhere('description_en', 'like','%' . $search.'%')
              ;
          });
          $path.='search='.$request->search;
      };
      if ((Input::has('orderby')&& $request->orderby!= '') && (Input::has('descask')&& $request->descask!= '')){
          $query->orderBy($request->orderby, $request->descask);
          $path.='orderby='.$request->orderby.'&descask='.$request->descask;
      }
      $badges = $query->paginate(config('lms.pagination'));
      $badges->setPath($path);
    return view('badges.index')->with('badges',$badges)->with('levels',$levels)->with('categories',$categories);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
      $levels= Levels::all();
      $categories=Categories::all();
      return view('badges.add')->with('categories',$categories)->with('levels',$levels);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function store(Request $request)
    {
        $rules = array(
            'title_ar' => 'required',
            'title_en'=> 'required',
            'description_ar'=> 'required',
            'description_en'=> 'required',
            'category'=> 'required',
            'points'=>'required',
            'level'=>'required',

        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()){
            $errors=$validator->errors()->all();
            return Response::json([
                'errors' => $errors
            ], 201);

        }
        $badge=new Badges;
        $badge->created_at	=date('Y-m-d h:m:s');
        $badge->title_ar=$request->title_ar;
        $badge->title_en=$request->title_en;
        $badge->description_ar=$request->description_ar;
        $badge->description_en=$request->description_en;
        $badge->category=$request->category;
        $badge->level_id=$request->level;
        $badge->school=0;
        $badge->points=$request->points;
        $badge->save();
        $levels= Levels::all();
        $categories=Categories::all();
        $badges = Badges::paginate(config('lms.pagination'));
         $badges->setPath('');
        return view('badges.index')->with('badges',$badges)->with('levels',$levels)->with('categories',$categories)->renderSections()['content'];
    }



  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($lang,$id)
  {
      $levels= Levels::all();
      $badge = Badges::find($id);
      $categories=Categories::all();
    return view('badges.edit')->with('categories',$categories)->with('badge',$badge)->with('levels',$levels);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($lang ,$id ,Request $request)
  {
      $rules = array(
          'title_ar' => 'required',
          'title_en'=> 'required',
          'description_ar'=> 'required',
          'description_en'=> 'required',
          'category'=> 'required',
          'points'=>'required',
          'level'=>'required',

      );
      $validator = Validator::make(Input::all(), $rules);
      if ($validator->fails()){
          $errors=$validator->errors()->all();
          return Response::json([
              'errors' => $errors
          ], 201);

      }
      $badge= Badges::where("badge_id",$id)->first();
      $badge->updated_at	=date('Y-m-d h:m:s');
      $badge->title_ar=$request->title_ar;
      $badge->title_en=$request->title_en;
      $badge->description_ar=$request->description_ar;
      $badge->description_en=$request->description_en;
      $badge->category=$request->category;
      $badge->level_id=$request->level;
      $badge->school=0;
      $badge->points=$request->points;
      $badge->save();
      $levels= Levels::all();
      $categories=Categories::all();
      $badges = Badges::paginate(config('lms.pagination'));
      $badges->setPath('');
      return view('badges.index')->with('badges',$badges)->with('levels',$levels)->with('categories',$categories)->renderSections()['content'];
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($lang,$id)
  {
      Badges::where("badge_id",$id)->delete();
      $levels= Levels::all();
      $categories=Categories::all();
      $badges = Badges::paginate(config('lms.pagination'));
      $badges->setPath('');
      return view('badges.index')->with('badges',$badges)->with('levels',$levels)->with('categories',$categories);
  }

    /**
     * filter the specified studets.
     *
     * @return Response
     */
    public  function filter(Request $request){

        $query = Badges::where([]);

        if (Input::has('level') && $request->level!= -1){
           $query->where('level_id', '=',$request->level);
        }
        if (Input::has('category')&& $request->category!= -1){
           $query->where('category', $request->category);
        }
        if (Input::has('search')&& $request->search!== null){
            $search= $request->search;
            $query->where(function($query) use ($search){
                $query->orwhere('title_ar','like', '%' .$search.'%' )
                    ->orwhere('title_en', 'like','%' . $search.'%')
                    ->orwhere('description_ar', 'like','%' . $search.'%')
                    ->orwhere('description_en', 'like','%' . $search.'%')
                ;
            });
        };
        $levels= Levels::all();
        $categories=Categories::all();
        $badges= $query->paginate(config('lms.pagination'));
        $badges->setPath('');
        return view('badges.index')->with('badges',$badges)->with('levels',$levels)->with('categories',$categories);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function assignLesson($lang,$id)
    {
        $assign=Assigns::where('product_id',$id)->where('product_type',Badges::ProductName)->first();
        $badge= Badges::where("badge_id",$id)->first();
        $lessons=Lessons::where('level',$badge->level_id)->get();
        return view('badges.assign_lesson')->with('assign',$assign)->with('lessons',$lessons)
            ->with('badge',$badge);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function saveassign($lang,$product_id,Request $request)
    {
        $assign=Assigns::where('product_id',$product_id)->where('product_type',Badges::ProductName)->first();
        $rules = array(
            'lessons' => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()){
            $errors=$validator->errors()->all();
            return Response::json([
                'errors' => $errors
            ], 201);

        }

        if(!empty($assign)){
            if($request->lessons==$assign->ref_id &&$product_id== $assign->product_id ){
                return Response::json([
                    'errors' => ["  it's alrady used"]
                ], 201);
            }else{
                $assign->product_id=$product_id;
                $assign->ref_id=$request->lessons;
                $assign->save();
            }
        }else{
            $assignModel=new Assigns;
            $assignModel->product_id=$product_id;
            $assignModel->ref_id=$request->lessons;
            $assignModel->product_type=Badges::ProductName;
            $assignModel->ref_type=Lessons::ProductName;
            $assignModel->school=0;
            $assignModel->save();
        }
        $levels= Levels::all();
        $categories=Categories::all();
        $badges = Badges::paginate(config('lms.pagination'));
        $badges->setPath('');
        return view('badges.index')->with('badges',$badges)->with('levels',$levels)->with('categories',$categories);
    }


}

?>