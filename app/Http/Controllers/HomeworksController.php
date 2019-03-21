<?php

namespace App\Http\Controllers;

use App\Categories;
use App\Classes;
use App\Groups;
use App\Helper\NotificationHelper;
use App\Helper\SqlHelper;
use App\HomeworkAssign;
use App\Homeworkmedia;
use App\Levels;
use App\Users;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Homeworks;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;

use PhpParser\Node\Expr\Array_;
use Response;

class HomeworksController extends BaseController
{

    public $path='?';
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $orderBy = 'created_at';
        $DescAsk = 'ASC';
        $search = '';
        $levels=Levels::all();
        $categories=Categories::all();
        $query=Homeworks::where([]);
        if (request()->isMethod('GET')) {
            $input = request()->all();
            if (isset($input['descask']) && $input['descask'] != '') {
                $DescAsk = $input['descask'];
                $this->path.='&descask='.$DescAsk;
            }
            if (isset($input['orderby']) && $input['orderby'] != '') {
                $orderBy = $input['orderby'];
                $this->path.='&orderby='.$orderBy;
            }
            if (isset($input['category']) && $input['category'] != -1) {
                $query->where('category', $input['category']);
                $this->path.='&category='. $input['category'];
            }
            if (isset($input['level']) && $input['level'] != -1) {
                $level=$input['level'];
                $idsUsers=  Users::where('level',$level)->get()->pluck('userid');
                $classes_ides=DB::table('classes')->select('class_id')->where('level',$level)->get()->pluck('class_id');
                $idsHomework=DB::table('homeworkassign')->select('id_homework')
                    ->orWhere(function($query) use ($idsUsers){
                        $query->orWhere('assigntype', 'student')
                            ->whereIn('id_target',$idsUsers);
                    })
                    ->orWhere(function($query) use ($classes_ides){
                        $query->where('assigntype', 'classes')
                            ->whereIn('id_target',$classes_ides);
                    })
                    ->get()
                    ->pluck('id_homework');

                $query->whereIn('homework_id',$idsHomework);
                $this->path.='&level='. $input['level'];
            }
            if (isset($input['search']) && $input['search'] != '') {
                $search = $input['search'];
                $query->where('title', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%');
                $this->path.='&search='. $input['search'];
            }
            if (isset($input['teacher']) && $input['teacher'] != -1) {
                $query->where('teacher', $input['teacher']);
                $this->path.='&teacher='. $input['teacher'];
            }
            if(Users::isTeacher()){
                $query->where('teacher', Auth::user()->userid);
            }
            if(Users::isStudent()){

                $homeworkassign=DB::table('homeworkassign')
                    ->select('homeworkassign.*')
                    ->where(function ($q){
                        $q->orwhere(function($query){
                            $query->where('assigntype', 'classes')
                                ->where('id_target', Auth::user()->class);
                        }) ;
                        $q->orwhere(function($query){
                            $query->where('assigntype', 'student')
                                ->where('id_target', Auth::user()->userid);
                        });
                        $q->orwhere(function($query){
                            $groupsid= Auth::user()->assignsGroupsStudent->pluck('product_id');
                            $query->where('assigntype', 'group')
                                ->whereIn('id_target', $groupsid);
                        });
                    })->distinct()
                    ->get()
                    ->pluck('id_homework')
                    ->toArray();
                $query->whereIn('homework_id', $homeworkassign);
            }

            if(Users::isParent()){
                $child=DB::table('parents')
                    ->join('users','users.userid','=','parents.student_id')
                    ->where('parent_id',Auth::user()->userid)->get();

                $homeworkassign=DB::table('homeworkassign')
                    ->select('homeworkassign.*')
                    ->where(function ($q)use ($child){
                        $q->orwhere(function($query)use ($child){
                            $query->where('assigntype', 'classes')
                                ->whereIn('id_target', $child->pluck('class')->toArray());
                        });
                        $q->orwhere(function($query)use ($child){
                            $query->where('assigntype', 'student')
                                ->whereIn('id_target', $child->pluck('userid')->toArray());
                        });
                        $q->orwhere(function($query)use ($child){
                            $groupsid=DB::table('assigns')
                                ->where('product_type','=','group')
                                ->where('ref_type','=','student')
                                ->whereIn('ref_id',$child->pluck('userid')->toArray())
                                ->get()
                                ->pluck('product_id')
                                ->toArray();
                            $query->where('assigntype', 'group')
                                ->whereIn('id_target', $groupsid);
                        });
                    })->get()
                    ->pluck('id_homework')
                    ->toArray();
                $query->whereIn('homework_id', $homeworkassign);

            }

        }

        $homework=$query->orderBy($orderBy, $DescAsk)->paginate(config('lms.pagination'));
        $homework->setPath($this->path);
        return view('homework.index', [
            'search' => $search,
            'descask' => $DescAsk,
            'orderBy' => $orderBy,
        ])->with('homework',$homework)
            ->with('categories',$categories)
            ->with('levels',$levels);

    }

