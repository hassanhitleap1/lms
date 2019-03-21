<?php
namespace App\Http\Controllers;
use App\Assigns;
use App\Classes;
use App\Curriculums;
use App\Domains;
use App\Groups;
use App\Helper\SqlHelper;
use App\Lessonassigns;
use App\LessonMidea;
use App\Pivot;
use Illuminate\Foundation\Bus\DispatchesJobs;

use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Levels;
use App\Lessons;
use App\Categories;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Response;




class LessonsController extends BaseController
{

    public $path='?';
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $orderby='lessons.created_at';
        $descask='DESC';
        $where = array(["id", ">", 0]);
        $whereCur = array(["curriculumsid", ">", 0]);
        $creaters=Users::where([]);
        $usersAdmin=array();

        $creaters->where(function ($q){
            $q->orWhere('permession',Users::USER_TEACHER);
            $q->orWhere('permession',Users::USER_SCHOOL_ADMINISTRATOR);
            $q->orWhere('permession',Users::USER_SCHOOL_MANGER);
            $q->orWhere('permession',Users::USER_MANHAL_ADMINISTRATOR);
        });
        $creaters=$creaters->get();
        $lessonsidAssigin=[];
        $lessonsidAssiginNotIN=[];
        if (request()->isMethod('GET')) {
            $input = request()->all();
            if (isset($input['cerate_by']) && $input['cerate_by'] != -1) {
                $where[] = ["lessons.teacher", "=", $input['cerate_by']];
                $this->path.='cerate_by='.$input['cerate_by'].'&';
            }
            if (isset($input['level']) && $input['level'] != -1) {
                $where[] = ["lessons.level", "=", $input['level']];
                $whereCur[] = ["cu_level", "=", $input['level']];
                $this->path.='level='.$input['level'].'&';
            }
            if (isset($input['category']) && $input['category'] != -1) {
                $where[] = ["lessons.category", "=", $input['category']];
                $whereCur[] = ["cu_category", "=", $input['category']];
                $this->path.='category='.$input['category'].'&';
            }
            if (isset($input['curricula']) && $input['curricula'] != -1) {
                $where[] = ["lessons.curricula", "=", $input['curricula']];
                $this->path.='curricula='.$input['curricula'].'&';
            }
            if (isset($input['standard']) && $input['standard'] != -1) {
                $where[] = ["lessons.standard", "=", $input['standard']];
                $this->path.='standard='.$input['standard'].'&';
            }
            if (isset($input['orderby']) && $input['orderby'] != -1) {
                $orderby=$input['orderby'] ;
                $this->path.='orderby='.$orderby.'&';
            }
            if (isset($input['descask']) && $input['descask'] != -1) {

                $descask=$input['descask'];
                $this->path.='descask='.$descask;
            }
        }

        if(Users::isStudent()){
            $where[] = ["lessons.level", "=", Auth::user()->level];
            $idgroups=Auth::user()->assignsGroupsStudent->pluck('product_id'); //assignsGroupsStudent;
            if(empty($idgroups)){
                $lessonsidAssigin=Lessonassigns::where('assigntype','group')
                    ->whereIn('id_target',$idgroups)->distinct()
                    ->get()->pluck('id_lesson');
            }

        }

        if(Users::isTeacher()){
            $usersAdmin=Users::where(function ($q){
                    $q->orWhere('permession' ,Users::USER_SCHOOL_ADMINISTRATOR);
                    $q->orWhere('permession' ,Users::USER_SCHOOL_MANGER);
                    $q->orWhere('permession' ,Users::USER_MANHAL_ADMINISTRATOR);
                    $q->orWhere('userid',Auth::user()->userid);
            })->get()->pluck('userid')->toArray();

        }

        $Lessons = DB::table('lessons')
                ->join('categories', 'lessons.category', '=', 'categories.category_id')
                ->join('levels', 'lessons.level', '=', 'levels.level_id')
                ->join('users', 'lessons.teacher', '=', 'users.userid')
                ->join('curriculums', 'lessons.curricula', '=', 'curriculums.curriculumsid')
                ->where($where);
        if(Users::isTeacher()){
            $arrCategory = DB::table('schedule')->select('category')->where('teacher',Auth::user()->userid)->get()->pluck('category')->toArray();
            $Lessons->whereIn('lessons.teacher',$usersAdmin);
            $Lessons->whereIn('lessons.category',$arrCategory);
        }
        if(Users::isParent()){
           $idlevelParent=DB::table('parents')
               ->select('users.*')
               ->join('users','users.userid','=','parents.student_id')
               ->where('parents.parent_id',Auth::user()->userid)
               ->distinct()
               ->get()
               ->pluck('level')
               ->toArray();
            $Lessons->whereIn('lessons.level',$idlevelParent);
        }

