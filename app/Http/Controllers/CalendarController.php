<?php 
namespace  App\Http\Controllers;


use App\ActiveQuery\UserActiveQuery;
use App\Classes;
use App\Groups;
use App\Helper\EventHelperCalender;
use App\Levels;
use App\Users;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CalendarController extends BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(Request $request)
  {
      $events= array();// event for student
      $firstStudent=null;

      $queryLevels= Levels::where([]);
      $queryGroups=Groups::where([]);
      $queryStudents=Users::where('permession',Users::USER_STUDENT); //
      $queryParents=Users::where('permession',Users::USER_PARENT); // QUARY USER_PARENT
      $queryTeatchers=Users::where('permession',Users::USER_TEACHER); // QUARY USER_TEACHER
      $modelClasses=Classes::where([]);


      if(Users::isStudent()){
          $queryLevels->where('level_id',Auth::user()->level);
          $idsGroup=Auth::user()->assignsGroupsStudent->pluck('product_id');
          $queryGroups->whereIn('group_id',$idsGroup);
          $modelClasses->where('class_id',Auth::user()->class);
      }
      if(Users::isParent()){
          $ides=Auth::user()->students->pluck('student_id');
          $queryStudents->whereIn('userid',$ides);
           $idsGroup=DB::table('assigns')->where('product_type','group')->where('ref_type','student')->whereIn('ref_id',$ides)->distinct()->get()->pluck('product_id')->toArray();
          $queryGroups->whereIn('group_id',$idsGroup);
          $modelClasses->whereIn('class_id',Auth::user()->students->pluck('student_id.class'));
          $firstStudent=Auth::user()->students->pluck('studentInfo.userid')[0];
      }
      if (Users::isTeacher()){

          $qerayser=DB::table('schedule')
              ->join('classes','classes.class_id','=','schedule.class')
              ->where('teacher',Auth::user()->userid)->distinct()->get();
          $idlevels=$qerayser->pluck('level')->toArray();
          $classids=$qerayser->pluck('class')->toArray();
          $queryLevels->whereIn('level_id',$idlevels);
          $queryGroups->where('teacher',Auth::user()->userid);
          $queryTeatchers->where('userid',Auth::user()->userid);
          $queryStudents->whereIn('class',$classids);
          $modelClasses->whereIn('class_id',$classids);
      }

      if(isset($request->level) && $request->level !=-1){
          $queryStudents->where('level',$request->level);
          $modelClasses->where('level',$request->level);
      }
      if(isset($request->class) && $request->class != -1 ){
          $queryStudents->where('class',$request->class);
      }
      if(isset($request->group) && $request->group != -1){
        $group=Groups::find($request->group);
        $ides=array();
        foreach ($group->assings as $assing){
            $ides[]=$assing->ref_id;
        }
          $queryStudents->whereIn('userid',$ides);
      }


      $levels= $queryLevels->get();
      $groups=$queryGroups->get();
      $students=$queryStudents->get();
      $parents=$queryParents->get();
      $teachers=$queryTeatchers->get();
      $modelClasses=$modelClasses->get();

      return view('calender.index')
          ->with('levels',$levels)
          ->with('groups',$groups)
          ->with('students',$students)
          ->with('parents',$parents)
          ->with('teachers',$teachers)
          ->with('modelClasses',$modelClasses)
          ->withFirstStudent($firstStudent);
  }


    /**
     * @create_by hasan kiwan
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @job return all events for level or class or group or user depands on @param $request
     */
  public function getEvents(Request $request){
      $events=Array();
      if(isset($request->group) && $request->group!= -1){
          $events=EventHelperCalender::getEventGroup($request->group);
          return response()->json($events);
      }
      if(isset($request->parent) && $request->parent!= -1){
           $events=EventHelperCalender::getEventParent($request->parent);
           return response()->json($events);
      }
      if(isset($request->teacher) && $request->teacher!= -1){
          $events=EventHelperCalender::getEventTeacher($request->teacher);
          return response()->json($events);
      }

      if(isset($request->class_id) && $request->class_id!= -1){
          $events=EventHelperCalender::getEventClass($request->class_id);
          return response()->json($events);
      }
      if(isset($request->level) && $request->level!= -1){
          $events=EventHelperCalender::getEventLevel($request->level);
          return response()->json($events);
      }
      if(isset($request->student) && $request->student!= -1){
          $events=EventHelperCalender::getEventUser($request->student);
          return response()->json($events);
      }

      if(Users::isStudent()){
          $events=EventHelperCalender::getEventUser(Auth::user()->userid);
          return response()->json($events);
      }
      if(Users::isParent()){
          $events=EventHelperCalender::getEventParent(Auth::user()->userid);
          return response()->json($events);
      }
      if(Users::isTeacher()){
          $events=EventHelperCalender::getEventTeacher(Auth::user()->userid);
          return response()->json($events);
      }


      return response()->json($events);

  }



}

?>