    public function viewAdd()
    {
        $categories = Categories::all();

        return view('homework.add')->with('categories', $categories);
    }

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

    private function mymedia($id, $page)
    {
        $query = DB::table('homeworkmedia')->where('id_homework', '=', $id)->get();
        $query = json_decode($query, true);
        $collection = collect($query);
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

    public function viewmymedia()
    {
        $id = 0;
        $page = request()->get('page', 1);
        $perPage = config('lms.pagination');
        $mediatype = 'games';
        $categorymedia = -1;
        $grade = 0;
        $search = '';
        if (request()->isMethod('GET')) {
            $input = request()->all();
            if (isset($input['idhomework']) && $input['idhomework'] != '') {
                $id = $input['idhomework'];
            }
            if (isset($input['type']) && $input['type'] != '') {
                $mediatype = $input['type'];
            }
            if (isset($input['category']) && $input['category'] != '') {
                $categorymedia = $input['category'];
            }
            if (isset($input['grade']) && $input['grade'] != '') {
                $grade = $input['grade'];
            }
            if (isset($input['search']) && $input['search'] != '') {
                $search = $input['search'];

            }
        }
        $mymedia = json_decode($this->mymedia($id), true);

        // return view('homework.media',['search' => $search, 'media' => $data, 'categories' => $categories,'type'=>$mediatype,'categorymedia'=>$categorymedia,'grade'=>$grade,'idhomework'=>$id,'mymedia'=>$mymedia]);
    }

    public function viewmedia($lang, $id)
    {
        $page = request()->get('page', 1);
        $mediatype = 'games';
        $categorymedia = -1;
        $grade = 0;
        $search = '';
        $tab = 'mymedia';
        $Allmedia = [];
        $mymedia = [];
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
            if (isset($input['search']) ) {
                $search = $input['search'];
                $search=str_replace('%',' ',$search);
            }
            if (isset($input['tab']) && $input['tab'] != '') {
                $tab = $input['tab'];
            }
        }
        $categories = config('lms.Categories');
        $mymedia = $this->mymedia($id, $page);
        $mymedia->setPath('');
        if ($tab == 'AllMedia') {

            if ($mediatype == 'stories') {

                $categories = $this->getCategoryStoryAPI();
                // return $categories;
            }
            $Allmedia = $this->getmediaAPI($mediatype, $categorymedia, $grade, $search, $page);


        }

        return view('homework.media', ['search' => $search, 'media' => $Allmedia, 'categories' => $categories, 'type' => $mediatype, 'categorymedia' => $categorymedia, 'grade' => $grade, 'idhomework' => $id, 'mymedia' => $mymedia, 'tab' => $tab]);
    }

   //new function to show homework editor as lesson editor - by Hussam
    public function editor($lang, $id)
    {

        if (request()->isMethod('GET')) {

            $Lesson = DB::table('homeworks')
                ->join('categories', 'homeworks.category', '=', 'categories.category_id')
                ->where([["homeworks.homework_id", "=", $id]])
                ->first();

            $Media = DB::table('homeworkmedia')
                ->join('categories', 'homeworkmedia.category', '=', 'categories.category_id')
                ->where([["homeworkmedia.id_homework", "=", $id]])
                ->get();
            $Lesson->ltitle_ar=$Lesson->title;
            $Lesson->ltitle_en=$Lesson->title;
            $Lesson->id=$Lesson->homework_id;
            return view('lessonsbuilder.index')->with("Lesson", $Lesson)->with("Media", $Media);
        }
    }

