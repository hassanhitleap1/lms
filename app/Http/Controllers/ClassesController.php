<?php

namespace App\Http\Controllers;

use App\Helper\MessageHelper;
use App\Helper\SqlHelper;
use Illuminate\Routing\Controller;
use App\Classes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Levels;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Response;




class ClassesController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $path='';
        $orderby='created_at';
        $descask='ASC';

        $teachers= Users::where('permession',Users::USER_TEACHER);
        $levels=Levels::where([]);

//edited by hussam - add count of student
        $query=  DB::table('classes')
            ->select('classes.class_id','classes.created_at','classes.ctitle_en',
                'classes.ctitle_ar','users.fullname','levels.level_id','levels.ltitle_ar',
                'levels.ltitle_en','users.userid',DB::raw("COUNT(DISTINCT  users.userid) as students_count"),
                'u.fullname as homeroom','u.userid as homeroomid')
            ->leftJoin('levels', 'levels.level_id', '=', 'classes.level')
           // ->leftJoin('users' , 'users.class','=','classes.class_id');
           ->leftJoin('users as u', 'u.class', '=', 'classes.class_id')
            ->leftJoin('users', function($join){
                $join->on('users.class', '=', 'classes.class_id');
                $join->where('users.permession', '=', 5);
            });

          //  ->leftJoin("assigns",function($join){
//                $join->on('assigns.product_id' ,'=', 'classes.class_id')
//                    ->where('assigns.product_type','class');
//            })

        //$homeRoomTeacher=Users::where('users.permession',4)->where('users.class','classes.class_id');

        if(!empty($clss_id=request()->input('class'))){
            $query->where('classes.class_id',$clss_id);
        }
        if(Users::isTeacher()){
            $claseids= DB::table('schedule')->select('class')->where('teacher',Auth::user()->userid)->distinct();
            $query->whereIn('classes.class_id',$claseids);
            $teachers->where('userid',Auth::user()->userid);
            $idLevels= DB::table('schedule')->select('classes.level')
                ->join('classes', 'classes.class_id', '=', 'schedule.class')
                ->where('schedule.teacher',Auth::user()->userid)
                ->distinct()
                ->get()
                ->pluck('level')
                ->toArray();
            $levels->whereIn('level_id', $idLevels);
            $query->orWhere('classes.class_id',Auth::user()->class);
        }
        if (Input::has('level') && $request->level!=-1){
            $query->where('levels.level_id', '=',$request->level);
            $path.='?level='.$request->level;
        }
        if (Input::has('teacher') && $request->teacher!=-1){
            $claseids= DB::table('schedule')->select('class')->where('teacher',$request->teacher)->get()->pluck('class');
            $query->whereIn('classes.class_id',$claseids);
            $classid=Users::where('userid',$request->teacher)->first()->class;
            $query->orWhere('classes.class_id',$classid);
            $path.='&teacher='.$request->teacher;
        }
        if (Input::has('parent') && $request->parent!=-1){
            $claseids= DB::table('parents')->select('users.class')->join('users','users.userid','parents.student_id')->where('parent_id',$request->parent)->get()->pluck('class')->toArray();
            $query->whereIn('classes.class_id',$claseids);
            $path.='&parent='.$request->parent;
        }
        if (Users::isParent()){
            $claseids= DB::table('parents')->select('users.class')->join('users','users.userid','parents.student_id')->where('parent_id',Auth::user()->userid)->get()->pluck('class')->toArray();;
            $query->whereIn('classes.class_id',$claseids);
        }
        if (Input::has('orderby')&& $request->orderby !==NULL && Input::has('descask')&&$request->descask !==NULL ){
            $orderby= $request->orderby;
            $descask=$request->descask;
            $query->orderBy($orderby, $descask);
            $path.='&orderby='.$request->orderby;
            $path.='&descask='.$request->descask;
        };

        //group by classid - By Hussam
        $query->groupBy("classes.class_id");
        $teachers=$teachers->get();
        $levels=$levels->get();
        $classes=$query->paginate(config('lms.pagination'));
        $classes->setPath($path);
        //get homeroom name for each class - By Hussam
