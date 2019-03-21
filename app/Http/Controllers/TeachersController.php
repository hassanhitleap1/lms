<?php
namespace App\Http\Controllers;
use App\Helper\NotificationHelper;
use App\Helper\SendEmail;
use App\Helper\SqlHelper;
use App\Helper\UserHelper;
use App\Levels;
use Illuminate\Support\Facades\Auth;
use Response;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use File;



class TeachersController extends BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(){

      $query= Users::where("permession",Users::USER_TEACHER);
      $path='';
//commented below lines to show all teachers for any teacher so teacher can send message for any teacher in school - By Hussam
//      if(Users::isTeacher()){
//          $query->where('userid',Auth::user()->userid);
//      }
      if(Users::isStudent()){
        $teachersid=DB::table('schedule')->where('class',Auth::user()->class)->select('teacher')->get()->pluck('teacher');
        $query->whereIn('userid',$teachersid);
          $query->orWhere('level' ,Auth::user()->class);
      }
      if(Users::isParent()){
          $teachersid=DB::table('parents')
              ->select('schedule.teacher')
              ->join('users','users.userid','=','parents.student_id')
              ->join('schedule','schedule.class','=','users.class')
              ->where('parents.parent_id',Auth::user()->userid)
              ->distinct()
              ->get()
              ->pluck('teacher')
              ->toArray();
          $query->where(function ($q) use ($teachersid){
              $q->orwhereIn('userid', $teachersid);
              $q->orwhereIn('class' ,Auth::user()->students->pluck('studentInfo.class')->toArray());
          });

      }
      if (request()->isMethod('GET')) {
          $request = request()->all();
          if (isset($request['class']) && $request['class'] != '') {
              $teachersId=DB::table('schedule')
                  ->where('class' , $request['class'])
                  ->get()->pluck('teacher');
              $query->whereIn('userid',$teachersId);
              $query->orWhere('class',$request['class']);
              $path.='class='.$request['class'];
          }
          if (isset($request['level']) && $request['level'] != '') {

              $teachersId=DB::table('schedule')
                  ->select('schedule.teacher')
                  ->join('classes','classes.class_id','schedule.class')
                  ->where('classes.level' , $request['level'])
                  ->distinct()
                  ->get()->pluck('teacher');


              $query->whereIn('userid',$teachersId);
              $path.='level='.$request['level'];
          }
          if (isset($request['search']) && $request['search']!== null){
              $search= $request['search'];
              $query->where(function($query) use ($search){
                  $query->orwhere('users.email','like', '%' .$search.'%' )
                      ->orwhere('users.fullname', 'like','%' . $search.'%')
                      ->orwhere('users.phone', 'like','%' . $search.'%')
                  ;
              });
              $path.='?search='.$request['search'];
          };
          if (isset($request['orderby'])&& $request['orderby'] !==NULL && isset($request['descask'])&&$request['descask']!==NULL ){
              $orderby=$request['orderby'] ;
              $descask=$request['descask'];
              $path.='orderby='.$request['orderby'];
              $path.='descask='.$request['descask'];
              $query->orderBy($orderby, $descask);
          };
      }

      $teachers= $query->paginate(config('lms.pagination'));
      $teachers->setPath($path);
    return view('teachers.index')->with("teachers",$teachers);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    $teacherModel=new Users();
 
    $rules = array(
        'uname' => 'required|unique:users',
        'fullname' => 'required',
        'email'=>'email|nullable|unique:users',
        'confirm_password'=>'same:password',
        'password' => 'required',
        'avatar'=>'image|mimes:jpg,jpeg,png,gif,bmp',
      );

      $validator = Validator::make(Input::all(), $rules);

      if ($validator->fails())
      {
        $admin="";
        $classes = DB::table('levels')
            ->join('classes', 'classes.level', '=', 'levels.level_id')
            ->select('levels.*', 'classes.*')
            ->get();
        $data=[
            "admin"=>$admin,
            "classes"=>$classes
        ];
        $errors=$validator->errors()->all();
        return Response::json([
            'errors' => $errors
        ], 201);
      }

      //check if homeroom used by another teacher - By Hussam
      if($request->class!="-1" && $request->level!="-1" && Users::where("permession",Users::USER_TEACHER)->where('level',$request->level)->where('class',$request->class)->count()>0){
          return Response::json([
              'errors' => [\Lang::get('lang.homeroomexist')]
          ], 201);
      }



