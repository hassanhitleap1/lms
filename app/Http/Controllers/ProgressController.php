<?php
namespace App\Http\Controllers;

use App\Categories;
use App\Curriculums;
use App\Helper\SqlHelper;
use App\Helper\UserHelper;
use App\Homeworks;
use App\Http\Middleware\Lang;
use App\Lessons;
use App\Levels;
use App\Quiz;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProgressController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $categories=Categories::all();

        $user=null;
        if(Users::isStudent()){
            //////////////////// first chart Student User //////////////////////////////
            $user=Users::find(Auth::user()->userid);
            $arrProgress=[];
            $arrProgress['title']=['Category','Rusalt','progress','badge'];
            $i=0;
            foreach ($categories as $category){
                $arrayResult=UserHelper::calculateUesrMarkCategory($user->userid,$category->category_id) ;

                if(!empty($arrayResult)){
                    $categories[$i]['result']=$arrayResult['result'];
                    $categories[$i]['percent']=$arrayResult['percent'];
                    $arrProgress['categories'][]=[$category['title_'.App::getLocale()],$arrayResult['result'],$arrayResult['percent'],$user->badges($category->category_id)->count()];
                }else{
                    $categories[$i]['result']=null;
                    $categories[$i]['percent']=null;
                    $arrProgress['categories'][]=[$category['title_'.App::getLocale()],0,0,$user->badges($category->category_id)->count()];
                }
                $i++;
            }

            $arrProgressJson=json_encode($arrProgress);

            //////////////////// second  chart Student User //////////////////////////////
            $arrProgressDitales=[];
            $curriculums=Curriculums::where('cu_level',$user->level)->get();
            $arrProgressDitalesa['title']=['lessons', 'Result', 'Progress'];
            if(!empty($curriculums)&& count($curriculums)>1){
                foreach ($curriculums as $curriculum){
                    $avg=UserHelper::avgCurriculum($user->userid,$curriculum->curriculumsid);
                    $arrProgressDitalesa['lessons'][]=
                        [
                            $curriculum['cu_title_'.App::getLocale()],
                            $avg['result'],
                            $avg['percent']
                        ];
                }
            }

            $arrProgressJsonDitales=json_encode($arrProgressDitalesa);

            //////////////////// third  chart //////////////////////////////

            $students = Users::where('userid',Auth::user()->userid)->paginate(config('lms.pagination'));

            $categoryModel=Categories::first();
            $i=0;
            foreach ($students as $student){
                $arrayResult=UserHelper::calculateUesrMarkCategory($student->userid,$categoryModel->category_id);
                $result=$arrayResult['result'];
                $percent=$arrayResult['percent'];
                $arrProgressStudent['users'][]=[$student->fullname,$result,$percent,$student->allBadges->count()];
                $i++;
            }
            $arrProgressJsonStudent=json_encode($arrProgressStudent);

            //////////////////// fourth chart Student User //////////////////////////////
            $arrProgressAll=[];
            foreach ($categories as $category ){
                $arrProgressAll['title'][]=$category['title_'.App::getLocale()];
            }
            if($arrProgressAll!=null){
                array_unshift($arrProgressAll['title'] , 'Student Name');
            }
            foreach ($students as $student){
                $marksUser=[];
                foreach ($categories as $category ){
                    $marksUser[]=UserHelper::calculateUesrMarkCategory($student->userid,$category->category_id)['result'];

                }
                array_unshift($marksUser , $student->fullname);
                $arrProgressAll['students'][]=$marksUser;
            }
            $arrProgressJsonAll=json_encode($arrProgressAll);

            return view('progress.student.index')
                ->with('arrProgressJson',$arrProgressJson)
                ->with('arrProgressJsonDitales',$arrProgressJsonDitales)
                ->with('arrProgressJsonStudent',$arrProgressJsonStudent)
                ->with('arrProgressJsonAll',$arrProgressJsonAll)
                ->with('user',$user);
        }


        if(Users::isParent())
        {
            //////////////////////// first chart  User Parent ///////////////////////
            $user=Auth::user()->students->pluck('studentInfo')->first();
            $arrProgress=[];

            $arrProgress['title']=['Category','Rusalt','progress','badge'];
            $i=0;
            foreach ($categories as $category){
                $arrayResult=UserHelper::calculateUesrMarkCategory($user->userid,$category->category_id) ;

                if(!empty($arrayResult)){
                    $categories[$i]['result']=$arrayResult['result'];
                    $categories[$i]['percent']=$arrayResult['percent'];
                    $arrProgress['categories'][]=[$category['title_'.App::getLocale()],$arrayResult['result'],$arrayResult['percent'],$user->badges($category->category_id)->count()];
                }else{
                    $categories[$i]['result']=null;
                    $categories[$i]['percent']=null;
                    $arrProgress['categories'][]=[$category['title_'.App::getLocale()],0,0,$user->badges($category->category_id)->count()];
                }
                $i++;
            }
            $arrProgressJson=json_encode($arrProgress);

            //////////////////////// second  chart  User Parent ///////////////////////

            $arrProgressDitales=[];
            $curriculums=Curriculums::where('cu_level',$user->level)->get();
            $arrProgressDitales['title']=['lessons', 'Result', 'Progress'];
            if(!empty($curriculums) && count($curriculums)>1){
                foreach ($curriculums as $curriculum){
                    $avg=UserHelper::avgCurriculum($user->userid,$curriculum->curriculumsid);
                    $arrProgressDitales['lessons'][]=
                        [
                            $curriculum['cu_title_'.App::getLocale()],
                            $avg['result'],
                            $avg['percent']
                        ];
                }
            }else{
                $percent=0;
                $curriculums=Curriculums::limit(5)->get();
                foreach ($curriculums as $curriculum){
                    $arrProgressDitales['lessons'][]=[ $curriculum['cu_title_'.App::getLocale()],$percent,$percent];
                }
            }
            $arrProgressJsonDitales=json_encode($arrProgressDitales);
            //////////////////////// third  chart  User Parent ///////////////////////

            $arrProgressStudent=[];
            $students=Users::whereIn('userid',Auth::user()->students->pluck('studentInfo.userid'))->get();;

            $categoryModel=Categories::first();
            $i=0;

            foreach ($students as $student){
                $arrayResult=UserHelper::calculateUesrMarkCategory($student['userid'],$categoryModel->category_id);
                $result=$arrayResult['result'];
                $percent=$arrayResult['percent'];
                $arrProgressStudent['users'][]=[$student->fullname,$result,$percent,$student->allBadges->count()];
                $i++;
            }
            $arrProgressJsonStudent=json_encode($arrProgressStudent);

            //////////////////////// fourth chart  User Parent ///////////////////////

            $arrProgressAll=[];
            foreach ($categories as $category ){
                $arrProgressAll['title'][]=$category['title_'.App::getLocale()];
            }
            if($arrProgressAll!=null){
                array_unshift($arrProgressAll['title'] , 'Student Name');
            }
            foreach ($students as $student){
                $marksUser=[];
                foreach ($categories as $category ){
                    $marksUser[]=UserHelper::calculateUesrMarkCategory($student->userid,$category->category_id)['result'];

                }
                array_unshift($marksUser , $student->fullname);
                $arrProgressAll['students'][]=$marksUser;
            }
            $arrProgressJsonAll=json_encode($arrProgressAll);

            return view('progress.index_parent')->with('arrProgressJson',$arrProgressJson)
                ->with('arrProgressJsonDitales',$arrProgressJsonDitales)
                ->with('arrProgressJsonStudent',$arrProgressJsonStudent)
                ->with('arrProgressJsonAll',$arrProgressJsonAll)
                ->with('user',$user);
        }

            //////////////////////// first chart  User Admin ///////////////////////
            $students=Users::where("permession",Users::USER_STUDENT)->limit(5)->get();
            $user= Users::where('permession',Users::USER_STUDENT)->first();
            $arrProgress=[];
            $arrProgress['title']=['Category','Rusalt','progress','badge'];
            $i=0;
            foreach ($categories as $category){
                $arrayResult=UserHelper::calculateUesrMarkCategory($user->userid,$category->category_id) ;
                if(!empty($arrayResult)){
                    $arrProgress['categories'][]=[$category['title_'.App::getLocale()],$arrayResult['result'],$arrayResult['percent'],$user->badges($category->category_id)->count()];
                }else{
                    $arrProgress['categories'][]=[$category['title_'.App::getLocale()],0,0,$user->badges($category->category_id)->count()];
                }
                $i++;
            }

            $arrProgressJson=json_encode($arrProgress);

            //////////////////////  second  chart Admin User///////////////////////
            $arrProgressDitalesa=[];
            $curriculums=Curriculums::where('cu_level',$user->level)->get();
            $arrProgressasT['title']=['lessons', 'Result', 'Progress'];
            if(!empty($curriculums)&& count($curriculums)>1){
                foreach ($curriculums as $curriculum){
                    $avg=UserHelper::avgCurriculum($user->userid,$curriculum->curriculumsid);
                    $arrProgressasT['lessons'][]=
                        [
                            $curriculum['cu_title_'.App::getLocale()],
                            $avg['result'],
                            $avg['percent']
                        ];
                }
            }else{
                $percent=0;
                $curriculums=Curriculums::limit(5)->get();
                foreach ($curriculums as $curriculum){
                    $arrProgressasT['lessons'][]=[ $curriculum['cu_title_'.App::getLocale()],$percent,$percent];
                }
            }

            $arrProgressJsonDitales=json_encode($arrProgressasT);

            //////////////////////////////  third  chart Admin User/ ////////////////////////////
             $arrProgressStudent=[];

            $categoryModel=Categories::first();
            $i=0;
            foreach ($students as $student){
                $arrayResult=UserHelper::calculateUesrMarkCategory($student->userid,$categoryModel->category_id);
                $result=$arrayResult['result'];
                $percent=$arrayResult['percent'];
                $arrProgressStudent['users'][]=[$student->fullname,$result,$percent,$student->allBadges->count()];
                $i++;
            }
            $arrProgressJsonStudent=json_encode($arrProgressStudent);
         //////////// fourth chat Admin User //////////////////////////
            $arrProgressAll=[];
            foreach ($categories as $category ){
                $arrProgressAll['title'][]=$category['title_'.App::getLocale()];
            }
            if($arrProgressAll!=null){
                array_unshift($arrProgressAll['title'] , 'Student Name');
            }
            foreach ($students as $student){
                $marksUser=[];
                foreach ($categories as $category ){
                    $marksUser[]=UserHelper::calculateUesrMarkCategory($student->userid,$category->category_id)['result'];

                }
                array_unshift($marksUser , $student->fullname);
                $arrProgressAll['students'][]=$marksUser;
            }
            $arrProgressJsonAll=json_encode($arrProgressAll);

            return view('progress.index')
                ->with('arrProgressJson',$arrProgressJson)
                ->with('arrProgressJsonDitales',$arrProgressJsonDitales)
                ->with('arrProgressJsonStudent',$arrProgressJsonStudent)
                ->with('arrProgressJsonAll',$arrProgressJsonAll)
                ->with('user',$user);



    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function parent(Request $request)
    {
        $tab='table';
        $levels=Levels::where([]);
        $students=Users::where('permession',Users::USER_STUDENT);
        $user=null;
        if(isset($request->tab) && $request->tab !=''){
            $tab=$request->tab;
        }
        if(isset($request->class ) && $request->level !=-1){
            $students->where('level',$request->level);
        }
        if(isset($request->class ) && $request->class  !=-1){
            $students->where('class',$request->class);
        }
        if(Users::isStudent()){
            $levels=$levels->where('level_id',Auth::user()->level);
            $user=Users::find(Auth::user()->userid);
        }
        if(Users::isParent()){
            $levelsChild =Auth::user()->students->pluck('studentInfo.level');
            $childIds =Auth::user()->students->pluck('student_id');
            $students->whereIn('userid',$childIds);
            $levels->whereIn('level_id',$levelsChild);
        }
        if(Users::isTeacher()){
            $arrObject=DB::table('schedule')
                ->select('schedule.class','classes.level')
                ->join('classes','classes.class_id','=','schedule.class')
                ->where('schedule.teacher',Auth::user()->userid)
                ->distinct()
                ->get();
            $levels->whereIn('level_id',$arrObject->pluck('level')->toArray());
            $students->whereIn('class',$arrObject->pluck('class')->toArray());
        }


        $students= $students->get();

        $categories=Categories::where([]);
        if(count($students)>0){
            if($request->student !='' && $request->student !=-1){
                $user= Users::where("userid",$request->student)->first();
            }else{
                $user= Users::where("userid",$students[0]->userid)->first();
            }
        }

        if($user==null){
            $categories->where('category_id',-1);
        }

        $categories=$categories->paginate(config('lms.pagination'));

        $arrProgress=[];
        $arrProgress['title']=['Category','Rusalt','progress','badge'];
        $i=0;
        foreach ($categories as $category){
            $arrayResult=UserHelper::calculateUesrMarkCategory($user->userid,$category->category_id) ;

            if(!empty($arrayResult)){
                $categories[$i]['result']=$arrayResult['result'];
                $categories[$i]['percent']=$arrayResult['percent'];
                $arrProgress['categories'][]=[$category['title_'.App::getLocale()],$arrayResult['result'],$arrayResult['percent'],$user->badges($category->category_id)->count()];
            }else{
                $categories[$i]['result']=null;
                $categories[$i]['percent']=null;
                $arrProgress['categories'][]=[$category['title_'.App::getLocale()],0,0,$user->badges($category->category_id)->count()];
            }
            $i++;
        }

        $arrProgressJson=json_encode($arrProgress);



        $levels=$levels->get();

        if(Users::isStudent()){
            return view('progress.student.parent')->with('levels',$levels)
                ->with('students',$students)
                ->with('user',$user)
                ->with('categories',$categories)
                ->with('arrProgressJson',$arrProgressJson)
                ->withTab($tab);
        }

        return view('progress.parent')->with('levels',$levels)
            ->with('students',$students)
            ->with('user',$user)
            ->with('categories',$categories)
            ->with('arrProgressJson',$arrProgressJson)
            ->withTab($tab);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function parentdetails($lang)
    {
        $userid = request()->student;
        $user=null;
        $tab='table';
        if(isset(request()->tab) && request()->tab !=''){
            $tab=request()->tab;
        }
        $query = Users::where("permession",Users::USER_STUDENT);
        if(empty($userid)){
            $user= Users::where("permession",Users::USER_STUDENT)->first();
        }else{
            $user= Users::find($userid);
        }

        $categories=Categories::where([]);

        $homeworks=Homeworks::where([]);
        $homeworkassign=DB::table('homeworkassign')
            ->select('homeworkassign.*')
            ->where(function ($q)use ($user){
                $q->orwhere(function($query)use ($user){
                    $query->where('assigntype', 'classes')
                        ->where('id_target', $user->class);
                })->orwhere(function($query)use ($user){
                    $query->where('assigntype', 'student')
                        ->where('id_target', $user->userid);
                })->orwhere(function($query)use ($user){
                    $groupsid= $user->assignsGroupsStudent->pluck('product_id');
                    $query->where('assigntype', 'group')
                        ->whereIn('id_target', $groupsid);
                });
            })
            ->get()
            ->pluck('id_homework')
            ->toArray();
        $homeworks->whereIn('homework_id',$homeworkassign);

        $quiz=Quiz::where([]);
        $quizides=DB::table('quizassign')
            ->where(function ($q)use ($user){
                $q->orwhere(function($query)use ($user){
                    $query->where('assigntype', 'class')
                        ->where('id_target', $user->class);
                })->orwhere(function($query)use ($user){
                    $query->where('assigntype', 'student')
                        ->where('id_target', $user->userid);
                })->orwhere(function($query)use ($user){
                    $groupsid= $user->assignsGroupsStudent->pluck('product_id');
                    $query->where('assigntype', 'group')
                        ->whereIn('id_target', $groupsid);
                });
            })->get()
            ->pluck('id_quiz')
            ->toArray();
        $quiz->whereIn('quiz_id',$quizides);
        $curriculums=Curriculums::where('cu_level',$user->level);

        if(isset(request()->category) && request()->category !=-1){
            $curriculums->where('cu_category',request()->category );
            $homeworks->where('category',request()->category);
            $quiz->where('category',request()->category);

        }
        if(isset(request()->curriculum) && request()->curriculum !=-1){
            $curriculums->where('curriculumsid',request()->curriculum );
        }
        if(Users::isStudent()){
            $user=Users::find(Auth::user()->userid);
            $curriculums->where('cu_level',Auth::user()->level);
        }
        if(Users::isParent()){
            $levelStudentId=DB::table('parents')->select('users.level')
                ->join('users','users.userid','=','parents.student_id')
                ->where('parents.parent_id',Auth::user()->userid)
                ->get()->pluck('level')->toArray();
            $curriculums->whereIn('cu_level',$levelStudentId);
        }

        if (Users::isTeacher()){

            $arrObject=DB::table('schedule')
                ->select('schedule.class','classes.level')
                ->join('classes','classes.class_id','=','schedule.class')
                ->where('schedule.teacher',Auth::user()->userid)
                ->distinct()
                ->get();
            $query->whereIn('class',$arrObject->pluck('class')->toArray());
            $curriculums->whereIn('cu_level',$arrObject->pluck('level')->toArray());
        }

        $curriculums=$curriculums->get();
        $arrProgress=[];

        $arrProgress['title']=['lessons', 'Result', 'Progress'];
        if(!empty($curriculums) && count($curriculums)>1){
            foreach ($curriculums as $curriculum){


                $avg=UserHelper::avgCurriculum($user->userid,$curriculum->curriculumsid);
                $arrProgress['lessons'][]=
                    [
                        $curriculum['cu_title_'.App::getLocale()],
                        $avg['result'],
                        $avg['percent']
                    ];


            }
        }else{
            $percent=0;
            $curriculums=Curriculums::limit(5)->get();
            foreach ($curriculums as $curriculum){
                $arrProgress['lessons'][]=[ $curriculum['cu_title_'.App::getLocale()],$percent,$percent];
            }
        }

        $arrProgressJson=json_encode($arrProgress);
        $queryCurriculums=Curriculums::where([]);

        if(Users::isParent()){
            $idesUsers= Auth::user()->students->pluck('student_id');
            $query=$query->whereIn('userid',$idesUsers);
            $levelsChild =Auth::user()->students->pluck('studentInfo.level');
            $queryCurriculums=$queryCurriculums->whereIn('cu_level',$levelsChild);
        }
        if(Users::isStudent()){
            $query=$query->where('userid',Auth::user()->userid);
            $queryCurriculums=$queryCurriculums->where('cu_level',Auth::user()->level);
        }
        if (Users::isTeacher()){
            $arrObject=DB::table('schedule')
                ->select('schedule.class','classes.level')
                ->join('classes','classes.class_id','=','schedule.class')
                ->where('schedule.teacher',Auth::user()->userid)
                ->distinct()
                ->get();
            $queryCurriculums->whereIn('cu_level',$arrObject->pluck('level')->toArray());
        }
        $students=$query->get();
        $curriculums=$queryCurriculums->get();
        $lessons=Lessons::where('level',$user->level);
        if(isset(request()->curriculum) && request()->curriculum!= -1){
            $lessons->where('curricula',request()->curriculum);
        }
        if(isset(request()->category) && request()->category!= -1){
            $lessons->where('category',request()->category);
        }
        $homeworks=$homeworks->get();
        $quiz=$quiz->get();
        $categories=$categories->get();
        $lessons=$lessons->get();

        return view('progress.parentdetails')->with('user',$user)
            ->with('arrProgressJson',$arrProgressJson)
            ->with('students',$students)
            ->with('lessons',$lessons)
            ->with('curriculums',$curriculums)
            ->withCategories($categories)
            ->withHomeworks($homeworks)
            ->withQuiz($quiz)
            ->withTab($tab);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function awards()
    {
        $levels=Levels::where([]);
        if(Users::isStudent()){
            $levels=$levels->where('level_id',Auth::user()->level);
        }
        if(Users::isParent()){
            $levelsChild =Auth::user()->students->pluck('studentInfo.level');
            $levels=$levels->whereIn('level_id',$levelsChild);
        }
        $levels=$levels->get();
        return view('progress.awards')->with('levels',$levels);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function class(Request $request)
    {
        $query = Users::where("permession",Users::USER_STUDENT);
        $categories=Categories::all();
        $levels=Levels::where([]);
        $path='?';
        $categoryModel=null;
        $tab='table';
        if(isset($request->tab) && $request->tab !=''){
            $tab=$request->tab;
        }
        if (isset($request->level ) && $request->level != -1 ) {
            $query= $query->where('level',$request->level);
            $path.="&level=".$request->level;
        }
        if (isset( $request->class ) && $request->class != -1 ) {
            $query= $query->where('class',$request->class);
            $path.="&class=".$request->class;
        }
        if (isset($request->category)&& $request->category != -1 ) {
            $categoryModel=Categories::find($request->category);
            $path.="&category=".$request->category;

        }else{
            $categoryModel=Categories::all()->first();
        }
        if(Users::isParent()){
            $query->whereIn('userid',Auth::user()->students->pluck('student_id'));
        }
        if(Users::isStudent()){
            $query->where('userid',Auth::user()->userid);
        }
        if (Users::isTeacher()){
            $arrObject=DB::table('schedule')
                ->select('schedule.class','classes.level')
                ->join('classes','classes.class_id','=','schedule.class')
                ->where('schedule.teacher',Auth::user()->userid)
                ->distinct()
                ->get();

            $levels->whereIn('level_id',$arrObject->pluck('level')->toArray());
            $query->whereIn('class',$arrObject->pluck('class')->toArray());
        }
        $students=$query->paginate(config('lms.pagination'));
        $arrProgress=[];
        $i=0;
        foreach ($students as $student){
            $arrayResult=UserHelper::calculateUesrMarkCategory($student->userid,$categoryModel->category_id);
            $students[$i]['result']=$arrayResult['result'];
            $students[$i]['percent']=$arrayResult['percent'];
            $result=$arrayResult['result'];
            $percent=$arrayResult['percent'];
            $arrProgress['users'][]=[$student->fullname,$result,$percent,$student->allBadges->count()];
            $i++;
        }
        $arrProgressJson=json_encode($arrProgress);

        if(Users::isStudent()){
            $levels=$levels->where('level_id',Auth::user()->level);
        }
        if(Users::isParent()){
            $levelsChild =Auth::user()->students->pluck('studentInfo.level');
            $levels=$levels->whereIn('level_id',$levelsChild);
        }
        $levels=$levels->get();
        $students->setPath($path);
        if(Users::isStudent()){
            return view('progress.student.class')->with('levels',$levels)
                ->with('categories',$categories)
                ->with('students',$students)
                ->with('categoryModel',$categoryModel)
                ->with('arrProgressJson',$arrProgressJson)
                ->withTab($tab);
        }
        return view('progress.class')->with('levels',$levels)
            ->with('categories',$categories)
            ->with('students',$students)
            ->with('categoryModel',$categoryModel)
            ->with('arrProgressJson',$arrProgressJson)
            ->withTab($tab);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function all(Request $request)
    {
        $tab='table';
        if(isset($request->tab) && $request->tab !=''){
            $tab=$request->tab;
        }
        $query = Users::where("permession",Users::USER_STUDENT);
        $levels=Levels::where([]);
        if(Users::isStudent()){
            $levels=$levels->where('level_id',Auth::user()->level);
            $query->where('userid',Auth::user()->userid);
        }
        if(Users::isParent()){
            $levelsChild =Auth::user()->students->pluck('studentInfo.level');
            $levels=$levels->whereIn('level_id',$levelsChild);
            $query->whereIn('userid',Auth::user()->students->pluck('student_id'));
        }
        if(Users::isTeacher()){
            $arrObject=DB::table('schedule')
                ->select('schedule.class','classes.level')
                ->join('classes','classes.class_id','=','schedule.class')
                ->where('schedule.teacher',Auth::user()->userid)
                ->distinct()
                ->get();
            $levels->whereIn('level_id',$arrObject->pluck('level')->toArray());
            $query->whereIn('class',$arrObject->pluck('class')->toArray());
        }

        $levels=$levels->get();
        $categories= Categories::all();
        if (isset($request->level) && $request->level != -1 ) {
            $query= $query->where('level',$request->level);
        }
        if (isset($request->class) && $request->class != -1 ) {
            $query= $query->where('class',$request->class);
        }
        $students=$query->paginate(config('lms.pagination'));
        $arrProgress=[];
        foreach ($categories as $category ){
            $arrProgress['title'][]=$category['title_'.App::getLocale()];
        }
        if ($arrProgress !=null){
            array_unshift($arrProgress['title'] , 'Student Name');
        }
        foreach ($students as $student){
            $marksUser=[];
            foreach ($categories as $category ){
                $marksUser[]=UserHelper::calculateUesrMarkCategory($student->userid,$category->category_id)['result'];

            }
            array_unshift($marksUser , $student->fullname);
            $arrProgress['students'][]=$marksUser;
        }
        $arrProgressJson=json_encode($arrProgress);
        if(Users::isStudent()){
            return view('progress.student.all')->with('levels',$levels)
                ->with('students',$students)->with('categories',$categories)
                ->with('arrProgressJson',$arrProgressJson);
        }
        return view('progress.all')->with('levels',$levels)
            ->with('students',$students)->with('categories',$categories)
            ->with('arrProgressJson',$arrProgressJson)
            ->withTab($tab);
    }


}

?>