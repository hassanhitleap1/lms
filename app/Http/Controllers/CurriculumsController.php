<?php

namespace App\Http\Controllers;

use App\Curriculums;
use App\Helper\UserHelper;
use App\Lessons;
use App\Levels;
use App\Categories;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Response;




class CurriculumsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // to get all curricula
    public function index(){
        $orderby='curriculums.created_at';
        $descask='desc';
        if(isset($_GET["orderby"])  && $_GET["orderby"]!=-1){
            $orderby='curriculums.'.$_GET["orderby"];
        }
        if(isset($_GET["descask"])  && $_GET["descask"]!=-1){
            $descask=$_GET["descask"];
        }

        $Curricula = DB::table('curriculums')
            ->join('categories', 'categories.category_id', '=', 'curriculums.cu_category')
            ->join('levels', 'levels.level_id', '=', 'curriculums.cu_level')
            ->where(function($q){
                if(isset($_GET["curricula_cat"])  && $_GET["curricula_cat"]!=-1){
                    $q->where('curriculums.cu_category',$_GET["curricula_cat"]);
                }

                if(isset($_GET["curricula_level"])  && $_GET["curricula_level"]!=-1){
                    $q->where('curriculums.cu_level',$_GET["curricula_level"]);
                }
                if(isset($_GET["domain"])  && $_GET["domain"]!=-1){
                    $careculimid=DB::table('lessons')
                        ->select('curricula')
                        ->where('domain',$_GET["domain"])
                        ->get()->pluck('curricula');
                    $q->whereIn('curriculumsid',$careculimid);
                }
                if(isset($_GET["pivot"])  && $_GET["pivot"]!=-1){
                    $careculimid=DB::table('lessons')
                        ->select('curricula')
                        ->where('pivot',$_GET["pivot"])
                        ->get()->pluck('curricula');
                    $q->whereIn('curriculumsid',$careculimid);
                }
                if(isset($_GET["standard"])  && $_GET["standard"]!=-1){
                    $careculimid=DB::table('lessons')
                        ->select('curricula')
                        ->where('standard',$_GET["standard"])
                        ->get()->pluck('curricula');
                    $q->whereIn('curriculumsid',$careculimid);
                }

                if(Users::isStudent()){
                    $q->where('curriculums.cu_level',Auth::user()->level);
                }
                if(Users::isParent()){
                    $levelsChild =Auth::user()->students->pluck('studentInfo.level');
                    $q->whereIn('curriculums.cu_level',$levelsChild);
                }
                if(Users::isTeacher()){
                    $levelsid=DB::table('schedule')->select('schedule.*','classes.*')
                            ->join('classes','schedule.class','=','classes.class_id')
                            ->where('schedule.teacher',Auth::user()->userid)
                            ->get()
                            ->pluck('level')
                            ->toArray();
                    $q->whereIn('curriculums.cu_level',$levelsid);
                }
            })-> orderBy($orderby, $descask)->paginate(config('lms.pagination'));

        $categories=Categories::all();
        $levels=Levels::where([]);
        if(Users::isStudent()){
            $levels=$levels->where('level_id',Auth::user()->level);
        }
        if(Users::isParent()){
           $levelsChild =Auth::user()->students->pluck('studentInfo.level');
            $levels=$levels->whereIn('level_id',$levelsChild);
        }
        $levels=$levels->get();
        $Curricula->setPath('');
        return view('curriculums.index')->with("curricula",$Curricula)->with("categories",$categories)->with("levels",$levels);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        //show form to add new curriculum
        $page = request()->get('page', 1);
        $grade = 0;
        $search = '';
        $categorybooks=0;
        $level_book=1;
        $adddata='';
        if (request()->isMethod('POST')) {
            $input = request()->all();
            if (isset($input['search']) && $input['search'] != '' && $input['search'] != 'undefined') {
                $search = $input['search'];
            }
            if (isset($input['categorybooks']) && $input['categorybooks'] != '' && $input['categorybooks'] != 'undefined') {
                $categorybooks = $input['categorybooks'];
            }
            if (isset($input['level_book']) && $input['level_book'] != '' && $input['level_book'] != 'undefined') {
                $level_book = $input['level_book'];
            }
            if (isset($input['adddata']) && $input['adddata'] != '' && $input['adddata'] != 'undefined') {
                $adddata = $input['adddata'];
            }
        }
        $categories_books = config('lms.Categories');
        $categories=Categories::all();
        $levels=Levels::all();
        $books=$this->getDataBooksAPI("books",$categorybooks,'', $search,$page);
        return view('curriculums.add',['categories'=>$categories,'categories_books'=>$categories_books,'levels'=>$levels,'books'=>$books,'search'=>$search,'categorybooks'=>$categorybooks,'level_book'=>$level_book,'adddata'=>$adddata]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        // check permession - Hussam
        if(Auth::user()->permession !=1){
            return false;
        }


        //insert new curricula
        $curriculums=new Curriculums();
        $rules = array(
            'title_ar' => 'required',
            'title_en' => 'required'
        );
        $curricula_title='';
        $thumbnail_img='';
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $errors=$validator->errors()->all();
            return Response::json([
                'errors' => $errors
            ], 201);
        }
        $curriculums->cu_title_ar=$request->title_ar;
        $curriculums->cu_title_en=$request->title_en;
        $curriculums->cu_description_ar=$request->description_ar;
        $curriculums->cu_description_en=$request->description_en;
        $curriculums->cu_level=$request->level;
        $curriculums->cu_category=$request->category;
        $curriculums->cu_book=$request->cu_book;
        $curriculums->title_book=$request->curricula_title;
        $curriculums->thumb_book=$request->thumbnail_img_curricula;
        $curriculums->save();

        //import lessons from Manhal.com
        if(isset($request->cu_book) && $request->cu_book>0){
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => config('lms.URL_API') . "unitslesson?type=lesson&id=".$request->cu_book,
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
            $lessons = json_decode($response, true);
            foreach($lessons as $lesson){
                DB::table('lessons')->insert([
                    [
                        'title' => $lesson["title"],
                        'description' => '',
                        'level' => $request->level,
                        'category' => $request->category,
                        'curricula' =>  $curriculums->curriculumsid,
                        'bookid' =>  $lesson["bookid"],
                        'ulid' => $lesson["ulid"],
                        'pageid' =>  $lesson["pageid"],
                        'unitid' =>  $lesson["unitid"],
                        'teacher' => Auth::user()->userid,
                        'start_date'=>date('Y-m-d'),
                        'close_date'=>date('Y-m-d'),

                    ]
                ]);
            }
        }
        $Curricula = DB::table('curriculums')
            ->join('categories', 'categories.category_id', '=', 'curriculums.cu_category')
            ->join('levels', 'levels.level_id', '=', 'curriculums.cu_level')
            ->where(function($q){
                if(isset($_GET["curricula_cat"])  && $_GET["curricula_cat"]!=-1){
                    $q->where('curriculums.cu_category',$_GET["curricula_cat"]);
                }

                if(isset($_GET["curricula_level"])  && $_GET["curricula_level"]!=-1){
                    $q->where('curriculums.cu_level',$_GET["curricula_level"]);
                }

            })-> orderBy('curriculums.created_at', 'desc')->paginate(config('lms.pagination'));
        $Curricula->setPath('');
        $categories=Categories::all();
        $levels=Levels::all();
        return view('curriculums.index')->with("curricula",$Curricula)->with("categories",$categories)->with("levels",$levels)->renderSections()['content'];
    }

    /**
     * @param string $mediatype
     * @param int $category
     * @param string $grade
     * @param string $search
     * @param int $page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function getDataBooksAPI($mediatype="books", $category=0, $grade="", $search="", $page=1)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => config('lms.URL_API') . "media?type=books&category=" . $category . "&grade=" . $grade . "&keyword=" . $search."&page=".$page,
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
        $books = json_decode($response, true);
        $collection = collect($books);
        $data = new \Illuminate\Pagination\LengthAwarePaginator(
            $collection->forPage($page, config('lms.pagination')),
            $collection->count(),
            config('lms.pagination'),
            $page
        );

        $data->setPageName('');
        $data->setPath('');
       return $data;
    }

    /**
     * @param $lang
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewedit($lang,$id){
    $page = request()->get('page', 1);
    $grade = 0;
    $search = '';
    $categorybooks=0;
        $level_book=1;
    if (request()->isMethod('POST')) {
        $input = request()->all();

        if (isset($input['search']) && $input['search'] != '' && $input['search'] != 'undefined') {
            $search = $input['search'];
        }
        if (isset($input['categorybooks']) && $input['categorybooks'] != '' && $input['categorybooks'] != 'undefined') {
                    $categorybooks = $input['categorybooks'];
                }
                if (isset($input['level_book']) && $input['level_book'] != '' && $input['level_book'] != 'undefined') {
                    $level_book = $input['level_book'];
                }

    }

    $Curricula = Curriculums::find($id);
    $categories_books = config('lms.Categories');
    $categories=Categories::all();
    $levels=Levels::all();
    $books=$this->getDataBooksAPI("books",$categorybooks,'', $search,$page);
    return view('curriculums.edit',['id'=>$id,'curricula'=>$Curricula,'categories'=>$categories,'categories_books'=>$categories_books,'levels'=>$levels,'books'=>$books,'search'=>$search,'categorybooks'=>$categorybooks,'level_book'=>$level_book]);
}

    /**
     * @param $lang
     * @param $id
     * @return mixed
     */
    public function delete_curr($lang,$id){
        $Curricula = Curriculums::find($id)->delete();
        return $this->index()->renderSections()['content'];
    }

    /**
     * @return mixed
     */
    public function saveedit()
    {
        if (request()->isMethod('POST')) {
            $input = request()->all();
            $title_ar = '';
            $title_en = '';
            $description_ar = '';
            $description_en = '';
            $category = '';
            $level = '';
            $idbook = '';
            $curricula_title = '';
            $thumbnail_img = '';
            $idcorriculum = '';
            if (isset($input['title_ar']) && $input['title_ar'] != '' && $input['title_ar'] != 'undefined') {
                $title_ar = $input['title_ar'];
            }
            if (isset($input['title_en']) && $input['title_en'] != '' && $input['title_en'] != 'undefined') {
                $title_en = $input['title_en'];
            }
            if (isset($input['description_ar']) && $input['description_ar'] != '' && $input['description_ar'] != 'undefined') {
                $description_ar = $input['description_ar'];
            }
            if (isset($input['description_en']) && $input['description_en'] != '' && $input['description_en'] != 'undefined') {
                $description_en = $input['description_en'];
            }
            if (isset($input['category']) && $input['category'] != '' && $input['category'] != 'undefined') {
                $category = $input['category'];
            }
            if (isset($input['level']) && $input['level'] != '' && $input['level'] != 'undefined') {
                $level = $input['level'];
            }
            if (isset($input['idbook']) && $input['idbook'] != '' && $input['idbook'] != 'undefined') {
                $idbook = $input['idbook'];
            }
            if (isset($input['curricula_title']) && $input['curricula_title'] != '' && $input['curricula_title'] != 'undefined') {
                $curricula_title = $input['curricula_title'];
            }
            if (isset($input['thumbnail_img']) && $input['thumbnail_img'] != '' && $input['thumbnail_img'] != 'undefined') {
                $thumbnail_img = $input['thumbnail_img'];
            }
            if (isset($input['idcorriculum']) && $input['idcorriculum'] != '' && $input['idcorriculum'] != 'undefined') {
                $idcorriculum = $input['idcorriculum'];
            }
            $rules = array(
                'title_ar' => 'required',
                'title_en' => 'required',
                'category' => 'required',
                'level' => 'required',
                'idbook' => 'required',
                'idcorriculum' => 'required',
            );
            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                return Response::json([
                    'errors' => $errors
                ], 201);
            }

            $Curricula = Curriculums::find($idcorriculum);
            $Curricula->cu_category = $category;
            $Curricula->cu_level = $level;
            $Curricula->cu_title_ar = $title_ar;
            $Curricula->cu_title_en = $title_en;
            $Curricula->cu_description_ar = $description_ar;
            $Curricula->cu_description_en = $description_en;
            $isCheange=($Curricula->cu_book ==$idbook)?true:false;
            $Curricula->cu_book = $idbook;
            $Curricula->title_book = $curricula_title;
            $Curricula->thumb_book = $thumbnail_img;
            $Curricula->updated_at = date('Y-m-d h:m:h');
            $Curricula->save();
            $lessonsStored = DB::table('lessons')->select('id')
                ->where('curricula', $Curricula->curriculumsid)->get();
            DB::table('lessons')
                ->where('curricula', $Curricula->curriculumsid)->update([
                    'category'=>$category,
                    'level'=>$level
                ]);

            if ($lessonsStored->count()==0) {
                if (isset($idbook) && $idbook > 0) {
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => config('lms.URL_API') . "unitslesson?type=lesson&id=" . $idbook,
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
                    $lessons = json_decode($response, true);
                    foreach ($lessons as $lesson) {
                        DB::table('lessons')->insert([
                            [
                                'title' => $lesson["title"],
                                'description' => '',
                                'level' => $level,
                                'category' => $category,
                                'curricula' => $Curricula->curriculumsid,
                                'bookid' => $idbook,
                                'ulid' => $lesson["ulid"],
                                'pageid' => $lesson["pageid"],
                                'unitid' => $lesson["unitid"],
                                'teacher' => Auth::user()->userid
                            ]
                        ]);
                    }
                }

            } elseif ($isCheange){
                return $this->index()->renderSections()['content'];
            }else{
                return Response::json([
                    'message' =>'are you sure',
                    'code'=>203,
                    'curriculumsid'=>$Curricula->curriculumsid,
                    'level'=>$level,
                    'category'=>$category,
                    'idbook'=>$idbook,
                ], 203);
            }

            return $this->index()->renderSections()['content'];
        }
    }

    public  function deleteAndAddLesson($lang,Request $request){
        $idbook=-1;
        $level=-1;
        $category=1;
        $curriculumsid=-1;

        if (request()->isMethod('GET')) {
            $input = request()->all();
            $idbook=$input["idbook"];
            $level=$input["level"];
            $category=$input["category"];
            $curriculumsid=$input["curriculumsid"];
        }
        if(isset($idbook) && $idbook>0 && $curriculumsid != -1){
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => config('lms.URL_API') . "unitslesson?type=lesson&id=".$idbook,
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
            $lessons = json_decode($response, true);

            $ModelLessonsDelelte=Lessons::where('curricula',$curriculumsid)->delete();

            foreach($lessons as $lesson){
                DB::table('lessons')->insert([
                    [
                        'title' => $lesson["title"],
                        'description' => '',
                        'level' =>$level,
                        'category' => $category,
                        'curricula' =>  $curriculumsid,
                        'bookid' =>  $idbook,
                        'ulid' => $lesson["ulid"],
                        'pageid' =>  $lesson["pageid"],
                        'unitid' =>  $lesson["unitid"],
                        'teacher' => Auth::user()->userid
                    ]
                ]);
            }
        }
        return $this->index()->renderSections()['content'];
    }
    /**
     * @return mixed
     */
    public function syllabus(){

        $Curricula = DB::table('curriculums')
            ->join('categories', 'categories.category_id', '=', 'curriculums.cu_category')
            ->join('levels', 'levels.level_id', '=', 'curriculums.cu_level')
            ->where(function($q){
                if(isset($_GET["curricula_cat"])  && $_GET["curricula_cat"]!=-1){
                    $q->where('curriculums.cu_category',$_GET["curricula_cat"]);
                }

                if(isset($_GET["curricula_level"])  && $_GET["curricula_level"]!=-1){
                    $q->where('curriculums.cu_level',$_GET["curricula_level"]);
                }

            })->paginate(config('lms.pagination'));

        $categories=Categories::all();
        $levels=Levels::all();
        return view('curriculums.index')->with("curricula",$Curricula)->with("categories",$categories)->with("levels",$levels);

    }


}