    public function deletemymedia()
    {
        $id = '';
        $idhomework = '';
        if (request()->isMethod('GET')) {
            $input = request()->all();
            if (isset($input['id']) && $input['id'] != '') {
                $id = $input['id'];
                $mymedia = DB::table('homeworkmedia')->where('id', '=', $id)->delete();
            }
            if (isset($input['idhomework']) && $input['idhomework'] != '') {
                $idhomework = $input['idhomework'];
                return $this->viewmedia('', $idhomework);
            }
        }
        return viewmedia('', $id);
    }

    public function addmedia()
    {
        $mediatype = '';
        $id = '';
        $idhomework = '';
        $thumbnail = '';
        $title_ar = '';
        $title_en = '';
        $url='';

        if (request()->isMethod('GET')) {
            $input = request()->all();
            if (isset($input['type']) && $input['type'] != '') {
                $mediatype = $input['type'];
            }
            if (isset($input['id']) && $input['id'] != '') {
                $id = $input['id'];
            }
            if (isset($input['idhomework']) && $input['idhomework'] != '') {
                $idhomework = $input['idhomework'];
            }
            if (isset($input['thumbnail']) && $input['thumbnail'] != '') {
                $thumbnail = $input['thumbnail'];
            }
            if (isset($input['title_ar']) && $input['title_ar'] != '') {
                $title_ar = $input['title_ar'];
            }
            if (isset($input['title_en']) && $input['title_en'] != '') {
                $title_en = $input['title_en'];
            }
            if (isset($input['url']) && $input['url'] != '') {
                $url = $input['url'];
            }
        }

        if ($id != '' && $idhomework != '' && $mediatype != '') {
            $media = DB::table('homeworkmedia')->where('id_homework', '=', $idhomework)->where('id_media', '=', $id)->where('type', '=', $mediatype)->get();
            if (!$media->isEmpty()) {

            } else {
                DB::table('homeworkmedia')->insert(['id' => null, 'id_homework' => $idhomework, 'id_media' => $id, 'type' => $mediatype, 'thumbnail' => $thumbnail, 'title_ar' => $title_ar, 'title_en' => $title_en,'url'=>$url]);
            }
        }


    }

    public function viewassign($lang, $id)
    {
        $page = request()->get('page', 1);
        $type = 'group';
        $Group = [];
        $Classes = [];
        $classe_id = 0;
        $level_id = 0;
        $group_id = 0;
        $today=date('Y-m-d');
        $student_fillter = 0;
        $levels=DB::table('levels');
        if (request()->isMethod('GET')) {
            $input = request()->all();
            if (isset($input['type']) && $input['type'] != '' && $input['type'] != 'undefined') {
                $type = $input['type'];
            }
            if (isset($input['level']) && $input['level'] != '' && $input['level'] != -1) {
                $level_id = $input['level'];
            }
            if (isset($input['classe']) && $input['classe'] != '' && $input['classe'] != -1) {
                $classe_id = $input['classe'];
            }
            if (isset($input['group']) && $input['group'] != '' && $input['group'] != -1) {
                $group_id = $input['group'];
            }
        }
        $idclass=[];
        $idslevels=[];
        if(Users::isTeacher()){
            $idslevels=DB::table('schedule')
                ->select('classes.level')
                ->join('classes','classes.class_id','=','schedule.class')
                ->where('schedule.teacher',Auth::user()->userid)
                ->distinct()->get()->pluck('level')->toArray();
            $idclass=DB::table('schedule')
                ->select('class')->where('teacher',Auth::user()->userid)
                ->distinct()->get()->pluck('class')->toArray();
            $levels->whereIn('level_id',$idslevels);
        }
        switch ($type) {
            case 'group':
                $query = DB::table('groups');
                if(Users::isTeacher()){
                    $query->where('teacher',Auth::user()->userid);
                }
                $query=$query->get();
                $collection = collect($query);
                $data = new \Illuminate\Pagination\LengthAwarePaginator(
                    $collection->forPage($page, config('lms.pagination')),
                    $collection->count(),
                    10,
                    $page
                );
                break;
            case 'student':
                $query = DB::table('users');
                if ($level_id == 0) {
                    $level_id = DB::table('levels')->select('level_id')->value('level_id');
                }
                if ($classe_id == 0) {
                    $classe_id = DB::table('classes')->where('level', $level_id)->select('class_id')->value('class_id');
                }

                $Classes = DB::table('classes')->where('level', $level_id);

                if ($group_id == 0) {

                    $group_id = DB::table('groups')->select('group_id')->value('group_id');
                    $query = $query->where('level', $level_id)->where('class', $classe_id)->where('permession', 5);
                } else {
                    $student_fillter = 1;
                    $query = $query->leftJoin('assigns', 'users.userid', '=', 'assigns.ref_id')->where('product_id', '=', $group_id)->where('product_type', '=', 'group');
                }


                $Group = DB::table('groups');
                if(Users::isTeacher()){
                    $Classes->whereIn('class_id',$idclass);
                    $Group->where('teacher',Auth::user()->userid);
                }
                $Classes = $Classes->get();
                $Group = $Group->get();
                $query=$query->get();
                $collection = collect($query);
                $data = new \Illuminate\Pagination\LengthAwarePaginator(
                    $collection->forPage($page, config('lms.pagination')),
                    $collection->count(),
                    config('lms.pagination'),
                    $page
                );
                break;
            case 'classes':
                if ($level_id == 0) {
                    $level_id = DB::table('levels')->select('level_id')->value('level_id');
                }
                $query = DB::table('classes')->where('level', $level_id);
                if (Users::isTeacher()){
                    $query->whereIn('class_id',$idclass);
                }
               $query=$query->get();
                $collection = collect($query);
                $data = new \Illuminate\Pagination\LengthAwarePaginator(
                    $collection->forPage($page, config('lms.pagination')),
                    $collection->count(),
                    config('lms.pagination'),
                    $page
                );

                break;
        }
        $levels = $levels->get();
        return view('homework.assign', ['data' => $data, 'idhomework' => $id, 'type' => $type, 'levels' => $levels, 'level_id' => $level_id, 'classes' => $Classes, 'group' => $Group, 'classe_id' => $classe_id, 'group_id' => $group_id, 'student_fillter' => $student_fillter]);


    }

