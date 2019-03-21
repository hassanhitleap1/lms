<?php

namespace App\Http\Controllers;

use App\Categories;
use App\Classes;
use App\Groups;
use App\Helper\NotificationHelper;
use Illuminate\Support\Facades\Lang;
use App\Levels;
use App\Quiz;
use App\QuizAssign;
use App\QuizMedia;
use App\Users;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;

class QuizController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query= Quiz::where([]);
        $orderBy = 'created_at';
        $DescAsk = 'ASC';
        $search = '';
        $path='?';
        $levels=Levels::where([]);
        $categoreis=Categories::all();
        if(isset($request->level) && $request->level != -1){
            $query->where('level',$request->level);
            $path.='&level='.$request->level;
        }
        if(isset($request->category) && $request->category != -1){
            $query->where('category',$request->category);
            $path.='&category='.$request->category;
        }
        if(isset($request->search) && $request->search != ''){
            $search=$request->search;
            $query->where(function($query) use ($search){
                $query->orwhere('title', 'like','%' . trim($search).'%')
                    ->orwhere('description', 'like','%' . trim($search).'%')
                ;
            });
            $path.='&search='.$search;
        }
        if (isset($request->descask) && $request->descask != '') {
            $DescAsk = $request->descask;
            $path.='&descask='.$DescAsk;
        }
        if (isset($request->orderby) && $request->orderby != '') {
            $orderby = $request->orderby;
            $path.='&orderby='.$orderby;
        }
        if(Users::isTeacher()){
            $query->where('teacher',Auth::user()->userid);
        }
        if(Users::isStudent()){
            $levels->where('level_id',Auth::user()->level);
            $quizides=DB::table('quizassign')
                ->orwhere(function($query){
                    $query->where('assigntype', 'class')
                        ->where('id_target', Auth::user()->class);
                })
                ->orwhere(function($query){
                    $query->where('assigntype', 'student')
                        ->where('id_target', Auth::user()->userid);
                })
                ->orwhere(function($query){
                    $groupsid= Auth::user()->assignsGroupsStudent->pluck('product_id');
                    $query->where('assigntype', 'group')
                        ->whereIn('id_target', $groupsid);
                })
                ->get()
                ->pluck('id_quiz')
                ->toArray();
            $query->whereIn('quiz_id',$quizides);
        }
        if(Users::isParent()){
            $idesUsers= Auth::user()->students->pluck('student_id');
            $modelparent= DB::table('parents')->select('users.class','users.level')
                ->join('users','users.userid','parents.student_id')
                ->where('parent_id',Auth::user()->userid)
                ->get();
            $idclass=$modelparent->pluck('class')->toArray();
            $idlevel=$modelparent->pluck('level')->toArray();

            $levels->whereIn('level_id',$idlevel);
            $quizides=DB::table('quizassign')
                ->orwhere(function($query) use ($idesUsers){
                    $query->where('assigntype', 'class')
                        ->whereIn('id_target',$idesUsers);
                })
                ->orwhere(function($query)use ($idesUsers){
                    $query->where('assigntype', 'student')
                        ->whereIn('id_target', $idesUsers);
                })
                ->orwhere(function($query)use ($idesUsers,$idclass){
                    $groupsid= DB::table('assigns')
                        ->select('product_id')
                        ->orWhere(function ($q)use ($idesUsers){
                            $q->where('product_type','group');
                            $q->where('ref_type','student');
                            $q->whereIn('ref_id',$idesUsers);
                        })
                        ->orWhere(function ($q) use ($idclass){
                            $q->where('product_type','group');
                            $q->where('ref_type','class');
                            $q->whereIn('ref_id',$idclass);
                        })
                       ->distinct()
                        ->get()
                        ->pluck('product_id')
                        ->toArray();

                    $query->where('assigntype', 'group')
                        ->whereIn('id_target', $groupsid);
                })
                ->get()
                ->pluck('id_quiz')
                ->toArray();
            $query->whereIn('quiz_id',$quizides);
        }
        $levels=$levels->get();
        $quizs= $query->orderby($orderBy,$DescAsk)->paginate(config('lms.pagination'));
        $quizs->setPath($path);
        return view('quiz.index')->with('quizs',$quizs)->withLevels($levels)->withCategories($categoreis);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $levels=Levels::all();
        $categories=Categories::all();
        return view('quiz.create')
                    ->with('levels',$levels)
                    ->with('categories',$categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'title' => 'required',
            'description' => 'required',
            'level'=>'required',
            'category'=>'required',
        );

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails())
        {
            $errors=$validator->errors()->all();
            return Response::json([
                'errors' => $errors
            ], 201);
        }
        $quiz=new Quiz;
        $quiz->title=$request->title;
        $quiz->description=$request->description;
        $quiz->level=$request->level;
        $quiz->category=$request->category;
        $quiz->teacher=Auth::user()->userid;
        $quiz->manhal_quizid=1;
        $quiz->school=0;
        $quiz->save();
        $query= Quiz::where([]);
        $levels=Levels::all();
        $categoreis=Categories::all();
        if(Users::isTeacher()){
            $query->where('teacher',Auth::user()->userid);
        }
        $quizs= $query->paginate(config('lms.pagination'));
        $quizs->setPath('');
        return view('quiz.index')->with("quizs",$quizs)->withLevels($levels)->withCategories($categoreis)->renderSections()['content'];
    }

    public  function view($lang,$id){
        $_GET["id"]=$id;
        return view('quiz.viewquiz');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($lang,$id)
    {
        $levels=Levels::all();
        $categories=Categories::all();
        $quiz=Quiz::find($id);
        return view('quiz.edit')
            ->with('quiz',$quiz)
            ->with('levels',$levels)
            ->with('categories',$categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $lang ,$id)
    {
        $rules = array(
            'title' => 'required',
            'description' => 'required',
            'level'=>'required',
            'category'=>'required',
        );

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails())
        {
            $errors=$validator->errors()->all();
            return Response::json([
                'errors' => $errors
            ], 201);
        }
        $quiz= Quiz::find($id);
        $quiz->title=$request->title;
        $quiz->description=$request->description;
        $quiz->level=$request->level;
        $quiz->category=$request->category;
        $quiz->teacher=Auth::user()->userid;
        $quiz->manhal_quizid=1;
        $quiz->school=0;
        $quiz->save();
        $query= Quiz::where([]);
        $levels=Levels::all();
        $categoreis=Categories::all();
        if(Users::isTeacher()){
            $query->where('teacher',Auth::user()->userid);
        }
        $quizs= $query->paginate(config('lms.pagination'));
        $quizs->setPath('');
        return view('quiz.index')->with("quizs",$quizs)->withLevels($levels)->withCategories($categoreis)->renderSections()['content'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang,$id)
    {
        $quizassign=DB::table('quizassign')->where('id_quiz',$id)->delete();
        $quiz_media=DB::table('quiz_media')->where('id_quiz',$id)->delete();
        Quiz::find($id)->delete();
        $query= Quiz::where([]);
        $levels=Levels::all();
        $categoreis=Categories::all();
        if(Users::isTeacher()){
            $query->where('teacher',Auth::user()->userid);
        }
        $quizs= $query->paginate(config('lms.pagination'));
        $quizs->setPath('');
        return view('quiz.index')->with("quizs",$quizs)->withLevels($levels)->withCategories($categoreis)->renderSections()['content'];
    }


    public function assignTo ($lang,$idQuiz){
        $quiz=Quiz::find($idQuiz);
        $tab='group';
        $idfirstlevel=Levels::first()->level_id;
        $groups=Groups::where([]);
        $levels=Levels::where([]);
        $classes=Classes::where([]);
        $levelStd=Levels::where([]);
        $classStd=Classes::where([]);
        $groupsStd=Groups::where([]);
        $students=Users::where('permession',Users::USER_STUDENT);

        if (request()->isMethod('GET')) {
            $input = request()->all();
            if (isset($input['level']) && $input['level'] != -1){
                $classes->where('level',$input['level']);
            }else{
                $classes->where('level',$idfirstlevel);
            }
            if (isset($input['levelstd']) && $input['levelstd'] != -1){
                $students->where('level',$input['levelstd']);
                $classStd->where('level',$input['levelstd']);
            }else{
                $classStd->where('level',$idfirstlevel);
            }
            if (isset($input['classstd']) && $input['classstd'] != -1){
                $students->where('class',$input['classstd']);
            }
            if (isset($input['groupstd']) && $input['groupstd'] != -1){
                $idusers=DB::table('assigns')
                    ->select('ref_id')
                    ->where('product_type','group')
                    ->where('product_id',$input['groupstd'])
                    ->where('ref_type','student')
                    ->distinct()
                    ->get()
                    ->pluck('ref_id')
                    ->toArray();
                $students->whereIn('userid',$idusers);
            }
            if (isset($input['tab']) && $input['tab'] != 'undefined'){
                $tab=$input['tab'];
            }
        }

        if (Users::isTeacher()){
            $classANDlevelst=DB::table('schedule')
                ->select('schedule.class','classes.level')
                ->join('classes','classes.class_id','=','schedule.class')
                ->where('schedule.teacher',Auth::user()->userid)
                ->distinct()
                ->get();
            $arrayLevel=$classANDlevelst->pluck('level')->toArray();
            $arrayClass=$classANDlevelst->pluck('class')->toArray();
            $groups=$groups->where('teacher',Auth::user()->userid);
            $levels=$levels->whereIn('level_id',$arrayLevel);
            $classes=$classes->whereIn('class_id',$arrayClass);
            $levelStd=$levelStd->whereIn('level_id',$arrayLevel);
            $classStd=$classStd->whereIn('class_id',$arrayClass);
            $groupsStd=$groupsStd->where('teacher',Auth::user()->userid);
        }

        $groups=$groups->get();
        $levels=$levels->get();
        $classes=$classes->get();
        $levelStd=$levelStd->get();
        $classStd=$classStd->get();
        $groupsStd=$groupsStd->get();
        $students=$students->paginate(config('lms.pagination'));


        return view('quiz.assign_quiz')
            ->withGroups($groups)
            ->withLevels($levels)
            ->withClasses($classes)
            ->withLevelStd($levelStd)
            ->withClassStd($classStd)
            ->withQuiz($quiz)
            ->withTab($tab)
            ->withStudents($students);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveAssign($lang,$idQuiz,Request $request)
    {
        // 1 level , 2 class , // 3 student
        $quiz=Quiz::find($idQuiz);
        $rules = array(
            'startDate' => 'required',
            'endDate' => 'required',
        );

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails())
        {
            $errors=$validator->errors()->all();
            return Response::json([
                'errors' => $errors
            ], 201);
        }
        $type='';
        $arrData=[];
        if(!empty($request->groups)){
            $type='group';
            foreach ($request->groups as $target){
                $quizassign=QuizAssign::where('assigntype','group')->where('id_target',$target)->where('enddate','>=',$request->startDate)->where('id_quiz',$idQuiz)->first();
                if(!empty($quizassign)){
                    $errors=['errors'=>Lang::get('lang.Previously_Sent')];
                    return Response::json([
                        'errors' => $errors,
                    ], 201);
                }
                $arrData[]=[
                    'id_quiz'=>$idQuiz,
                    'startdate'=>$request->startDate,
                    'enddate'=>$request->endDate,
                    'assigntype'=>'group',
                    'id_target'=> intval($target),
                    'senddate'=>date('Y-m-d'),

                ];
                $this->sendNotifcationforUsers($target,$type,$quiz->infoCategory['title_en'],$quiz['quiz_id']);
            }
        }
        if(!empty($request->classes)){
            $type='class';
            foreach ($request->classes as $target){
                $quizassign=QuizAssign::where('assigntype','class')->where('id_target',$target)->where('enddate','>=',$request->startDate)->where('id_quiz',$idQuiz)->first();
                if(!empty($quizassign)){
                    $errors=['errors'=>Lang::get('lang.Previously_Sent')];
                    return Response::json(['errors' => $errors,], 201);
                }
                $arrData[]=[
                    'id_quiz'=>$idQuiz,
                    'startdate'=>$request->startDate,
                    'enddate'=>$request->endDate,
                    'assigntype'=>$type,
                    'id_target'=> intval($target),
                    'senddate'=>date('Y-m-d'),

                ];
                $this->sendNotifcationforUsers($target,$type,$quiz->infoCategory['title_en'],$quiz['quiz_id']);
            }
        }
        if(!empty($request->students)){
            $type='student';
            foreach ($request->students as $target){
                $quizassign=QuizAssign::where('assigntype','student')->where('id_target',$target)->where('enddate','>=',$request->startDate)->where('id_quiz',$idQuiz)->first();
                if(!empty($quizassign)){
                    $errors=['errors'=>Lang::get('lang.Previously_Sent')];
                    return Response::json(['errors' => $errors,], 201);
                }
                $arrData[]=[
                    'id_quiz'=>$idQuiz,
                    'startdate'=>$request->startDate,
                    'enddate'=>$request->endDate,
                    'assigntype'=>$type,
                    'id_target'=> intval($target),
                    'senddate'=>date('Y-m-d'),

                ];
                $this->sendNotifcationforUsers($target,$type,$quiz->infoCategory['title_en'],$quiz['quiz_id']);
            }
        }

        if(empty($request->groups) && empty($request->classes) && empty($request->students)){
            $errors=['error'=>'must be select groups or classes or students to assign quiz'];
            return Response::json([
                'errors' => $errors,
            ], 201);
        }
       DB::table('quizassign')->insert($arrData);

        $errors=['errors'=>Lang::get('lang.Successfully_Add')];
        return Response::json([
            'errors' => $errors,
        ], 201);
    }


    /**
     * @param $lang
     * @param Request $request
     * @return mixed
     */
    public function  getStudents($lang,Request $request){
        $qeray= Users::where('permession',Users::USER_STUDENT);
        if (!empty($request->idlevel)){
            $qeray=$qeray->where('level',$request->idlevel);
        }
        if (!empty($request->idClass)){
            $qeray= $qeray->where('class',$request->idClass);
        }
        $students =$qeray->paginate(config('lms.pagination'));
        $pagination=str_replace("page-link","page-link-ajax-st",(string) $students->links());
        $pagination=str_replace("href","url",$pagination);
        return Response::json([
            'data' => $students ,
            'pagination'=>$pagination,
        ], 201);
    }

    public function  getGroups(){
        $groups=Groups::paginate(config('lms.pagination')); //
        $pagination=str_replace("page-link","page-link-ajax",(string) $groups->links());
        $pagination=str_replace("href","url",$pagination);
        return Response::json([
            'data' => $groups,
            'pagination'=>$pagination,
        ], 201);
    }

    public function  getClasess($lang,Request $request){
        $classes= Classes::where('level',$request->idlevel)->paginate(config('lms.pagination')); //

        $pagination=str_replace("page-link","page-link-ajax-cls",(string) $classes->links());
        $pagination=str_replace("href","url",$pagination);
        return Response::json([
            'data' => $classes ,
            'pagination'=>$pagination,
        ], 201);
    }

    private function sendNotifcationforUsers($target,$type,$category,$quizId){
        $link='/quiz/'.$quizId.'/view';
        switch ($type) {
            case 'group':
                $groups=Groups::find($target);
                foreach ($groups->assings as $assing){
                    NotificationHelper::n_quizForUser($assing->ref_id,$type,$category,$link);
                    $parent=Users::find($assing->ref_id)->parent['parentInfo']['userid'];
                    if(!empty($parent)){
                      NotificationHelper::n_quizForParent($parent,$type,$category,$link,$assing->infoUser->fullname);
                    }
                }
                break;
            case 'class':
                $class=Classes::find($target);
                foreach ($class->students as $student){
                    NotificationHelper::n_quizForUser($student->userid,$type,$category,$link);
                    $parent=Users::find($student->userid)->parent['parentInfo']['userid'];
                    if(!empty($parent)){
                      NotificationHelper::n_quizForParent($parent,$type,$category,$link,$student->fullname);
                    }
                }
                break;
            case 'student':
                $student=Users::find($target);
                NotificationHelper::n_quizForUser($target,$type,$category,$link);
                $parent=Users::find($target)->parent['parentInfo']['userid'];
                if(!empty($parent)){
                   NotificationHelper::n_quizForParent($parent,$type,$category,$link,$student->fullname);
                }
                break;
            default:
                break;
        }
    }

    public function viewMedia($lang,$idQuiz){
        $quiz=Quiz::find($idQuiz);
        $medias=$quiz->media()->paginate(config('lms.pagination'));
        $medias->setPath(URL::to('/').'/'.App::getLocale().'/quiz/get-my-media');
        $categories = config('lms.Categories');
        $pagination=str_replace("page-link","page-link-ajax-my-media",(string) $medias->links());
        $pagination=str_replace("href","url",$pagination);
        $pagination=str_replace("viewmedia","get-my-media",$pagination);
        return view('quiz.media')->with('quiz',$quiz)
            ->with('categories',$categories)
            ->with('medias',$medias)
            ->with('pagination',$pagination);
    }

    private function getmediaAPI($mediatype, $category, $grade, $search, $page)
    {
        $curl = curl_init();
        if(session_status()==PHP_SESSION_NONE){
            session_start();
        }
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $strCookie = 'PHPSESSID=' . $_COOKIE["PHPSESSID"] . '; path=/';
        curl_setopt_array($curl, array(
            CURLOPT_URL => config('lms.URL_API') . "media?type=" . $mediatype . "&category=" . $category . '&manhal_id='.Auth::user()->userid."&grade=" . $grade . "&keyword=" . $search,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $useragent,
            CURLOPT_COOKIE => $strCookie,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $response = [];
        }
        $media = json_decode($response, true);
        $collection = collect($media);
        $data = new \Illuminate\Pagination\LengthAwarePaginator(
            $collection->forPage($page, config('lms.pagination')),
            $collection->count(),
            config('lms.pagination'),
            $page
        );
        $data->setPageName('');
        $data->setPath('');
        return $data;
        // return json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', (string)$response), true);
    }

    public function  deletemedia($lang,Request $request){
        $model= QuizMedia::find($request->idMedia);
        $model->delete();
        return response()->json(['message'=>'success delete media ','code'=>201]);
    }

    public  function  getAllMedia(){
        $mediatype = 'quiz';
        $categorymedia = 1;
        $grade = 0;
        $search = '';
        $page=1;
        $quizId=0;
        $Allmedia = [];
        $quizMediaAdd=null;
        if (request()->isMethod('GET')) {
            $quizId =request()->quizId;
            $quizMediaAdd=  QuizMedia::where('id_quiz',$quizId)->pluck('id_media');
            $input = request()->all();
            if (isset($input['category']) && $input['category'] != '') {
                $categorymedia = $input['category'];
            }
            if (isset($input['grade']) && $input['grade'] != '') {
                $grade = $input['grade'];
            }
            if (isset($input['search']) && $input['search'] != '' && $input['search'] != 'undefined') {
                $search = $input['search'];
            }
            if (isset($input['page']) && $input['page'] != '') {
                $page = $input['page'];
            }
        }
        $Allmedia = $this->getmediaAPI($mediatype, $categorymedia, $grade, $search, $page);
        $pagination=str_replace("page-link","page-link-ajax-my-media",(string) $Allmedia->links());
        $pagination=str_replace("href","url",$pagination);
        return response()->json(['allMedia'=>$Allmedia,'pagination'=>$pagination,'quizMediaAdd'=>$quizMediaAdd]);
    }

    public function  addMedia(Request $request){
        DB::table('quiz_media')->insert([
            'id_quiz' => $request->quizId,
            'title_ar' => $request->nameAr,
            'title_en' => $request->nameEn,
            'thumbnail' => $request->thumbnail,
            'type' => 'quiz',
            'url'=>'https://www.manhal.com/platform/quiz/view/en/index.php?id='.$request->mediaId,
            'id_media'=>$request->mediaId,
            ]);
    }

    public  function getMyMedia(Request $request){
        $media=Quiz::find($request->quizId)->media()->paginate(config('lms.pagination'));
        $pagination=str_replace("page-link","page-link-ajax-my-media",(string) $media->links());
        $pagination=str_replace("href","url",$pagination);
       return response()->json(['media'=>$media,'pagination'=>$pagination]);
    }


    public  function  quizViewer($lang,$id){
        $canview=true;
        if(Users::isParent() || Users::isStudent()){
            $quiz=DB::table('quizassign')
                ->where('quizassign.id_quiz',$id)
                ->where('quizassign.enddate','>=' ,date('Y-m-d'))
                ->where(function ($query){
                    $query->orWhere(function ($q){
                        $q->where('assigntype','student');
                        $q->where('id_target',Auth::user()->userid);
                    }) ->orWhere(function ($q) {
                        $q->where('assigntype','class');
                        $q->where('id_target',Auth::user()->class);
                    })->orWhere(function ($q) {
                        $q->where('assigntype','group');
                        $q->whereIn('id_target',Auth::user()->assignsGroupsStudent->pluck('product_id')->toArray());
                    });
                })
                ->distinct()
                ->get();
            if($quiz->count()<= 0){
                $canview=false;
                $quiz=DB::table('quizassign')
                    ->where('quizassign.id_quiz',$id)
                    ->where('quizassign.enddate','>=' ,date('Y-m-d'))
                    ->where(function ($query){
                        $query->orWhere(function ($q){
                            $q->where('assigntype','student');
                            $q->where('id_target',Auth::user()->userid);
                        }) ->orWhere(function ($q) {
                            $q->where('assigntype','class');
                            $q->where('id_target',Auth::user()->class);
                        })->orWhere(function ($q) {
                            $q->where('assigntype','group');
                            $q->whereIn('id_target',Auth::user()->assignsGroupsStudent->pluck('product_id')->toArray());
                        });
                    })
                    ->distinct()
                    ->get();
                if($quiz->count()<= 0){
                    return view('404');
                }
            }

        }
        if (request()->isMethod('GET')) {

            $quiz = DB::table('quiz')
                ->join('categories', 'quiz.category', '=', 'categories.category_id')
                ->where("quiz.quiz_id",$id)
                ->first();

            $Media = DB::table('quiz_media')
                ->leftJoin('quizresult', function($join){
                    $join->on('quizresult.id_assign', '=', 'quiz_media.id')
                        ->where([["quizresult.id_user","=",Auth::user()->userid]])
                    ;
                })
                ->select('quiz_media.*','quizresult.*',
                    DB::raw('( select category_id from categories where  category_id='.$quiz->category.') as category')
                )
                ->where("quiz_media.id_quiz",$id)->get();

            $total_points=0;
            $lesson_points=0;
            $play_count=0;
            $i=0;
            foreach($Media as $media_Item){
                $total_points+=$media_Item->result;
                $lesson_points+=100;
                if($media_Item->result!='' && $media_Item->result!=null){
                    $play_count+=1;
                }
                $i++;

            }
            if($total_points==0){
                $user_points=0;
            }else{
                $user_points=round($total_points/$lesson_points*100,2);
            }

            if($play_count==0){
                $progress=0;
            }else{
                $progress=round($play_count/$i*100,2);
            }

            $awards=0;

            return view('quizview.index')->with("quiz",$quiz)->with("Media",$Media)->with("user_points",$user_points)->with("progress",$progress)->with("awards",$awards)->withCanview($canview);
        }
    }


    /**
     * browseassignment quiz
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function browseassignment()
    {
        $query = DB::table('quizassign')
            ->select('quizassign.*', 'quiz.title as name_quiz','levels.ltitle_ar', 'levels.ltitle_en', DB::raw("(IF(quizassign.assigntype='group',groups.title_ar,IF(quizassign.assigntype='classes',classes.ctitle_ar,IF(quizassign.assigntype='student',users.uname,'')))) AS title_ar"), DB::raw("(IF(quizassign.assigntype='group',groups.title_en,IF(quizassign.assigntype='classes',classes.ctitle_en,IF(quizassign.assigntype='student',users.uname,'')))) AS title_en"))
            ->join('quiz','quizassign.id_quiz','=','quiz.quiz_id')
            ->leftJoin("groups", function ($join) {
                $join->on('groups.group_id', '=', 'quizassign.id_target')
                    ->where('quizassign.assigntype', 'group');
            })
            ->leftJoin("classes", function ($join) {
                $join->on('classes.class_id', '=', 'quizassign.id_target')
                    ->where('quizassign.assigntype', 'class');
            })
            ->leftJoin("users", function ($join) {
                $join->on('users.userid', '=', 'quizassign.id_target')
                    ->where('quizassign.assigntype', 'student');
            })->leftJoin("levels", function ($join) {
                $join->on('levels.level_id', '=', 'users.level');
                $join->orOn('levels.level_id', '=', 'classes.level');
            });



        if (request()->isMethod('GET')) {
            $input = request()->all();
            if (isset($input['quiz']) && $input['quiz'] != '' && $input['quiz'] != -1 ) {
                $query->where('quizassign.id_quiz',$input['quiz']);
            }
        }
        $quizsQeary=Quiz::where([]);

        if(Users::isTeacher()){
            $quizsQeary->where('teacher',Auth::user()->userid);
        }

        if(Users::isStudent()){
            $qerayIdes= DB::table('quizassign')
                    ->where("assigntype", function ($qerayIdes) {
                        $qerayIdes->where('assigntype', '=', 'class');
                        $qerayIdes->where('id_target', '=', Auth::user()->class);
                    })
                    ->orWhere("assigntype", function ($qerayIdes) {
                        $qerayIdes->where('assigntype', '=', 'student');
                        $qerayIdes->where('id_target', '=', Auth::user()->userid);
                    })
                    ->orWhere("assigntype", function ($qerayIdes) {
                        $idesGroups=Auth::user()->assignsGroupsStudent->pluck('product_id');
                        $qerayIdes->where('assigntype', '=', 'group');
                        $qerayIdes->whereIn('id_target', '=', $idesGroups);
                    })
                    ->select('quizassign.id_quiz')
                >get();

            $quizsQeary->whereIn('quiz_id',$qerayIdes);

        }

        $query=$query->get();
        $quizs=$quizsQeary->get();

        return view('quiz.browseassignment', ['data' => $query])->with('quizs',$quizs);
    }


    public function showresult($lang, $id, $idtarget)
    {

        $query = Quiz::where('quiz.quiz_id', '=', $id)->select('quiz.title', 'quiz.description', 'categories.title_ar', 'categories.title_en', 'quizassign.*')
            ->where('quiz.quiz_id', '=', $id)
            ->leftJoin("categories", function ($join) {
                $join->on('categories.category_id', '=', 'quiz.category');
            })->leftJoin("quizassign", function ($join) use ($idtarget) {
                $join->on('quizassign.id_quiz', '=', 'quiz.quiz_id')
                    ->where('quizassign.id_target', '=', $idtarget);
            })
            ->get();

        $query2 = DB::table("quizresult")->where('quizresult.id_assign', '=', $idtarget)
            ->leftJoin("users", function ($join) {
                $join->on('users.userid', '=', 'quizresult.id_user');
            })
            ->leftJoin("levels", function ($join) {
                $join->on('levels.level_id', '=', 'users.level')
                ;
            })->leftJoin("classes", function ($join) {
                $join->on('classes.class_id', '=', 'users.class');
            })
            ->select('users.uname', 'quizresult.*', 'levels.ltitle_ar', 'levels.ltitle_en', 'classes.ctitle_ar', 'classes.ctitle_en')
            ->get();

        return view('quiz.showresult', ['data' => $query, 'users' => $query2]);
    }



}
