<?php 

namespace App\Http\Controllers;

use App\Assigns;
use App\Helper\MessageHelper;
use App\Helper\NotificationHelper;
use App\Helper\SqlHelper;
use App\Http\Controllers\Controller;
use App\Levels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Groups;
use Response;


class GroupsController extends BaseController {

    public $path;
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
      $teachers=Users::where("permession",4)->get();
      $query= $this->getGroups();
      $groups=$query->paginate(config('lms.pagination'));
      $groups->setPath($this->path);
      return view('groups.index')->with("groups",$groups)->with('teachers',$teachers);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
      $query= Users::where('permession',Users::USER_TEACHER);
      if(Users::isTeacher()){
          $query->where('users.userid',Auth::user()->userid);
      }
        $teachers=$query->get();
    
    return view('groups.add')->with('teachers',$teachers);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
      $rules = array(
        'gname' => 'required',
        'gdesc' => 'required',
        'teacher'=> 'required',
      );

      $validator = Validator::make(Input::all(), $rules,Groups::messages());

      if ($validator->fails()){
        $errors=$validator->errors()->all();
        return Response::json([
            'errors' => $errors
        ], 201);
        
      }
        $groupModel= new Groups;
        $groupModel->title_en=$request->gname;
        $groupModel->title_ar=$request->gname;
        $groupModel->description_ar=$request->gdesc;
        $groupModel->description_en=$request->gdesc;
        $groupModel->school=0;
        $groupModel->teacher=$request->teacher;
        $groupModel->created_at=date('Y-m-d h:m:h');
        $groupModel->save();

      $teachers= Users::where("permession",4)->get();
      $query= $this->getGroups();
      $groups=$query->paginate(config('lms.pagination'));
      $groups->setPath($this->path);