    public function addassign()
    {
        $type = '';
        $All_user = [];
        $idhomework = 0;
        $startdate = 0;
        $enddate = 0;
        $flag = '';
        if (request()->isMethod('GET')) {
            $input = request()->all();
            if (isset($input['type']) && $input['type'] != '' && $input['type'] != 'undefined') {
                $type = $input['type'];
            }
            if (isset($input['All_user']) && $input['All_user'] != '' && $input['All_user'] != 'undefined') {
                $All_user = $input['All_user'];
            }
            if (isset($input['idhomework']) && $input['idhomework'] != '' && $input['idhomework'] != 'undefined') {
                $idhomework = $input['idhomework'];
            }
            if (isset($input['startdate']) && $input['startdate'] != '' && $input['startdate'] != 'undefined') {
                $startdate = $input['startdate'];
            }
            if (isset($input['enddate']) && $input['enddate'] != '' && $input['enddate'] != 'undefined') {
                $enddate = $input['enddate'];
            }
        }
        $this->notifUserHomeworks($type,$All_user,$idhomework);
        $senddate = date("Y/m/d");
        foreach ($All_user as $item) {
            $media = DB::table('homeworkassign')->where('id_homework', '=', $idhomework)->where('startdate', '=', $startdate)->where('enddate', '=', $enddate)->where('assigntype', '=', $type)->where('id_target', '=', $item)->get();
            if (!$media->isEmpty()) {
                $flag = 0;
            } else {
                $flag = 1;
                DB::table('homeworkassign')->insert(
                    ['id' => null, 'id_homework' => $idhomework, 'startdate' => $startdate, 'enddate' => $enddate, 'assigntype' => $type, 'id_target' => $item, 'senddate' => $senddate]
                );
            }
        }
        return $flag;
    }

