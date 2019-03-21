<?php
namespace App\Http\Controllers;

use App\Assigns;
use App\Classes;
use App\Groups;
use App\Helper\NotificationHelper;
use App\Helper\SendEmail;
use App\Helper\SqlHelper;
use App\Helper\UserHelper;
use App\Levels;
use App\Parents;
use Illuminate\Http\Request;
use App\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Response;
use File;


class StudentController extends BaseController
{
    /**
     * Display a listing of the resource.
     * view  students index
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $path='?';
        $modelClasses=Classes::where([]);
        $classes = DB::table('levels')
            ->join('classes', 'classes.level', '=', 'levels.level_id')
            ->select('levels.*', 'classes.*');
        $levels=Levels::where([]);
        $groups=Groups::where([]);
        $query = DB::table('users')
            ->select('users.*','classes.*','levels.*','users.level as userlevel')
            ->leftJoin('classes', 'users.class', '=', 'classes.class_id')
            ->leftJoin('levels', 'users.level', '=', 'levels.level_id')
            ->where("permession",Users::USER_STUDENT);

//        SqlHelper::printSql($query);
//        exit();
        if(Users::isParent()){
            $ides=Auth::user()->students->pluck('student_id');
            $groupsid=DB::table('assigns')
                ->where('product_type','group')
                ->where('ref_type','student')
                ->whereIn('ref_id',$ides)
                ->get()
                ->pluck('product_id');
            $groups->whereIn('group_id',$groupsid);
            $query->whereIn('userid',$ides);
            $modelClasses->whereIn('class_id',Auth::user()->students->pluck('student_id.class'));
            if (Input::has('group')&& $request->group!= -1){

                $ides=Assigns::where('product_id',$request->group)
                    ->where('product_type','group')
                    ->where('ref_type','student')->get()
                    ->pluck('ref_id');

                $query->orWhereIn('users.userid', $ides);
                $path.='group='.$request->group;

            }
            $idlevels=DB::table('parents')->select('users.level')->join('users','users.userid','parents.student_id')->where('parents.parent_id',Auth::user()->userid)->get()->pluck('users.level');
            $levels->whereIn('level_id',$idlevels);
        }
        if(Users::isStudent()){
            $groupsid=DB::table('assigns')
                ->where('product_type','group')
                ->where('ref_type','student')
                ->where('ref_id',Auth::user()->userid)
                ->get()
                ->pluck('product_id');
            $groups->whereIn('group_id',$groupsid);
            $levels->where('level_id', Auth::user()->level);

        }
        if (Users::isTeacher()){

            $qerayser=DB::table('schedule')
                ->join('classes','classes.class_id','=','schedule.class')
                ->where('teacher',Auth::user()->userid)->distinct()->get();
            $classids=$qerayser->pluck('class')->toArray();
            $modelClasses->whereIn('class_id',$classids);
        }


        //commet to allow teacher show all student for group issues and teacher must see all levels and group in students page for filtering - By Hussam
//       if(Users::isTeacher()){
//           $groups->where('teacher',Auth::user()->userid);
//           $claseids= DB::table('schedule')->select('class')->where('teacher',Auth::user()->userid)->distinct();
//           $idLevels= DB::table('schedule')->select('classes.level')
//               ->join('classes', 'classes.class_id', '=', 'schedule.class')
//               ->where('schedule.teacher',Auth::user()->userid)
//               ->distinct()
//               ->get()
//               ->pluck('level')
//               ->unique('level')
//               ->values();
//           $query->whereIn('users.class',$claseids);
//           $levels->whereIn('level_id', $idLevels);
//       }

        if (Input::has('level') && $request->level!= -1){
            $query->where('users.level', '=',$request->level);
            $modelClasses->where('level',$request->level);
            $path.='level='.$request->level;
        }
        if (Input::has('parent') && $request->parent!= -1){
            $ides=Users::find($request->parent)->students->pluck('student_id');
            $query->whereIn('userid',$ides);
        }
        if (Input::has('class')&& $request->class!= -1){
            $query->where('users.class', $request->class);
            $path.='class='.$request->class;
        }
        if (Input::has('group')&& $request->group!= -1){

            $ides=Assigns::where('product_id',$request->group)
                ->where('product_type','group')
                ->where('ref_type','student')->get()
                ->pluck('ref_id');

            $query->whereIn('users.userid', $ides);
            $path.='group='.$request->group;

        }
        if (Input::has('search')&& $request->search!== null){
            $search= $request->search;
            $query->where(function($query) use ($search){
                $query->orwhere('users.fullname', 'like','%' . trim($search).'%')
                    ->orwhere('users.phone', 'like','%' . trim($search).'%')
                    ->orwhere('users.email','like', '%' .trim($search).'%' )
                ;
            });
            $path.='search='.$request->search;
        }
        if (Input::has('orderby')&& $request->orderby !==NULL && Input::has('descask')&&$request->descask !==NULL ){
            $orderby= $request->orderby;
            $descask=$request->descask;
            $query->orderBy($orderby, $descask);
            $path.='orderby='.$orderby;
            $path.='descask='.$descask;
        };

        $students=$query->paginate(config('lms.pagination'));
        $groups=$groups->get();
        $students->setPath($path);
        $levels=$levels->get();
        $classes=$classes->get();
        $modelClasses=$modelClasses->get();

        return view('students.index')->with("groups",$groups)->with("students",$students)->with("classes",$classes)->with("levels",$levels)->with('modelClasses',$modelClasses);
    }


    /**
     * create by hasan kiwan
     * show input for add new stuendt
     * @return $this
     */
    public function newuser(){
        $admin="";
        $parents= Users::where("permession",Users::USER_PARENT)->get();
        $classes = DB::table('levels')
            ->join('classes', 'classes.level', '=', 'levels.level_id')
            ->select('levels.*', 'classes.*')
            ->get();
        $levels=Levels::all();
        $data=[
            "admin"=>$admin,
            "classes"=>$classes,
            'parents'=>$parents,
            "levels"=> $levels,
        ];
        return view('students.add')->with($data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $studentModel=new Users();
        $modelClasses=Classes::all();
        $rules = array(
            'uname' => 'required|unique:users',
            'fullname' => 'required',
            'email'=>'email|nullable|unique:users',
            'password' => 'required',
            'confirm_password'=>'same:password',
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

        $studentModel->uname=$request->uname;
        $studentModel->password=md5($request->password);
        //$studentModel->avatar
        $studentModel->permession=Users::USER_STUDENT;
        $studentModel->email=$request->email;
        $studentModel->fullname=$request->fullname;
        $studentModel->status=1;
        $studentModel->phone=$request->phone;
        $studentModel->birthdate=$request->birthdate;
        $studentModel->class=$request->class;
        $studentModel->level=$request->level;
        $studentModel->created_at=date('Y-m-d h:m:s');
        $path="images/user.png";
        if (request()->hasFile('avatar')) {
            $avatar = md5(uniqid(rand(), true)).'.'.$request->avatar->extension();
            $request->avatar->move(config('lms.path_avatar'),$avatar);
            $studentModel->avatar=config('lms.path_avatar').'/'.$avatar;
        }else{
            $studentModel->avatar=$path;
        }

        $studentModel->save();
        NotificationHelper::n_adduser('student','/students/filter?search='.$studentModel->email,$studentModel->userid);
        $accept=UserHelper::createUserOnSiteManhal($studentModel,$request->password);
        if($accept['code']==502){
            return Response::json([
                'errors' => ['not created in manhl.com']
            ], 201);
        }else{
            $studentModel->manhal_id=$accept['manhal_id'];
            $studentModel->save();
        }

        if(!empty($request->parent)){
            $parentModel = new Parents;
            $parentModel->parent_id=$request->parent;
            $parentModel->student_id=$studentModel->userid;
            $parentModel->created_at=date('Y-m-d');
            $parentModel->save();
        };

        $students = DB::table('users')
            ->select('users.*','classes.*','levels.*','users.level as userlevel')
            ->leftJoin('classes', 'users.class', '=', 'classes.class_id')
            ->leftJoin('levels', 'users.level', '=', 'levels.level_id')
            ->where("permession",Users::USER_STUDENT)
            ->paginate(config('lms.pagination'));
        $levels=Levels::all();
        $clasesses=Classes::all();
        $classes = DB::table('levels')
            ->join('classes', 'classes.level', '=', 'levels.level_id')
            ->select('levels.*', 'classes.*')
            ->get();
        $groups=Groups::all();
        $students->setPath('');
        return view('students.index')->with("groups",$groups)->with("students",$students)->with("classes",$classes)->with("levels",$levels)->with("clasesses",$clasesses)->with('modelClasses',$modelClasses)->renderSections()['content'];
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($lang,$id){
        $parents= Users::where("permession",Users::USER_PARENT)->get();
        $student= Users::where("userid",$id)->get();
        $classes = DB::table('levels')
            ->join('classes', 'classes.level', '=', 'levels.level_id')
            ->select('levels.*', 'classes.*')
            ->get();
        $levels=Levels::all();
        $data=[
            "student"=>$student,
            "classes"=>$classes,
            'parents'=>$parents,
            'levels'=>$levels,
        ];
        return view('students.edit')->with($data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($lang,$id){

        $student= Users::where("userid",$id)->first();
        $modelClasses=Classes::all();
        $rules = array(
            'uname' => 'required|unique:users,uname,'.$student->userid.',userid',
            'fullname' => 'required',
            'email'=>'email|unique:users,email,'.$student->userid.',userid',
            'avatar'=>'image|mimes:jpg,jpeg,png,gif,bmp',

        );
        $input = request()->all();
        $student->uname=$input["uname"];
        $student->fullname=$input["fullname"];
        $student->email=$input["email"];
        $student->level=$input["level"];
        $student->class=$input["class"];
        $student->phone=$input["phone"];
        $student->birthdate=$input["birthdate"];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            $errors=$validator->errors()->all();
            return Response::json([
                'errors' => $errors
            ], 201);
        }
        $path="images/user.png";
        if (request()->hasFile('avatar')) {
            if(File::exists($student->avatar) && $student->avatar!=$path ) {
                File::delete($student->avatar);
            }
            $avatar = md5(uniqid(rand(), true)).'.'.$input["avatar"]->extension();
            $input["avatar"]->move(config('lms.path_avatar'),$avatar);
            $student->avatar=config('lms.path_avatar').'/'.$avatar;
        }
        $parentModel= Parents::where("student_id",$student->userid)->first();
        if(empty($parentModel) ){
            if(!empty($input["parent"])) {
                $parentModel = new Parents;
                $parentModel->parent_id = $input["parent"];
                $parentModel->student_id = $student->userid;
                $parentModel->created_at = date('Y-m-d');
                $parentModel->save();

            }
        }else{
            if(isset($input["parent"])){
                $parentModel->parent_id=$input["parent"];
                $parentModel->student_id=$student->userid;
                $parentModel->created_at=date('Y-m-d');
                $parentModel->save();
            }

        }
        $student->save();
        NotificationHelper::n_updateUser('student','/students/filter?search='.$student->email,$student->userid);
        $classes = DB::table('levels')
            ->join('classes', 'classes.level', '=', 'levels.level_id')
            ->select('levels.*', 'classes.*')
            ->get();
        $levels=Levels::all();
        $clasesses=Classes::all();
        $students = DB::table('users')
            ->select('users.*','classes.*','levels.*','users.level as userlevel')
            ->leftJoin('classes', 'users.class', '=', 'classes.class_id')
            ->leftJoin('levels', 'users.level', '=', 'levels.level_id')
            ->where("permession",Users::USER_STUDENT)
            ->paginate(config('lms.pagination'));
        $groups=Groups::all();
        $students->setPath('');
        return view('students.index')->with("groups",$groups)->with("students",$students)->with('classes',$classes)->with("levels",$levels)->with("clasesses",$clasesses)->with('modelClasses',$modelClasses)->renderSections()['content'];
    }

    public function delete($lang,$id){
        $modelClasses=Classes::all();
        $input = request()->all();
        $student= Users::where("userid",$id)->delete();
        $levels=Levels::all();
        $clasesses=Classes::all();
        $students = DB::table('users')
            ->select('users.*','classes.*','levels.*','users.level as userlevel')
            ->leftJoin('classes', 'users.class', '=', 'classes.class_id')
            ->leftJoin('levels', 'users.level', '=', 'levels.level_id')
            ->where("permession",Users::USER_STUDENT)
            ->paginate(config('lms.pagination'));
        $groups=Groups::all();
        $students->setPath('');
        return view('students.index')->with("groups",$groups)->with("students",$students)->with("levels",$levels)->with("clasesses",$clasesses)->with('modelClasses',$modelClasses)->renderSections()['content'];
    }



    /*
     * show parents chiled
     */
    public function childsParent($lang,$idParent){
        $chiledIds= DB::table('parents')->where('parent_id',$idParent)->select('parents.student_id')->get();
        $chiledIdsArray=[];
        foreach ($chiledIds as $chiledId){
            $chiledIdsArray[]=$chiledId->student_id;
        }

        $classes = DB::table('levels')
            ->join('classes', 'classes.level', '=', 'levels.level_id')
            ->select('levels.*', 'classes.*')
            ->get();
        $levels=Levels::all();
        $clasesses=Classes::all();

        $students = DB::table('users')
            ->leftJoin('classes', 'users.class', '=', 'classes.class_id')
            ->leftJoin('levels', 'users.level', '=', 'levels.level_id')
            ->where("permession",Users::USER_STUDENT)
            ->whereIn('userid',$chiledIdsArray)
            ->paginate(config('lms.pagination'));
        $groups=Groups::all();
        $students->setPath('');
        return view('students.index')->with("groups",$groups)->with("students",$students)->with("classes",$classes)->with("levels",$levels)->with("clasesses",$clasesses);

    }


    /*
     * show all teacher in special level
     */
    public function showStudentsLevel($lang,$levelId){
        $classes = DB::table('levels')
            ->join('classes', 'classes.level', '=', 'levels.level_id')
            ->select('levels.*', 'classes.*')
            ->get();
        $levels=Levels::all();
        $clasesses=Classes::all();

        $students = DB::table('users')
            ->leftJoin('classes', 'users.class', '=', 'classes.class_id')
            ->leftJoin('levels', 'users.level', '=', 'levels.level_id')
            ->where('users.level',$levelId)
            ->where("permession",Users::USER_STUDENT)
            ->paginate(config('lms.pagination'));
        $groups=Groups::all();
        $students->setPath('');
        return view('students.index')->with("groups",$groups)->with("students",$students)->with("classes",$classes)->with("levels",$levels)->with("clasesses",$clasesses);
    }



    public function restPsswordStudent($lang,$id){
        $user=Users::find($id);
        return view('students.rest-password')->with('user',$user);
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
//            $parent=$user->parent->parentInfo;
//            if(isset($parent) && $parent != null){
//                SendEmail::sendEmailRestPass($parent,$request->password);
//            }
        }
        NotificationHelper::n_changePasswordUser('/userprofile',$user->userid);
        $students = DB::table('users')
            ->select('users.*','classes.*','levels.*','users.level as userlevel')
            ->leftJoin('classes', 'users.class', '=', 'classes.class_id')
            ->leftJoin('levels', 'users.level', '=', 'levels.level_id')
            ->where("permession",Users::USER_STUDENT)
            ->paginate(config('lms.pagination'));
        $levels=Levels::all();
        $modelClasses=Classes::all();
        $clasesses=Classes::all();
        $classes = DB::table('levels')
            ->join('classes', 'classes.level', '=', 'levels.level_id')
            ->select('levels.*', 'classes.*')
            ->get();
        $groups=Groups::all();
        $students->setPath('');
        return view('students.index')->with("groups",$groups)->with("students",$students)->with("classes",$classes)->with("levels",$levels)->with("clasesses",$clasesses)->with('modelClasses',$modelClasses)->renderSections()['content'];
    }

    public function getClassesLevel($lang,$id){
        if($id==-1){
            return response()->json(['class'=>null]);
        }
        $level=Levels::find($id);
        return response()->json(['class'=>$level->classesInfo]);
    }

    public function getStudentGroup($lang,$groupId)
    {

        $classes = DB::table('levels')
            ->join('classes', 'classes.level', '=', 'levels.level_id')
            ->select('levels.*', 'classes.*')
            ->get();
        $levels=Levels::all();
        $clasesses=Classes::all();
        $qrery=DB::table('assigns')
            ->join('users', 'assigns.ref_id', '=', 'users.userid')
            ->join('classes', 'users.class', '=', 'classes.class_id')
            ->join('levels', 'users.level', '=', 'levels.level_id')
            ->select('avatar','userid','phone','birthdate','uname','email','fullname','levels.ltitle_en','levels.ltitle_ar','classes.ctitle_en','classes.ctitle_ar')
            ->where('product_type','group')
            ->where('permession','!=',Users::USER_TEACHER)
            ->where('product_id',$groupId);
        $students=$qrery->paginate(config('lms.pagination'));
        $groups=Groups::all();
        $students->setPath('');
        return view('students.index')->with("groups",$groups)->with("students",$students)->with("classes",$classes)->with("levels",$levels)->with("clasesses",$clasesses);
    }

}