//        foreach($classes as $class){
//            $teacherHomeroom=Users::where('permession',Users::USER_TEACHER)->where("class",$class->class_id)->first();
//            if($teacherHomeroom){
//                $class->homeroom=$teacherHomeroom->fullname;
//            }else{
//                $class->homeroom="";
//            }
//        }
        return view('classes.index')->with("classes",$classes)->with("levels",$levels)->with('teachers',$teachers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $levels=Levels::all();
        $teachers= Users::all()->where('permession',Users::USER_TEACHER);

        return view('classes.add')->with(['levels'=>$levels,'teachers'=>$teachers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {

        $rules = array(
            'name_en' => 'required',
            'name_ar' => 'required',
            'level'=> 'required',
            'teacher'=> 'required',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()){
            $errors=$validator->errors()->all();
            return Response::json([
                'errors' => $errors
            ], 201);
        }

        $classModel= new  Classes;
        $classModel->ctitle_en=$request->name_en;
        $classModel->ctitle_ar=$request->name_ar;
        $classModel->level=$request->level;
        $classModel->school=0;
        $classModel->created_at=date('Y-m-d h:m:h');
        $model=$classModel->save();

//        DB::table('assigns')->insert([
//            'product_id' => $classModel->class_id,
//            'ref_id'=> $request->teacher,
//            'product_type'=>'class',
//            'ref_type'=>'teacher',
//            'school'=>0,
//            'created_at'=>date('Y-m-d h:m:s'),
//        ]);

        $assigns= DB::table('users')
            ->where('permession',Users::USER_TEACHER)
            ->where('class' , $classModel->class_id)
            ->update(['class' => -1]);

        $homeroom= DB::table('users')
            ->where('userid',$request->teacher)
            ->update(['class' => $classModel->class_id]);


        $levels=Levels::all();
        $teachers= Users::all()->where('permession',Users::USER_TEACHER);
        $query=  DB::table('classes')
            ->select('classes.class_id','classes.created_at','classes.ctitle_en',
                'classes.ctitle_ar','users.fullname','levels.level_id','levels.ltitle_ar',
                'levels.ltitle_en','users.userid',DB::raw("COUNT(DISTINCT  users.userid) as students_count"),
                'u.fullname as homeroom','u.userid as homeroomid')
            ->leftJoin('levels', 'levels.level_id', '=', 'classes.level')
            // ->leftJoin('users' , 'users.class','=','classes.class_id');
            ->leftJoin('users as u', 'u.class', '=', 'classes.class_id')
            ->leftJoin('users', function($join){
                $join->on('users.class', '=', 'classes.class_id');
                $join->where('users.permession', '=', 5);
            });
        $query->groupBy("classes.class_id");
        $classes=$query->paginate(config('lms.pagination'));

        $classes->setPath('');
        return view('classes.index')->with("classes",$classes)->with("levels",$levels)->with('teachers',$teachers)->renderSections()['content'];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($lang,$id)
    {
        $query=  DB::table('classes')
            ->select('classes.class_id','classes.created_at',
                'classes.ctitle_en','classes.ctitle_ar','assigns.ref_id' ,
                'users.fullname','levels.ltitle_ar','levels.ltitle_en',
                'users.userid','classes.level','u.userid as homeroomid')
            ->leftJoin('levels', 'levels.level_id', '=', 'classes.level')
            ->leftJoin('users as u', 'u.class', '=', 'classes.class_id')
            ->leftJoin("assigns",function($join){
                $join->on('assigns.product_id' ,'=', 'classes.class_id')
                    ->where('assigns.product_type','class');
            })->leftJoin('users' , 'users.userid','=','assigns.ref_id');

        $class=$query->where('classes.class_id',$id)->first();
        $levels=Levels::all();
        $teachers= Users::all()->where('permession',Users::USER_TEACHER);
        return view('classes.edit')->with(['class'=>$class,'levels'=>$levels,'teachers'=>$teachers]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $lang,$id)
    {
        $rules = array(
            'name_en' => 'required',
            'name_ar' => 'required',
            'level'=> 'required',
            'teacher'=> 'required',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()){
            $errors=$validator->errors()->all();
            return Response::json([
                'errors' => $errors
            ], 201);
        }
        $classModel= Classes::where("class_id",$request->id)->first();
        $classModel->ctitle_en=$request->name_en;
        $classModel->ctitle_ar=$request->name_ar;
        $classModel->level=$request->level;
        $classModel->school=0;
        $classModel->created_at=date('Y-m-d h:m:h');
        $classModel->save();
//        $assigns= DB::table('assigns')
//            ->where('product_id', $classModel->class_id)
//            ->where('product_type','class')
//            ->update(['ref_id' => $request->teacher,
//                'updated_at'=>date('Y-m-d h:m:s')]);
//        if($assigns==0){
//            DB::table('assigns')->insert([
//                'product_id' => $classModel->class_id,
//                'ref_id'=> $request->teacher,
//                'product_type'=>'class',
//                'ref_type'=>'teacher',
//                'school'=>0,
//                'created_at'=>date('Y-m-d h:m:s'),
//            ]);
//        }

        $assigns= DB::table('users')
            ->where('permession',Users::USER_TEACHER)
            ->where('class' , $classModel->class_id)
            ->update(['class' => -1]);

        $homeroom= DB::table('users')
            ->where('userid',$request->teacher)
            ->update(['class' => $classModel->class_id]);

        $levels=Levels::all();
        $teachers= Users::all()->where('permession',Users::USER_TEACHER);

        $query=  DB::table('classes')
            ->select('classes.class_id','classes.created_at','classes.ctitle_en',
                'classes.ctitle_ar','users.fullname','levels.level_id','levels.ltitle_ar',
                'levels.ltitle_en','users.userid',DB::raw("COUNT(DISTINCT  users.userid) as students_count"),
                'u.fullname as homeroom','u.userid as homeroomid')
            ->leftJoin('levels', 'levels.level_id', '=', 'classes.level')
            // ->leftJoin('users' , 'users.class','=','classes.class_id');
            ->leftJoin('users as u', 'u.class', '=', 'classes.class_id')
            ->leftJoin('users', function($join){
                $join->on('users.class', '=', 'classes.class_id');
                $join->where('users.permession', '=', 5);
            });
        $query->groupBy("classes.class_id");
        $classes=$query->paginate(config('lms.pagination'));
        $classes->setPath('');
        return view('classes.index')->with("classes",$classes)->with("levels",$levels)->with('teachers',$teachers)->renderSections()['content'];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($lang,$id)
    {

        $class=DB::table('classes')->where('class_id', '=', $id)->delete();

        if($class){
            $assigns= DB::table('assigns')->where('product_id', '=', $id)
                ->where('product_type', 'class')
                ->delete();
        }
        $levels=Levels::all();
        $teachers= Users::all()->where('permession',Users::USER_TEACHER);
        $query=  DB::table('classes')
            ->select('classes.class_id','classes.created_at','classes.ctitle_en',
                'classes.ctitle_ar','users.fullname','levels.level_id','levels.ltitle_ar',
                'levels.ltitle_en','users.userid',DB::raw("COUNT(DISTINCT  users.userid) as students_count"),
                'u.fullname as homeroom','u.userid as homeroomid')
            ->leftJoin('levels', 'levels.level_id', '=', 'classes.level')
            // ->leftJoin('users' , 'users.class','=','classes.class_id');
            ->leftJoin('users as u', 'u.class', '=', 'classes.class_id')
            ->leftJoin('users', function($join){
                $join->on('users.class', '=', 'classes.class_id');
                $join->where('users.permession', '=', 5);
            });
        $query->groupBy("classes.class_id");
        $classes=$query->paginate(config('lms.pagination'));
        $classes->setPath('');

        return view('classes.index')->with("classes",$classes)->with("levels",$levels)->with('teachers',$teachers)->renderSections()['content'];

    }


    /**
     * @param $lang
     * @param $id
     * @return mixed
     */
    public function show($lang ,$id)
    {
        $levels=Levels::all();
        $teachers= Users::all()->where('permession',Users::USER_TEACHER);
        $query=  DB::table('classes')
            ->select('classes.class_id','classes.created_at','classes.ctitle_en','classes.ctitle_ar','assigns.ref_id' ,'users.fullname','levels.level_id','levels.ltitle_ar','levels.ltitle_en','users.userid')
            ->leftJoin('levels', 'levels.level_id', '=', 'classes.level')
            ->leftJoin("assigns",function($join){
                $join->on('assigns.product_id' ,'=', 'classes.class_id')
                    ->where('assigns.product_type','class');
            })
            ->leftJoin('users' , 'users.userid','=','assigns.ref_id')
            ->where('classes.class_id',$id);


        $classes=$query->paginate(config('lms.pagination'));
        return view('classes.index')->with("classes",$classes)->with("levels",$levels)->with('teachers',$teachers);
    }

    /**
     * @create_by hasan kiwan
     * @param $lang
     * @param $id
     * @return BaseController|\Illuminate\Contracts\View\Factory|\Illuminate\View\View for send message
     * @job show view for send message
     */
    public function sendMessageView($lang,$id){
        return view('classes.sendmessage')->with('id',$id);
    }

    /**
     * @create_by hasan kiwan
     * @param $lang
     * @param $id
     * @param Request $request
     * @job for send message for all user in class selected
     */
    public function sendMessageToClassStu($lang,$id,Request  $request){
        MessageHelper::sendMessageToClassUsers($request->message,$id);
    }

}

?>