        if(isset(\request()->search) && \request()->search != ''){
            $search=\request()->search;
            $Lessons->where(function($query) use ($search){
                $query->orwhere('lessons.title', 'like','%' . trim($search).'%')
                    ->orwhere('lessons.description', 'like','%' . trim($search).'%')
                ;
            });
            $this->path.='&search='.$search;
        }
        
        $Lessons= $Lessons->whereNotIn('lessons.id',$lessonsidAssiginNotIN)
                ->orWhereIn('lessons.id',$lessonsidAssigin)
                ->orderBy($orderby, $descask)
                ->paginate(config('lms.pagination'));


            $Lessons->setPath($this->path);

            $levels=Levels::where([]);
            if(Users::isStudent()){
                $levels=$levels->where('level_id',Auth::user()->level);
            }
            if(Users::isParent()){
                $levelsChild =Auth::user()->students->pluck('studentInfo.level');
                $levels=$levels->whereIn('level_id',$levelsChild);
            }
            $levels=$levels->get();
            $categories = Categories::get();
            $curriculums = Curriculums::where($whereCur)->get();

        return view('lessons.index')->with("Lessons", $Lessons)->with("levels", $levels)->with("categories", $categories)->with("curriculums", $curriculums)->with('creaters',$creaters);


    }


    /**
     * //Hussam - function to open lesson editor
     * @param $lang
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lessonsbuilderedit($lang, $id)
    {
        if (request()->isMethod('GET')) {
            $Lesson = DB::table('lessons')
                ->join('categories', 'lessons.category', '=', 'categories.category_id')
                ->join('levels', 'lessons.level', '=', 'levels.level_id')
                ->join('curriculums', 'lessons.curricula', '=', 'curriculums.curriculumsid')
                ->where([["lessons.id", "=", $id]])
                ->first();

            $Media = DB::table('lesson_media')
                ->join('categories', 'lesson_media.category', '=', 'categories.category_id')
                ->where([["lesson_media.id_lesson", "=", $id]])
                ->get();

            return view('lessonsbuilder.index')->with("Lesson", $Lesson)->with("Media", $Media);
        }
    }

    /**
     * @param $lang
     * @param $id
     * @return mixed
     */
    public function lessonsViewer($lang,$id){
        Lessons::findOrFail($id);
        $studentid=Auth::user()->userid;
        if (request()->isMethod('GET')) {
            $Lesson = DB::table('lessons')
                ->join('categories', 'lessons.category', '=', 'categories.category_id')
                ->join('levels', 'lessons.level', '=', 'levels.level_id')
                ->leftJoin('curriculums', 'lessons.curricula', '=', 'curriculums.curriculumsid')
                ->where([["lessons.id","=",$id]])
                ->first();
            if(Users::isStudent()){
                if($Lesson->level_id != Auth::user()->level){
                    $lessonassgin=DB::table('lessonassigns')
                        ->where('lessonassigns.id_lesson',$id)
                        ->where('lessonassigns.enddate','>=' ,date('Y-m-d'))
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
                        })->get();
                        if($lessonassgin->count()<= 0){
                            return view('404');
                        }
                }
            }elseif (Users::isParent()){
                $levelsid=Auth::user()->students->pluck('studentInfo.level')->toArray();
                if (! in_array($Lesson->level_id, $levelsid)) {
                    $lessonassgin=DB::table('lessonassigns')
                        ->where('lessonassigns.id_lesson',$id)
                        ->where('lessonassigns.enddate','>=' ,date('Y-m-d'))
                        ->where(function ($query){
                            $query->orWhere(function ($q){
                                $q->where('assigntype','student');
                                $q->whereIn('id_target',Auth::user()->students->pluck('studentInfo.userid')->toArray());
                            }) ->orWhere(function ($q) {
                                $q->where('assigntype','class');
                                $q->whereIn('id_target',Auth::user()->students->pluck('studentInfo.class')->toArray());
                            })->orWhere(function ($q) {
                                $q->where('assigntype','group');
                                $idgroups= DB::table('assigns')->select('product_id')
                                        ->where('product_type','group')
                                        ->where('ref_type','student')
                                        ->whereIn('ref_id',Auth::user()->students->pluck('studentInfo.userid')->toArray())
                                        ->get()
                                        ->pluck('product_id')
                                   ->toArray();
                                $q->whereIn('id_target',$idgroups);
                            });
                        })->get();

                    if($lessonassgin->count()<= 0){
                        return view('404');
                    }
                }
            }elseif (Users::isTeacher()){
                $levelsid=DB::table('schedule')->select('classes.level')
                        ->join('classes','classes.class_id','=','schedule.class')
                        ->where('schedule.teacher',Auth::user()->userid)
                        ->get()
                        ->pluck('level')
                        ->toArray();
                if (! in_array($Lesson->level_id, $levelsid)) {
                    return view('404');
                }
            }

            if(isset(request()->student) &&  request()->student!='' && request()->student !=-1){
                $studentid=request()->student;
            }

            $Media = DB::table('lesson_media')
                ->leftJoin('result_media', function($join)use ($studentid){
                    $join->on('result_media.id_media', '=', 'lesson_media.id')
                        ->where([["result_media.user_id","=",$studentid]]);
                })
                ->select('lesson_media.id as media_id','lesson_media.*','result_media.*')
                ->where([["lesson_media.id_lesson","=",$id]])
                ->orderBy('lesson_media.id','ASC')
                //SqlHelper::printSql($Media);
                ->get();
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
            return view('lessonsviewer.index')->with("Lesson",$Lesson)->with("Media",$Media)->with("user_points",$user_points)->with("progress",$progress)->with("awards",$awards);
        }
    }

    /**
     * //Hussam - function to save lesson from editor
     * @param $lang
     * @param $id
     * @param Request $request
     * @return string
     */
    public function lessonsbuildersave($lang, $id, Request $request)
    {
        if($request->lessontype=="lesson"){//save lesson
            $media = json_decode($request->media, true);

            DB::table('lesson_media')
                ->where([["id_lesson", "=", $id]])
                ->delete();

            foreach ($media as $media_item) {

                DB::table('lesson_media')->insert([
                        'id_lesson' => $id,
                        'title' => $media_item["title"],
                        'description' => $media_item["description"],
                        'thumbnail' => $media_item["thumbnail"],
                        'type' => $media_item["type"],
                        'category' => $media_item["category"],
                        'agree' => $media_item["agree"],
                        'id_media' => $media_item["id_media"],
                        'url' => $media_item["url"],
                        'category_ar' => $media_item["category_ar"],
                        'category_en' => $media_item["category_en"]
                    ]
                );
            }
            $result=[
                "result"=>200,
                "msg"=>"success",
            ];
            return json_encode($result);
        }else{//save homework
            $media = json_decode($request->media, true);

            DB::table('homeworkmedia')
                ->where([["id_homework", "=", $id]])
                ->delete();

            foreach ($media as $media_item) {

                DB::table('homeworkmedia')->insert([
                        'id_homework' => $id,
                        'title_ar' => $media_item["title"],
                        'title_en' => $media_item["title"],
                        'thumbnail' => $media_item["thumbnail"],
                        'type' => $media_item["type"],
                        'category' => $media_item["category"],
                        'agree' => $media_item["agree"],
                        'id_media' => $media_item["id_media"],
                        'url' => $media_item["url"],
                        'category_ar' => $media_item["category_ar"],
                        'category_en' => $media_item["category_en"]
                    ]
                );
            }
            $result=[
                "result"=>200,
                "msg"=>"success",
            ];
            return json_encode($result);
        }
  }

    /**
     * @param $mediatype
     * @param $category
     * @param $grade
     * @param $search
     * @param $page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function getmediaAPI($mediatype, $category, $grade, $search, $page)
  {

      $curl = curl_init();
      curl_setopt_array($curl, array(
          CURLOPT_URL => config('lms.URL_API') . "media?type=" . $mediatype . "&category=" . $category .  "&keyword=" . $search."&grade=" . $grade ,
          CURLOPT_RETURNTRANSFER => true,
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

    /**
     * @return array|mixed|string
     */
    public function viewIframe(){
        if(isset($_GET["page"]) && $_GET["page"]!=""){
            $page=$_GET["page"];
        }else{
            return "no page";
        }
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $page,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 300,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      $response = [];
    }

    return $response;
    // return json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', (string)$response), true);
  }

    /**
     * @param $lang
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewmedia($lang,$lessonId)
  {
    $page = request()->get('page', 1);
    $mediatype =  request()->get('mediatype', 'games');
    $categorymedia = request()->get('category', -1);
    $grade = request()->get('grade', 0);
    $search = request()->get('search', '');
    $mediaAssigns=[];

        if (request()->isMethod('GET')) {
            $input = request()->all();
            if (isset($input['type']) && $input['type'] != '' && $input['type'] != 'undefined') {
                $mediatype = $input['type'];
            }
            if (isset($input['category']) && $input['category'] != '') {
                $categorymedia = $input['category'];
            }
            if (isset($input['grade']) && $input['grade'] != '') {
                $grade = $input['grade'];
            }
            if (isset($input['search']) && $input['search'] != '' && $input['search'] != 'undefined') {
                $search = $input['search'];
            }

            if (isset($input['lessontype']) && $input['lessontype']!= 'undefined') {
                if($input['lessontype']=='homework'){
                    $mediaAssigns=DB::table('homeworkmedia')
                        ->select('id_media')
                        ->where('id_homework',$lessonId)
                        ->get()
                        ->pluck('id_media')->toArray();
                }else{
                    $mediaAssigns=DB::table('lesson_media')
                        ->select('id_media')
                        ->where('id_lesson',$lessonId)
                        ->get()
                        ->pluck('id_media')->toArray();
                }
            }
        }
        $categories = config('lms.Categories');

        if ($mediatype == 'stories') {
            $categories = $this->getCategoryStoryAPI();
        }
        $Allmedia = $this->getmediaAPI($mediatype, $categorymedia, $grade, $search, $page);
    return view('lessonsbuilder.edit', ['search' => $search, 'media' => $Allmedia, 'categories' => $categories, 'type' => $mediatype, 'categorymedia' => $categorymedia, 'grade' => $grade,'mediaAssigns'=>$mediaAssigns]);
  }

    /**
     * @param $thumb
     * @param $filename
     * @param $path
     * @param $type
     * @param $id
     * @return string
     */
    public static function getMediaSrc($thumb,$filename,$path,$type,$id) {
      if($path!=""){
          $ext= strtolower(substr($path, -3));
          if($ext=="mp4" || $ext=="mp3" || strpos($path,"stories/demo/index.php")!==false || $type==4 || $type==3 || $type==17){
              if($ext=="mp4"){
                  $path_type="video";
              }elseif($ext=="mp3"){
                  $path_type="audio";
              }else{
                  $path_type="story";
              }
              $url="https://www.manhal.com/mediaviewer.php?type=".$path_type."&path=".$path;
          }else{
              $url="https://www.manhal.com/mediaviewer.php?type=story&path=".$id;
          }
          return $url;
      }
    $arr=explode("/",$thumb);
    unset($arr[7]);
    unset($arr[6]);
    unset($arr[5]);
    $url=implode("/",$arr);
    switch($type){
      case "4"://video
          $url.="/".$id."/".$filename.".mp4";
          $url="https://www.manhal.com/mediaviewer.php?type=video&path=".$url;
          break;
      case "3"://sound
          $url.="/".$id."/".$filename.".mp3";
          $url="https://www.manhal.com/mediaviewer.php?type=audio&path=".$url;
          break;
      case "0"://Worksheets
        $url.="/".$id."/".$filename.".pdf";
        break;
      case "11"://games
        $url.="/".$id."/".$filename.".html";
        break;
      case "12"://interactive worksheets
        $url.="/".$id."/".$filename.".html";
        break;
      case "6"://quiz
        $url.="https://www.manhal.com/platform/quiz/view/en/index.php?id=".$id;
        break;
      case "17"://stories
            //$url="platform/stories/demo/index.php?storyid=".$id;
            $url="https://www.manhal.com/mediaviewer.php?type=story&path=".$id;
            break;
      default:
        $url.="/index.html";
        break;
    }
    return $url;
    }

    /**
     * @param $lang
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewedit($lang, $id)
    {
        $categories = Categories::get();
        $Curricula = Curriculums::where([]);
        $levels=Levels::where([]);
        $lesson = Lessons::find($id);
        if(Users::isTeacher()){
            $levelIds=DB::table('schedule')
                ->select('classes.level')
                ->join('classes','classes.class_id','=','schedule.class')
                ->where('teacher',Auth::user()->userid)
                ->get()
                ->pluck('level')
                ->toArray();
            $levels->whereIn('level_id',$levelIds);
            $Curricula->whereIn('cu_level',$levelIds);
        }
        // teacher removed no needs - by Hussam
        $levels=$levels->get();
        $Curricula = $Curricula->get();
        $domains =Domains::all();
        return view('lessons.edit', ['lesson' => $lesson, 'categories' => $categories, 'curriculums' => $Curricula, 'levels'=>$levels,'domains'=>$domains]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewadd(){

    $Curricula = Curriculums::where([]);
    $categories = Categories::get();
    $levels=Levels::where([]);
    if(Users::isTeacher()){
        $levelIds=DB::table('schedule')
            ->select('classes.level')
            ->join('classes','classes.class_id','=','schedule.class')
            ->where('teacher',Auth::user()->userid)
            ->get()
            ->pluck('level')
            ->toArray();
        $levels->whereIn('level_id',$levelIds);
        $Curricula->whereIn('cu_level',$levelIds);
    }
    $levels=$levels->get();
    $Curricula = $Curricula->get();
    $domains =Domains::all();
    return view('lessons.add', [ 'categories' => $categories, 'curriculums' => $Curricula,'levels'=>$levels,'domains'=>$domains]);
}

    /**
     * @return mixed
     */
    public function saveadd(){
    $title = '';
    $description = '';
    $category = '-1';
    $level = '';
    $curricula = '';
    $Start_Date = '';
    $End_Date = '';
    $min_point = '';
    $max_point = '';
    $domain=-1;
    $pivot=-1;
    $standard=-1;


    $rules = array(
        'title' => 'required',
        'category'=>'required',
        'curricula'=>'required',
        'level'=>'required',

    );
    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return Response::json([
            'errors' => $errors
        ], 201);
    }

    if (request()->isMethod('POST')) {
        $input = request()->all();
        if (isset($input['title']) && $input['title'] != '' && $input['title'] != 'undefined') {
            $title = $input['title'];
        }
        if (isset($input['description']) && $input['description'] != '' && $input['description'] != 'undefined') {
            $description = $input['description'];
        }
        if (isset($input['category']) && $input['category'] != '' && $input['category'] != 'undefined') {
            $category = $input['category'];
        }
        if (isset($input['curricula']) && $input['curricula'] != '' && $input['curricula'] != 'undefined') {
            $curricula = $input['curricula'];
        }


        if (isset($input['Start_Date']) && $input['Start_Date'] != '' && $input['Start_Date'] != 'undefined') {
            $Start_Date = $input['Start_Date'];
        }
        if (isset($input['End_Date']) && $input['End_Date'] != '' && $input['End_Date'] != 'undefined') {
            $End_Date = $input['End_Date'];
        }
        if (isset($input['min_point']) && $input['min_point'] != '' && $input['min_point'] != 'undefined') {
            $min_point = $input['min_point'];
        }
        if (isset($input['max_point']) && $input['max_point'] != '' && $input['max_point'] != 'undefined') {
            $max_point = $input['max_point'];
        }
        if (isset($input['level']) && $input['level'] != '' && $input['level'] != 'undefined') {
            $level = $input['level'];
        }
        if (isset($input['domain']) && $input['domain'] != '' && $input['domain'] != 'undefined') {
            $domain = $input['domain'];
        }
        if (isset($input['pivot']) && $input['pivot'] != '' && $input['pivot'] != 'undefined') {
            $pivot= $input['pivot'];
        }
        if (isset($input['standard']) && $input['standard'] != '' && $input['standard'] != 'undefined') {
            $standard = $input['standard'];
        }



    }
    $teacher = Auth::user()->userid;

    $Lesson = new Lessons();
    $Lesson->title = $title;
    $Lesson->category = $category;
    $Lesson->description = $description;
    $Lesson->curricula = $curricula;
    $Lesson->teacher = $teacher;
    $Lesson->start_date = $Start_Date;
    $Lesson->close_date = $End_Date;
    $Lesson->min_point = $min_point;
    $Lesson->max_point = $max_point;
    $Lesson->level = $level;
    $Lesson->domain=$domain;
    $Lesson->pivot=$pivot;
    $Lesson->standard = $standard ;
    $Lesson->updated_at = date('Y-m-d h:m:h');
    $Lesson->save();
    return $this->index()->renderSections()['content'];
}

    /**
     * @param $lang
     * @param $id
     * @return mixed
     */
    public function saveedit($lang, $id)
    {
        $title = '';
        $description = '';
        $category = '-1';
        $level = '';
        $curricula = '';
        $Start_Date = '';
        $End_Date = '';
        $min_point = '';
        $max_point = '';
        $domain=-1;
        $pivot=-1;
        $standard=-1;
        $rules = array(
            'title' => 'required',
            'category'=>'required',
            'curricula'=>'required',
            'level'=>'required',

        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return Response::json([
                'errors' => $errors
            ], 201);
        }
        if (request()->isMethod('POST')) {
            $input = request()->all();
            if (isset($input['title']) && $input['title'] != '' && $input['title'] != 'undefined') {
                $title = $input['title'];
            }
            if (isset($input['description']) && $input['description'] != '' && $input['description'] != 'undefined') {
                $description = $input['description'];
            }
            if (isset($input['category']) && $input['category'] != '' && $input['category'] != 'undefined') {
                $category = $input['category'];
            }
            if (isset($input['curricula']) && $input['curricula'] != '' && $input['curricula'] != 'undefined') {
                $curricula = $input['curricula'];
            }

            if (isset($input['Start_Date']) && $input['Start_Date'] != '' && $input['Start_Date'] != 'undefined') {
                $Start_Date = $input['Start_Date'];
            }
            if (isset($input['End_Date']) && $input['End_Date'] != '' && $input['End_Date'] != 'undefined') {
                $End_Date = $input['End_Date'];
            }
            if (isset($input['min_point']) && $input['min_point'] != '' && $input['min_point'] != 'undefined') {
                $min_point = $input['min_point'];
            }
            if (isset($input['max_point']) && $input['max_point'] != '' && $input['max_point'] != 'undefined') {
                $max_point = $input['max_point'];
            }
            if (isset($input['level']) && $input['level'] != '' && $input['level'] != 'undefined') {
                $level = $input['level'];
            }
            if (isset($input['domain']) && $input['domain'] != '' && $input['domain'] != 'undefined') {
                $domain = $input['domain'];
            }
            if (isset($input['pivot']) && $input['pivot'] != '' && $input['pivot'] != 'undefined') {
                $pivot= $input['pivot'];
            }
            if (isset($input['standard']) && $input['standard'] != '' && $input['standard'] != 'undefined') {
                $standard = $input['standard'];
            }

        }
        $Lesson = Lessons::find($id);
        $Lesson->title = $title;
        $Lesson->category = $category;
        $Lesson->description = $description;
        $Lesson->curricula = $curricula;
        $Lesson->start_date = $Start_Date;
        $Lesson->close_date = $End_Date;
        $Lesson->min_point = $min_point;
        $Lesson->max_point = $max_point;
        $Lesson->level = $level;
        $Lesson->domain=$domain;
        $Lesson->pivot=$pivot;
        $Lesson->standard = $standard ;
        $Lesson->updated_at = date('Y-m-d h:m:h');
        $Lesson->save();
        return $this->index()->renderSections()['content'];

    }

    /**
     * @param $lang
     * @param $id
     * @return mixed
     */
    public function delete($lang, $id){
        LessonMidea::where('id_lesson',$id)->delete();
        Lessonassigns::where('id_lesson',$id)->delete();
      $lesson = Lessons::find($id)->delete();
      return $this->index()->renderSections()['content'];
    }

    /**
     * @return mixed
     */
    private function getCategoryStoryAPI()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => config('lms.URL_API') . "category?type=story",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $response = [];
        }

        return json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', (string)$response), true);
    }

    /**
     * @param $lang
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPivots($lang){
        $requast=\request()->all();
        $pivots=Domains::find($requast['id'])->pivots;
        return response()->json( $pivots);
    }

    /**
     * @param $lang
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStandards($lang){
        $requast=\request()->all();
        $standards=Pivot::find($requast['id'])->standards;
        return response()->json( $standards);
    }


    /**
     * @created_by hasan kiwan
     * @param $lang
     * @param Lessons $id
     * @return  view for assign lesson to groups
     */
    public function  assignLessonToGroups($lang,$id,Request $request){
        $lesson=Lessons::find($id);
        $tab='group';
        $idfirstlevel=Levels::first()->level_id;
        $teachers=Users::where('permession',Users::USER_TEACHER);
        $students=Users::where('permession',Users::USER_STUDENT);
        $groups=Groups::where([]);
        $levels=Levels::where([]);
        $classes=Classes::where([]);
        $levelStd=Levels::where([]);
        $classStd=Classes::where([]);
        $groupsStd=Groups::where([]);
        if(isset($request->teacher) && $request->teacher != -1){
            $groups->where('teacher',$request->teacher);
        }
        if (isset($request->level) && $request->level != -1){
            $classes->where('level',$request->level);
        }

        if (isset($request->levelstd) && $request->levelstd != -1){
            $students->where('level',$request->levelstd);
            $classStd->where('level',$request->levelstd);
        }else{
            $classStd->where('level',$idfirstlevel);
        }
        if (isset($$request->classstd) && $request->classstd != -1){
            $students->where('class',$request->classstd);
        }

        if (isset($request->tab) && $request->tab != 'undefined'){
            $tab=$request->tab;
        }
        if (isset($request->groupstd) && $request->groupstd != -1){
            $idusers=DB::table('lessonassigns')
                ->select('ref_id')
                ->where('product_type','group')
                ->where('product_id',$request->groupstd)
                ->where('ref_type','student')
                ->distinct()
                ->get()
                ->pluck('ref_id')
                ->toArray();
            $students->whereIn('userid',$idusers);
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


        return view('lessons.assign_lesson')
            ->withGroups($groups)
            ->withLevels($levels)
            ->withClasses($classes)
            ->withLevelStd($levelStd)
            ->withClassStd($classStd)
            ->withLesson($lesson)
            ->withTab($tab)
            ->withStudents($students);
    }

    public function addLessonTogroups($lang,$id,Request $request){
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
                $quizassign=DB::table('lessonassigns')->where('assigntype','group')->where('id_target',$target)->where('enddate','>=',$request->startDate)->where('id_lesson',$id)->first();
                if(!empty($quizassign)){
                    $errors=['errors'=>Lang::get('lang.Previously_Sent')];
                    return Response::json([
                        'errors' => $errors,
                    ], 201);
                }
                $arrData[]=[
                    'id_lesson'=>$id,
                    'startdate'=>$request->startDate,
                    'enddate'=>$request->endDate,
                    'assigntype'=>'group',
                    'id_target'=> intval($target),
                    'created_by'=> Auth::user()->userid,
                    'senddate'=>date('Y-m-d'),

                ];

            }
        }
        if(!empty($request->classes)){
            $type='class';
            foreach ($request->classes as $target){
                $quizassign=DB::table('lessonassigns')->where('assigntype','class')->where('id_target',$target)->where('enddate','>=',$request->startDate)->where('id_lesson',$id)->first();
                if(!empty($quizassign)){
                    $errors=['errors'=>Lang::get('lang.Previously_Sent')];
                    return Response::json(['errors' => $errors,], 201);
                }
                $arrData[]=[
                    'id_lesson'=>$id,
                    'startdate'=>$request->startDate,
                    'enddate'=>$request->endDate,
                    'assigntype'=>$type,
                    'id_target'=> intval($target),
                    'created_by'=> Auth::user()->userid,
                    'senddate'=>date('Y-m-d'),

                ];

            }
        }
        if(!empty($request->students)){
            $type='student';
            foreach ($request->students as $target){
                $quizassign=DB::table('lessonassigns')->where('assigntype','student')->where('id_target',$target)->where('enddate','>=',$request->startDate)->where('id_lesson',$id)->first();
                if(!empty($quizassign)){
                    $errors=['errors'=>Lang::get('lang.Previously_Sent')];
                    return Response::json(['errors' => $errors,], 201);
                }
                $arrData[]=[
                    'id_lesson'=>$id,
                    'startdate'=>$request->startDate,
                    'enddate'=>$request->endDate,
                    'assigntype'=>$type,
                    'id_target'=> intval($target),
                    'created_by'=> Auth::user()->userid,
                    'senddate'=>date('Y-m-d'),

                ];

            }
        }

        if(empty($request->groups) && empty($request->classes) && empty($request->students)){
            $errors=['error'=>'must be select groups or classes or students to assign lesson'];
            return Response::json([
                'errors' => $errors,
            ], 201);
        }
        DB::table('lessonassigns')->insert($arrData);

        $errors=['errors'=>Lang::get('lang.Successfully_Add')];
        return Response::json([
            'errors' => $errors,
        ], 201);
    }



}

?>