      return view('groups.index')->with("groups",$groups)->with('teachers',$teachers)->renderSections()['content'];
  }



  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($lang,$id){
      $group = Groups::find($id);
      $query= Users::where('permession',Users::USER_TEACHER);
      if(Users::isTeacher()){
          $query->where('users.userid',Auth::user()->userid);
      }
      $teachers=$query->get();

    return view('groups.edit')->with('teachers',$teachers)->with('group',$group);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Request $request)
  {

    $rules = array(
      'gname' => 'required',
      'gdesc' => 'required',
      'teacher'=> 'required',
    );

    $validator = Validator::make(Input::all(), $rules);

    if ($validator->fails()){
        $errors=$validator->errors()->all();
        return Response::json([
            'errors' => $errors
        ], 201);
        
      }
       $groupModel= Groups::where("group_id",$request->id)->first();
      $groupModel->title_en=$request->gname;
      $groupModel->description_en=$request->gdesc;
      $groupModel->school=0;
      $groupModel->teacher=$request->teacher;
      $groupModel->created_at=date('Y-m-d h:m:h');
      $groupModel->save();
      $teachers= Users::where("permession",4)->get();
      $query= $this->getGroups();
      $groups=$query->paginate(config('lms.pagination'));
      $groups->setPath($this->path);
    return view('groups.index')->with("groups",$groups)->with('teachers',$teachers)->renderSections()['content'];
  }

  /**
   * Remove the specified resource from storage.
   * @param  int  $id
   * @return Response
   */
    public function destroy($lang,$id)
  {
    $group= Groups::find($id)->delete();

    if($group){
        $assigns=DB::table('assigns')
        ->where('product_id', $id)
        ->where('product_type', 'group')
        ->delete(); 
    }
      $teachers= Users::where("permession",4)->get();
      $query= $this->getGroups();
      $groups=$query->paginate(config('lms.pagination'));
      $groups->setPath($this->path);
      return view('groups.index')->with("groups",$groups)->with('teachers',$teachers)->renderSections()['content'];;
  
  }

    /**
     * @param $lang
     * @param $id
     * @return mixed
     */
    public  function  assignStudent($lang,$id){
    $group= Groups::find((integer)$id);
    $levels=Levels::where([]);
    if(Users::isTeacher()){
        $arrObject=DB::table('schedule')
            ->select('schedule.class','classes.level')
            ->join('classes','classes.class_id','=','schedule.class')
            ->where('schedule.teacher',Auth::user()->userid)
            ->distinct()
            ->get();

        $levels->whereIn('level_id',$arrObject->pluck('level')->toArray());
    }
    $levels=$levels->get();
    $studentsAss=DB::table('assigns')
        ->join('users', 'assigns.ref_id', '=', 'users.userid')
        ->leftJoin('classes', 'users.class', '=', 'classes.class_id')
        ->leftJoin('levels', 'users.level', '=', 'levels.level_id')
        ->select('userid','uname','email','fullname','levels.ltitle_en','levels.ltitle_ar','classes.ctitle_en','classes.ctitle_ar')
        ->where('product_type','group')
        ->where('assigns.ref_type','student')
        ->where('permession','!=',Users::USER_TEACHER)
        ->where('product_id',$group->group_id)
        ->distinct()
        ->paginate(config('lms.pagination'));

      $studentmstbadModels =DB::table('assigns')->select('ref_id')
          ->where('product_id',$id)
          ->where('product_type','group')
          ->where('assigns.ref_type','student')
          ->get();
      $refIdArry=[];
      $refIdArry=$studentmstbadModels->pluck('ref_id')->toArray();
      $students = DB::table('users')
          ->leftJoin('classes', 'users.class', '=', 'classes.class_id')
          ->leftJoin('levels', 'users.level', '=', 'levels.level_id')
          //->leftJoin('assigns', 'users.userid', '=', 'assigns.ref_id')
          ->select('userid','uname','email','fullname','levels.ltitle_en','levels.ltitle_ar','classes.ctitle_en','classes.ctitle_ar')
          ->where("permession",Users::USER_STUDENT)
          ->whereNotIn('users.userid',$refIdArry)
          ->distinct()
          ->paginate(config('lms.pagination'));

    return view('groups.assignstudent')->with('levels',$levels)->with('group',$group)->with('studentsAss',$studentsAss)->with('students',$students);
}

    /**
     * @param $lang
     * @param $id
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getClassesLevel($lang,$id){
          if($id==-1){
              return ['class'=>['id'=>-1,'ctitle_en'=>'-----------','ctitle_ar'=>'-----------']] ;
          }

        $class=Levels::find($id)->classesInfo;
        return response()->json(['class'=>$class]);

    }

    /**
     * @param $lang
     * @param $group_id
     * @param $level_id
     * @param $class_is
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsersClass($lang,$group_id,$level_id,$class_is){
    $studentmstbadModels =DB::table('assigns')->select('ref_id')
        ->where('product_id',$group_id)
        ->where('product_type','group')
        ->where('assigns.ref_type','student')
        ->get();
    $refIdArry=$studentmstbadModels->pluck('ref_id')->toArray();

    $users = DB::table('users')
        ->leftJoin('classes', 'users.class', '=', 'classes.class_id')
        ->leftJoin('levels', 'users.level', '=', 'levels.level_id')
        ->select('userid','uname','email','fullname','levels.ltitle_en','levels.ltitle_ar','classes.ctitle_en','classes.ctitle_ar');
        $users->where([]);
        $users->where("permession",Users::USER_STUDENT);

        //filter with if statment to get all students if filter value is -1 - By Hussam
        if($class_is!=-1){
            $users->where('users.class',$class_is);
        }else if($level_id!=-1){
            $users->where('users.level',$level_id);
        }

        $users=$users->whereNotIn('users.userid',$refIdArry)
        ->paginate(config('lms.pagination'));
//SqlHelper::printSql($users);
//        exit();
    $pagination=str_replace("page-link","page-link-ajax",(string) $users->links());
    $pagination=str_replace("href","url",$pagination);
 
    return response()->json(['users'=>$users,'pagination'=> $pagination]);
}

    /**
     * @param $lang
     * @param $group_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignUser($lang,$group_id,$id){
        $assigns=  Assigns::where("product_id",$group_id)
            ->where("product_type",'group')
            ->where('assigns.ref_type','student')
            ->where('ref_id',$id);
        if(empty($assigns)){
            return response()->json(['status'=>201]);
        }
        $assigns= new Assigns;
        $modelGroup=Groups::find($group_id);
        $assigns->created_at=date('Y-m-d H:m:s');
        $assigns->product_id=$group_id;
        $assigns->ref_id=$id;
        $assigns->product_type='group';
        $assigns->ref_type='student';
        $assigns->school=0;
        NotificationHelper::n_addUserToGroup($modelGroup['title_'.App::getLocale()],'/groups',$id);
        $assigns->save();
         return response()->json(['status'=>201]);
    }

    /**
     * @param $lang
     * @param $group_id
     * @param $id
     * @return mixed
     */
      public function deleteAssignUser($lang,$group_id,$id){
          $assigns=  Assigns::where("product_id",$group_id)
              ->where("product_type",'group')
              ->where('assigns.ref_type','student')
              ->where('ref_id',$id)->delete();
          $modelGroup=Groups::find($group_id);
          NotificationHelper::n_removeUserToGroup($modelGroup['title_'.App::getLocale()],'/groups',$id);
          return Response::json([
              'message' => 'sucssesfuly delete user'
          ], 201);

      }

    public function showTeatcheGroups($lang ,$idTeatcher){
        $teachers= Users::where("permession",4)->get();
        $groups =Groups::where('teacher',$idTeatcher)->paginate(config('lms.pagination'));
        return view('groups.index')->with("groups",$groups)->with('teachers',$teachers);
    }


    /**
     * @create_by hasan kiwan
     * @param $lang
     * @param $id
     * @return BaseController|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @job show view for send message
     */
    public function sendMessageView($lang,$id){
        return view('groups.sendmessage')->with('id',$id);
    }

    /**
     * @create_by hasan kiwan
     * @param $lang
     * @param $id
     * @param Request $request
     * @job for send message for all user in group selected
     */
    public function sendMessageToGroupStu($lang,$id,Request  $request){
        MessageHelper::sendMessageToGroupUsers($request->message,$id);
    }


    public function  getGroups(){
        $query =Groups::where([]);
        $orderBy='created_at';
        $DescAsk='ASC';

        if(Users::isTeacher()){
            $query->where('teacher',Auth::user()->userid);
        }

        if(Users::isStudent()){
            $idsGroups=Assigns::where('product_type','group')
                ->where('ref_type','student')
                ->where('ref_id',Auth::user()->userid)
                ->get()->pluck('product_id');
            $query->whereIn('group_id',$idsGroups);
        }

        if(Users::isParent()){
            $studentId=Auth::user()->students()->with('studentInfo')
                ->get()->pluck('studentInfo.userid');
            $idsGroups=Assigns::where('product_type','group')
                ->where('ref_type','student')
                ->whereIn('ref_id',$studentId)
                ->get()->pluck('product_id');
            $query->whereIn('group_id',$idsGroups);
        }

        if (request()->isMethod('GET')) {
            $input = request()->all();
            if(isset($input['sorting'])&&$input['sorting']!=''){
                $orderBy=$input['sorting'];
                $this->path.='sorting='.$orderBy;
            }
            if(isset($input['descask'])&&$input['descask']!=''){
                $DescAsk=$input['descask'];
                $this->path.='descask='.$DescAsk;
            }
            if(isset($input['teacher'])&&$input['teacher']!=-1){
                $query->where('teacher',$input['teacher']);
                $this->path.='teacher='.$input['teacher'];
            }
            if(isset($input['parent'])&&$input['parent']!=''){
                $studentId=Users::find($input['parent'])->students()->with('studentInfo')
                    ->get()->pluck('studentInfo.userid');
                $idsGroups=Assigns::where('product_type','group')
                    ->where('ref_type','student')
                    ->whereIn('ref_id',$studentId)
                    ->get()->pluck('product_id');
                $query->whereIn('group_id',$idsGroups);
                $this->path.='parent='.$input['parent'];
            }
            if(isset($input['student'])&&$input['student']!=''){
                $idsGroups=Assigns::where('product_type','group')
                    ->where('ref_type','student')
                    ->where('ref_id',$input['student'])
                    ->get()->pluck('product_id');
                $query->whereIn('group_id',$idsGroups);
                $this->path.='student='.$input['student'];
            }
            if(isset($input['group'])&&$input['group']!=''){
                $query->where('group_id',$input['group']);
            }
        }
        $query->orderBy($orderBy,$DescAsk);
        return $query;
    }

}

?>