    public function browseassignment()
    {
        $query = DB::table('homeworkassign')
            ->select('homeworkassign.*', 'homeworks.title as name_homework','levels.ltitle_ar', 'levels.ltitle_en', DB::raw("(IF(homeworkassign.assigntype='group',groups.title_ar,IF(homeworkassign.assigntype='classes',classes.ctitle_ar,IF(homeworkassign.assigntype='student',users.uname,'')))) AS title_ar"), DB::raw("(IF(homeworkassign.assigntype='group',groups.title_en,IF(homeworkassign.assigntype='classes',classes.ctitle_en,IF(homeworkassign.assigntype='student',users.uname,'')))) AS title_en"))
            ->join('homeworks','homeworkassign.id_homework','=','homeworks.homework_id')
            ->leftJoin("groups", function ($join) {
                $join->on('groups.group_id', '=', 'homeworkassign.id_target')
                    ->where('homeworkassign.assigntype', 'group');
            })
            ->leftJoin("classes", function ($join) {
                $join->on('classes.class_id', '=', 'homeworkassign.id_target')
                    ->where('homeworkassign.assigntype', 'classes');
            })
            ->leftJoin("users", function ($join) {
                $join->on('users.userid', '=', 'homeworkassign.id_target')
                    ->where('homeworkassign.assigntype', 'student');
            })->leftJoin("levels", function ($join) {
                $join->on('levels.level_id', '=', 'users.level');
                $join->orOn('levels.level_id', '=', 'classes.level');
            });



        if (request()->isMethod('GET')) {
            $input = request()->all();
            if (isset($input['homework']) && $input['homework'] != '' && $input['homework'] != -1 ) {
                $query->where('homeworkassign.id_homework',$input['homework']);
            }
        }
        $homeworksQeary=Homeworks::where([]);

        if(Users::isTeacher()){
            $homeworksQeary->where('teacher',Auth::user()->userid);
        }

        if(Users::isStudent()){
            $qerayIdes= DB::table('homeworkassign')
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
                    ->select('homeworkassign.id_homework')
                >get();

            $homeworksQeary->whereIn('homework_id',$qerayIdes);

        }

        $query=$query->get();
        $homeworks=$homeworksQeary->get();
        //return $query;
        return view('homework.browseassignment', ['data' => $query])->with('homeworks',$homeworks);
    }