      $teacherModel->uname=$request->uname;
    $teacherModel->password=md5($request->password);;
    //$teacherModel->avatar
    $teacherModel->permession=4;
    $teacherModel->email=$request->email;
    $teacherModel->fullname=$request->fullname;
    $teacherModel->status=1;
    $teacherModel->phone=$request->phone;
    $teacherModel->birthdate=$request->birthdate;
    $teacherModel->class=$request->class;
    $teacherModel->level=$request->level;
    $teacherModel->created_at=date('Y-m-d h:m:s');
      $path="images/user.png";
      if (request()->hasFile('avatar')) {
          $avatar = md5(uniqid(rand(), true)).'.'.$request->avatar->extension();
          $request->avatar->move(config('lms.path_avatar'),$avatar);
          $teacherModel->avatar=config('lms.path_avatar').'/'.$avatar;
      }else{
          $teacherModel->avatar=$path;
      }
    $teacherModel->save();
    NotificationHelper::n_adduser('teacher','/teachers/?search='.$teacherModel->email,$teacherModel->userid);
      $accept=UserHelper::createUserOnSiteManhal($teacherModel,$request->password);
      if($accept['code']==502){
          return Response::json([
              'errors' => ['not created in manhl.com']
          ], 201);
      }else{
          $teacherModel->manhal_id=$accept['manhal_id'];
          $teacherModel->save();
      }
    $teachers= Users::where("permession",4)->paginate(config('lms.pagination'));
    $teachers->setPath('');
    return view('teachers.index')->with("teachers",$teachers)->renderSections()['content'];
  }

  /**
   * Display the specified resource.


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($lang,$id){
    $teacher= Users::where("userid",$id)->get();
    $classes = DB::table('levels')
        ->join('classes', 'classes.level', '=', 'levels.level_id')
        ->select('levels.*', 'classes.*')
        ->get();
      $levels=Levels::all();
    $data=[
        "teacher"=>$teacher,
        "classes"=>$classes,
        'levels'=>$levels,
    ];
    
    return view('teachers.edit')->with($data);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($lang,$id){
    $input = request()->all();
    $teacher= Users::where("userid",$id)->first();
    $rules = array(
        'uname' => 'required|unique:users,uname,'.$teacher->userid.',userid',
        'fullname' => 'required',
        'email'=>'email|unique:users,email,'.$teacher->userid.',userid',
        'avatar'=>'image|mimes:jpg,jpeg,png,gif,bmp',

    );

    $teacher->uname=$input["uname"];
    $teacher->fullname=$input["fullname"];
    $teacher->email=$input["email"];
    $teacher->phone=$input["phone"];
    $teacher->birthdate=$input["birthdate"];
    $teacher->class=$input["class"];
    $teacher->level=$input["level"];
    
    $validator = Validator::make(Input::all(), $rules);

    if ($validator->fails())
    {
      $errors=$validator->errors()->all();
      return Response::json([
          'errors' => $errors
      ], 201);
    }
      //check if homeroom used by another teacher - By Hussam
      if($input["class"]!="-1" && $input["level"]!="-1" && Users::where("permession",Users::USER_TEACHER)->where('level',$input["level"])->where('class',$input["class"])->where('userid',"<>",$id)->count()>0){
          return Response::json([
              'errors' => [\Lang::get('lang.homeroomexist')]
          ], 201);
      }

      $path="images/user.png";
      if (request()->hasFile('avatar')) {
          if(File::exists($teacher->avatar) && $teacher->avatar!=$path ) {
              File::delete($teacher->avatar);
          }
          $avatar = md5(uniqid(rand(), true)).'.'.$input["avatar"]->extension();
          $input["avatar"]->move(config('lms.path_avatar'),$avatar);
          $teacher->avatar=config('lms.path_avatar').'/'.$avatar;
      }
    $teacher->save();
      NotificationHelper::n_updateUser('teacher','/teachers/?search='.$teacher->email,$teacher->userid);
      $teachers= Users::where("permession",4)->paginate(config('lms.pagination'));
      $teachers->setPath('');
      return view('teachers.index')->with("teachers",$teachers)->renderSections()['content'];
  }

  public function delete($lang,$id){
    $input = request()->all();
    $teacher= Users::where("userid",$id)->delete();
      $teachers= Users::where("permession",4)->paginate(config('lms.pagination'));
      $teachers->setPath('');
      return view('teachers.index')->with("teachers",$teachers)->renderSections()['content'];
  }

  public function newuser(){
    $admin="";
    $classes = DB::table('levels')
        ->join('classes', 'classes.level', '=', 'levels.level_id')
        ->select('levels.*', 'classes.*')
        ->get();
    $levels=Levels::all();
    $data=[
        "admin"=>$admin,
        "classes"=>$classes,
        'levels'=>$levels,
    ];
    return view('teachers.add')->with($data);
    
  }

    /**
     * filter the specified studets.
     *
     * @return Response
     */
    public function filter(Request $request){

        $query = Users::where("permession",Users::USER_TEACHER);

        if (Input::has('search')&& $request->search!== null){
            $search= $request->search;
            $query->where(function($query) use ($search){
                $query->orwhere('users.email','like', '%' .$search.'%' )
                    ->orwhere('users.fullname', 'like','%' . $search.'%')
                    ->orwhere('users.phone', 'like','%' . $search.'%')
                ;
            });
        };
        if (Input::has('orderby')&& $request->orderby !==NULL && Input::has('descask')&&$request->descask !==NULL ){
            $orderby= $request->orderby;
            $descask=$request->descask;
            $query->orderBy($orderby, $descask);
        };

        $teachers= $query->paginate(config('lms.pagination'));
        $teachers->setPath('');
        return view('teachers.index')->with("teachers",$teachers);
    }




    public function showTeachersLevel($lang,$levelId){
        $teachers= Users::where("permession",Users::USER_TEACHER)->where('level',$levelId)->paginate(config('lms.pagination'));

        return view('teachers.index')->with("teachers",$teachers);
    }




    public function restPsswordTeacher($lang,$id){
        $user=Users::find($id);
        return view('teachers.rest-password')->with('user',$user);
    }
    /*
     * rest password
     */
    public function restPssword($lang,$id,Request $request){
        $user=Users::find($id);
        $rules = array(
            'password'=>'required',
            'confirm_password'=>'required|same:password',
        );
        $validator = Validator::make(Input::all(), $rules);


        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return Response::json([
                'errors' => $errors
            ], 201);

        }

        $user->password=md5($request->password);

        $user->save();
        if( ! in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ) ) ){
            SendEmail::sendEmailRestPass($user,$request->password);
        }
        NotificationHelper::n_changePasswordUser('/userprofile',$user->userid);
        $teachers= Users::where("permession",4)->paginate(config('lms.pagination'));
        $teachers->setPath('');
        return view('teachers.index')->with("teachers",$teachers)->renderSections()['content'];
    }
}

?>