    public function showresult($lang, $id, $idtarget)
    {

        $query = Homeworks::where('homeworks.homework_id', '=', $id)->select('homeworks.title', 'homeworks.description', 'categories.title_ar', 'categories.title_en', 'homeworkassign.*')
            ->where('homeworks.homework_id', '=', $id)
            ->leftJoin("categories", function ($join) {
                $join->on('categories.category_id', '=', 'homeworks.category');
            })->leftJoin("homeworkassign", function ($join) use ($idtarget) {
                $join->on('homeworkassign.id_homework', '=', 'homeworks.homework_id')
                    ->where('homeworkassign.id_target', '=', $idtarget);
            })
            ->get();

        $query2 = DB::table("homeworkresult")->where('homeworkresult.id_assign', '=', $idtarget)
            ->leftJoin("users", function ($join) {
                $join->on('users.userid', '=', 'homeworkresult.id_user');
            })
            ->leftJoin("levels", function ($join) {
                $join->on('levels.level_id', '=', 'users.level')
                ;
            })->leftJoin("classes", function ($join) {
                $join->on('classes.class_id', '=', 'users.class');
            })
            ->select('users.uname', 'homeworkresult.*', 'levels.ltitle_ar', 'levels.ltitle_en', 'classes.ctitle_ar', 'classes.ctitle_en')
            ->get();

        return view('homework.showresult', ['data' => $query, 'users' => $query2]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $orderBy = 'created_at';
        $DescAsk = 'ASC';
        $search = '';
        $input = request()->all();
        $homework = new Homeworks();
        $homework->title = $input['title'];
        $homework->description = $input['description'];
        $homework->category = $input['category'];
        $homework->school = 1;
        $homework->teacher =  Auth::user()->userid;
        $homework->save();

        if (isset($input['descask']) && $input['descask'] != '') {
            $DescAsk = $input['descask'];
        }
        if (isset($input['search']) && $input['search'] != '') {
            $search = $input['search'];
            if(Users::isTeacher()){
                $homework = Homeworks::where('title', 'like', '%' . $search . '%')->where('teacher',Auth::user()->userid)->orWhere('description', 'like', '%' . $search . '%')->orderBy($orderBy, $DescAsk)->paginate(config('lms.pagination'));
            }else{
                $homework = Homeworks::where('title', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%')->orderBy($orderBy, $DescAsk)->paginate(config('lms.pagination'));
            }
        } else {
            if(Users::isTeacher()){
                $homework = Homeworks::where('teacher',Auth::user()->userid)->orderBy($orderBy, $DescAsk)->paginate(config('lms.pagination'));
            }else{
                $homework = Homeworks::orderBy($orderBy, $DescAsk)->paginate(config('lms.pagination'));
            }
        }
        $levels=Levels::all();
        $categories=Categories::all();
        $homework->setPath('');
        return view('homework.index', compact('homework'), [
            'search' => $search,
            'descask' => $DescAsk,
            'orderBy' => $orderBy])
            ->with('levels',$levels)
            ->with('categories',$categories)
            ->renderSections()['content'];

    }

    public function delete()
    {
        if (request()->isMethod('GET')) {
            $input = request()->all();
            if (isset($input['id'])) {
                $id = $input['id'];
                if ($id != '') {
                    $assigin=HomeworkAssign::where('id_homework',$id)->delete();
                    $homeworkmedia=Homeworkmedia::where('id_homework',$id)->delete();
                    $homework = Homeworks::find($id)->delete();
                }
            }
        }
        $orderBy = 'created_at';
        $DescAsk = 'ASC';
        $search = '';
        if (isset($input['descask']) && $input['descask'] != '') {
            $DescAsk = $input['descask'];
        }
        if (isset($input['search']) && $input['search'] != '') {
            $search = $input['search'];
            if(Users::isTeacher()){
                $homework = Homeworks::where('title', 'like', '%' . $search . '%')->where('teacher',Auth::user()->userid)->orWhere('description', 'like', '%' . $search . '%')->orderBy($orderBy, $DescAsk)->paginate(config('lms.pagination'));
            }else{
                $homework = Homeworks::where('title', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%')->orderBy($orderBy, $DescAsk)->paginate(config('lms.pagination'));
            }
        } else {
            if(Users::isTeacher()){
                $homework = Homeworks::where('teacher',Auth::user()->userid)->orderBy($orderBy, $DescAsk)->paginate(config('lms.pagination'));
            }else{
                $homework = Homeworks::orderBy($orderBy, $DescAsk)->paginate(config('lms.pagination'));
            }
        }
        $levels=Levels::all();
        $categories=Categories::all();
        $homework->setPath('');
        return view('homework.index', compact('homework'), [
            'search' => $search,
            'descask' => $DescAsk,
            'orderBy' => $orderBy])
            ->with('levels',$levels)
            ->with('categories',$categories)
            ->renderSections()['content'];
    }

    public function viewedit($lang, $id)
    {
        $categories = Categories::all();
        $homework = Homeworks::find($id);
        return view('homework.edit', compact('homework', 'categories'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($lang)
    {

        $orderBy = 'created_at';
        $DescAsk = 'ASC';
        $search = '';
        $input = request()->all();
        $homework = new Homeworks();
        $homework = Homeworks::where('homework_id', $input['id'])->first();
        $homework->title = $input['title'];
        $homework->description = $input['description'];
        $homework->category = $input['category'];
        $homework->school = 1;
        $homework->teacher = Auth::user()->userid;
        $homework->save();

        if (isset($input['descask']) && $input['descask'] != '') {
            $DescAsk = $input['descask'];
        }
        if (isset($input['search']) && $input['search'] != '') {
            $search = $input['search'];
            if(Users::isTeacher()){
                $homework = Homeworks::where('title', 'like', '%' . $search . '%')->where('teacher',Auth::user()->userid)->orWhere('description', 'like', '%' . $search . '%')->orderBy($orderBy, $DescAsk)->paginate(config('lms.pagination'));
            }else{
                $homework = Homeworks::where('title', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%')->orderBy($orderBy, $DescAsk)->paginate(config('lms.pagination'));
            }
        } else {
            if(Users::isTeacher()){
                $homework = Homeworks::where('teacher',Auth::user()->userid)->orderBy($orderBy, $DescAsk)->paginate(config('lms.pagination'));
            }else{
                $homework = Homeworks::orderBy($orderBy, $DescAsk)->paginate(config('lms.pagination'));
            }
        }
        $levels=Levels::all();
        $categories=Categories::all();
        $homework->setPath('');
        return view('homework.index', compact('homework'), [
            'search' => $search,
            'descask' => $DescAsk,
            'orderBy' => $orderBy])
            ->with('levels',$levels)
            ->with('categories',$categories)
            ->renderSections()['content'];

    }

    /**
     * sent notifcation for all user when assing Homeworks
     * @param string $type
     * @param array $listItems
     */
    private  function notifUserHomeworks($type,$listItems,$idhomework){
        $model=Homeworks::find($idhomework);
        switch ($type) {
            case "group":
                foreach ($listItems as $item){
                    $users= Groups::find($item)->assings->pluck('ref_id');
                    NotificationHelper::n_homeworkToUsersAndParents($model->title,$type,$users,'/homework/'.$model->homework_id);
                }
                break;
            case "class":
                foreach ($listItems as $item){
                    $users= Classes::find($item)->students->pluck('userid');
                    NotificationHelper::n_homeworkToUsersAndParents($model->title,$type,$users,'/homework/'.$model->homework_id);
                }
            case "student":
                NotificationHelper::n_homeworkToUsersAndParents($model->title,$type,$listItems,'/homework/'.$model->homework_id);
                break;
        }
    }

    /**
     * auther hasan kiwan
     * @param $lang
     * @param $id
     * @return mixed
     */
    public function homeworkViewer($lang,$id){
        $canview=true;
        $studentid=-1;
        $homework = DB::table('homeworks')
            ->join('categories', 'homeworks.category', '=', 'categories.category_id')
            ->where("homeworks.homework_id",$id)
            ->first();
        if(empty($homework)){
            return view('404');
        }
        if(Users::isParent() || Users::isStudent()){
            $homeworks=DB::table('homeworkassign')
                ->where('homeworkassign.id_homework',$id)
                ->where('homeworkassign.enddate','>=' ,date('Y-m-d'))
                ->where(function ($query){
                    $query->orWhere(function ($q){
                        $q->where('assigntype','student');
                        $q->where('id_target',Auth::user()->userid);
                    }) ->orWhere(function ($q) {
                        $q->where('assigntype','classes');
                        $q->where('id_target',Auth::user()->class);
                    })->orWhere(function ($q) {
                        $q->where('assigntype','group');
                        $q->whereIn('id_target',Auth::user()->assignsGroupsStudent->pluck('product_id')->toArray());
                    });
                })->get();


            if($homeworks->count()<= 0){
                $canview=false;
                $homeworks=DB::table('homeworkassign')
                    ->where('homeworkassign.id_homework',$id)
                    ->where(function ($query){
                        $query->orWhere(function ($q){
                            $q->where('assigntype','student');
                            $q->where('id_target',Auth::user()->userid);
                        }) ->orWhere(function ($q) {
                            $q->where('assigntype','classes');
                            $q->where('id_target',Auth::user()->class);
                        })->orWhere(function ($q) {
                            $q->where('assigntype','group');
                            $q->whereIn('id_target',Auth::user()->assignsGroupsStudent->pluck('product_id')->toArray());
                        });
                    })->get();
                if($homeworks->count()<= 0){
                    return view('404');
                }
            }

        }
        if (Users::isTeacher()){
            if((Users::find($homework->teacher)->permession > 3 )){
                if($homework->teacher!=Auth::user()->userid ){
                    return view('404');
                }
            }
        }

        if (request()->isMethod('GET')) {
            if(Users::isStudent()){
                $studentid =Auth::user()->userid;
            }else{
                if(isset(request()->student) &&  request()->student!='' && request()->student !=-1 && $studentid!=-1){
                    $studentid=request()->student;
                }
            }

            $Media = DB::table('homeworkmedia')
                ->select('homeworkmedia.*','homeworkresult.*',
                    DB::raw('( select category_id from categories where  category_id='.$homework->category.') as category')
                )->leftJoin('homeworkresult', function($join)use ($studentid,$id){
                    $join->on('homeworkresult.id_assign', '=', 'homeworkmedia.id_media')
                        ->where([["homeworkresult.id_user","=",$studentid]])
                        ->where("homeworkresult.id_homework",$id);
                })->where("homeworkmedia.id_homework",$id)->get();


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
            return view('homeworksviewer.index')->with("homework",$homework)->with("Media",$Media)->with("user_points",$user_points)->with("progress",$progress)->with("awards",$awards)->withCanview($canview);
        }
    }

}

?>