<?php

namespace App\Http\Controllers;

use App\Helper\MessageHelper;
use App\Helper\SqlHelper;
use App\Helper\UserHelper;
use App\Homeworkmedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Users;
use App\Homeworks;

class BaseController extends Controller
{
    function __construct() {

        $this->middleware('lang');
        $this->middleware('auth.custom');
    }

    public function lang($lang=null){
        if (Auth::user()->permession == Users::USER_MANHAL_ADMINISTRATOR ||Auth::user()->permession == Users::USER_SCHOOL_ADMINISTRATOR ||Auth::user()->permession == Users::USER_SCHOOL_MANGER ){
            $teachers=Users::where('permession', Users::USER_TEACHER)->get();
            $adminIdes=Users::whereBetween('permession', [1, 3])->get()->pluck('userid');
            $lessonTachers=[];
            $countTatcher=0;
            foreach ($teachers as $teacher){
                $where = array(["schedule.teacher", "=", $teacher->userid]);
                $Lessons = DB::table('schedule')
                    ->select('schedule.class','schedule.category','categories.title_ar','categories.title_en','classes.ctitle_ar','classes.ctitle_en','levels.level_id')
                    ->join('categories', 'schedule.category', '=', 'categories.category_id')
                    ->join('classes', 'schedule.class', '=', 'classes.class_id')
                    ->join('levels', 'levels.level_id', '=', 'classes.level')
                    ->join('lessons', 'lessons.level', '=', 'classes.level')
                    ->where($where)
                    ->orderBy('lessons.created_at','DESC')
                    ->distinct()
                    ->get();

                $response = json_decode($Lessons, true);
                $i=0;
                $mydate = date('Y-m-d');
                $Lessons_=[];
                $lessonassign=DB::table('lessonassigns')->select('id_lesson')->where('created_by',$teacher->userid)->where('assigntype','class')->where('enddate','>=',$mydate)->get()->pluck('id_lesson')->toArray();
                foreach($response as $lesson){
//                    $where = array(["lessons.level","=",$lesson['level_id']],["lessons.category","=",$lesson['category']],['close_date','>=',$mydate]);
//                    $Lessons_[$i]['lesson']['end']=DB::table('lessons')
//                        ->select('lessons.id','lessons.title','lesson_media.thumbnail')
//                        ->join('lesson_media', 'lesson_media.id_lesson', '=', 'lessons.id')
//                        ->groupBy('lessons.title')
//                        ->distinct()
//                        ->where($where)
//                        ->orderBy('lessons.created_at','DESC')
//                        ->take(2)
//                        ->get();

                    $where = array(["lessons.level","=",$lesson['level_id']],["lessons.category","=",$lesson['category']],['close_date','>=',$mydate]);
                    $Lessons_[$i]['lesson']['str'] = DB::table('lessons')
                        ->select('lessons.id','lessons.title','lesson_media.thumbnail')
                        ->join('lesson_media', 'lesson_media.id_lesson', '=', 'lessons.id')
                        ->orderBy('lessons.created_at','DESC')
                        ->groupBy('lessons.title')
                        ->distinct()
                        ->where($where)
                        ->orWhereIn('lessons.id',$lessonassign)
                        ->orderBy('lessons.created_at','DESC')
                        ->take(2)
                        ->get();
                    $Lessons_[$i]['info']=$lesson;


                    $homeworks=DB::table('homeworks')
                        ->select('homeworks.*','homeworkassign.enddate','homeworkassign.startdate')
                        ->join('homeworkassign','homeworks.homework_id','=','homeworkassign.id_homework')
                        ->where('homeworks.category',$lesson['category'])
                        ->where('homeworkassign.enddate','>=',$mydate)
                        ->where(function ($q)use ($adminIdes,$teacher){
                            $q->where('homeworks.teacher',$teacher->userid);
                            $q->orWhereIn('homeworks.teacher',$adminIdes);
                        })
                        ->where(function ($q)use ($lesson){
                            $q->where('id_target',$lesson['class']);
                            $q->where('assigntype','classes');
                        })->distinct()
                        ->orderby('homeworkassign.id','DESC')
                        ->take(2)
                        ->get();
                    for ($k=0 ;$k <count($homeworks);$k++){
                        $homeworks[$k]->media= DB::table('homeworkmedia')->where('id_homework',$homeworks[$k]->homework_id)->first();
                    }

                    $Lessons_[$i]['homeworks']=$homeworks;

                    $quiz=DB::table('quizassign')
                        ->select('quiz.title','quiz.quiz_id')
                        ->join('quiz','quiz.quiz_id','=','quizassign.id_quiz')
                        ->where('category',$lesson['category'])
                        ->where(function ($q)use ($adminIdes,$teacher){
                            $q->where('quiz.teacher',$teacher->userid);
                            $q->orWhereIn('quiz.teacher',$adminIdes);
                        })
                        ->where('enddate','>=',$mydate)
                        ->where(function ($q)use ($lesson){
                            $q->where('id_target',$lesson['class']);
                            $q->where('assigntype','class');
                        })->distinct()
                        ->orderBy('quizassign.id','DESC')
                        ->take(2)
                        ->get();

                    for ($k=0 ;$k <count($quiz);$k++){
                        $quiz[$k]->media= DB::table('quiz_media')->where('id_quiz', $quiz[$k]->quiz_id)->first();
                    }
                    $Lessons_[$i]['quiz']=$quiz;


                    $i++;

                }
                $lessonTachers[$countTatcher]['lessons']=$Lessons_;
                $lessonTachers[$countTatcher]['fullname']=$teacher->fullname;
                $countTatcher++;

            }


            return view('home/admin')->with("Lessons", $lessonTachers);
        }else if(Auth::user()->permession == Users::USER_TEACHER ){
                $adminIdes=Users::whereBetween('permession', [1, 3])->get()->pluck('userid');
                $where = array(["schedule.teacher", "=", Auth::user()->userid]);
                $Lessons = DB::table('schedule')
                    ->select('classes.class_id','schedule.class','schedule.category','categories.title_ar','categories.title_en','classes.ctitle_ar','classes.ctitle_en','levels.level_id')
                    ->join('categories', 'schedule.category', '=', 'categories.category_id')
                    ->join('classes', 'schedule.class', '=', 'classes.class_id')
                    ->join('levels', 'levels.level_id', '=', 'classes.level')
                    ->join('lessons', 'lessons.level', '=', 'classes.level')
                    ->orderBy('lessons.created_at','DESC')
                    ->where($where)->distinct()
                    ->get();
                $response = json_decode($Lessons, true);
                $i=0;
                $mydate = date('Y-m-d');
                $Lessons_=[];
                foreach($response as $lesson){
                    $where = array(["lessons.level","=",$lesson['level_id']],["lessons.category","=",$lesson['category']],['close_date','>=',$mydate]);
//                    $Lessons_[$i]['lesson']['end']=DB::table('lessons')
//                        ->select('lessons.id','lessons.title','lesson_media.thumbnail')
//                        ->join('lesson_media', 'lesson_media.id_lesson', '=', 'lessons.id')
//                        ->orderBy('lessons.created_at','DESC')
//                        ->groupBy('lessons.title')
//                        ->distinct()
//                        ->where($where)
//                        ->take(2)
//                        ->get();
                    $lessonassign=DB::table('lessonassigns')->select('id_lesson')->where('created_by',Auth::user()->userid)->where('assigntype','class')->where('enddate','>=',$mydate)->get()->pluck('id_lesson')->toArray();
                    $where = array(["lessons.level","=",$lesson['level_id']],["lessons.category","=",$lesson['category']],['close_date','>=',$mydate]);
                    $Lessons_[$i]['lesson']['str'] = DB::table('lessons')
                        ->select('lessons.id','lessons.title','lesson_media.thumbnail')
                        ->join('lesson_media', 'lesson_media.id_lesson', '=', 'lessons.id')
                        ->groupBy('lessons.title')
                        ->orderBy('lessons.created_at','DESC')
                        ->distinct()
                        ->where($where)
                        ->orWhereIn('lessons.id',$lessonassign)
                        ->distinct()
                        ->take(2)
                        ->get();
                    $Lessons_[$i]['info']=$lesson;


                    $homeworks=DB::table('homeworks')
                        ->select('homeworks.*','homeworkassign.enddate','homeworkassign.startdate')
                        ->join('homeworkassign','homeworks.homework_id','=','homeworkassign.id_homework')
                        ->where('homeworks.category',$lesson['category'])
                        ->where('homeworkassign.enddate','>=',$mydate)
                        ->where('homeworkassign.id_target',$lesson['class_id'])
                        ->where('homeworkassign.assigntype','classes')
                        ->where(function ($q)use ($adminIdes){
                            $q->where('homeworks.teacher',Auth::user()->userid);
                            $q->orWhereIn('homeworks.teacher',$adminIdes);
                        })
                        ->orderby('homeworkassign.id','desc')
                        ->take(2)
                        ->distinct()->get();

                    for ($k=0 ;$k <count($homeworks);$k++){
                        $homeworks[$k]->media= DB::table('homeworkmedia')->where('id_homework',$homeworks[$k]->homework_id)->first();
                    }

                    $Lessons_[$i]['homeworks']=$homeworks;

                    $quiz=DB::table('quizassign')
                        ->select('quiz.title','quiz.quiz_id')
                        ->join('quiz','quiz.quiz_id','=','quizassign.id_quiz')
                        ->where('category',$lesson['category'])

                        ->where('quizassign.id_target',$lesson['class_id'])
                        ->where('quizassign.assigntype','class')
                        ->where('enddate','>=',$mydate)
                        ->where(function ($q)use ($adminIdes){
                            $q->where('quiz.teacher',Auth::user()->userid);
                            $q->orWhereIn('quiz.teacher',$adminIdes);
                        })
                        ->orderby('quizassign.id','desc')
                        ->take(2)
                        ->get();

                    for ($k=0 ;$k <count($quiz);$k++){
                        $quiz[$k]->media= DB::table('quiz_media')->where('id_quiz', $quiz[$k]->quiz_id)->first();
                    }
                    $Lessons_[$i]['quiz']=$quiz;


                    $i++;
                }

                return view('home/teacher')->with("Lessons", $Lessons_);
        }else if(Auth::user()->permession == Users::USER_STUDENT) {
            $where = array(["users.userid", "=", Auth::user()->userid]);
            $Lessons = DB::table('users')
                ->select('users.level','categories.category_id','categories.title_ar','categories.title_en')
                ->join('lessons', 'lessons.level', '=', 'users.level')
                ->join('categories', 'lessons.category', '=', 'categories.category_id')
                ->where($where)
                ->orderBy('lessons.created_at','DESC')
                ->distinct()
                ->get();

            $dataNow=date('Y-m-d');
            $response = json_decode($Lessons, true);
            $Lessons_=[];

            $i=0;

            $mydate = date('Y-m-d');
            foreach($response as $lesson){
                $where = array(["lessons.level","=",$lesson['level']],["lessons.category","=",$lesson['category_id']],['close_date','>=',$mydate]);
//                $Lessons_[$i]['lesson']['end']=DB::table('lessons')
//                    ->select('lessons.id','lessons.title','lesson_media.thumbnail')
//                    ->join('lesson_media', 'lesson_media.id_lesson', '=', 'lessons.id')
//                    ->groupBy('lessons.title')
//                    ->orderBy('lessons.created_at','DESC')
//                    ->distinct()
//                    ->where($where)
//                    ->take(2)
//                    ->get();
                $lessonassign=DB::table('lessonassigns')->select('lessonassigns.id_lesson')
                    ->where('enddate','>=',$mydate)
                    ->where(function ($q){
                        $q->orwhere(function($query){
                            $query->where('assigntype', 'class')
                                ->where('id_target', Auth::user()->class);
                        })->orwhere(function($query){
                            $query->where('assigntype', 'student')
                                ->where('id_target', Auth::user()->userid);
                        })->orwhere(function($query){
                            $groupsid= Auth::user()->assignsGroupsStudent->pluck('product_id');
                            $query->where('assigntype', 'group')
                                ->whereIn('id_target', $groupsid);
                        });
                    })
                    ->get()->pluck('id_lesson')->toArray();

                $where = array(["lessons.level","=",$lesson['level']],["lessons.category","=",$lesson['category_id']],['close_date','>=',$mydate]);
                $Lessons_[$i]['lesson']['str'] = DB::table('lessons')
                    ->select('lessons.id','lessons.title','lesson_media.thumbnail')
                    ->join('lesson_media', 'lesson_media.id_lesson', '=', 'lessons.id')
                    ->groupBy('lessons.title')
                    ->orderBy('lessons.created_at','DESC')
                    ->distinct()
                    ->where($where)
                    ->orWhere(function($q)use($lessonassign,$lesson){
                      $q->whereIn('lessons.id',$lessonassign);
                      $q->where('lessons.category',$lesson['category_id']);
                    })
                    ->take(2)
                    ->get();
                $Lessons_[$i]['info']=$lesson;

                $homeworkassign=DB::table('homeworkassign')
                    ->select('homeworkassign.*')
                   ->where('homeworkassign.enddate','>=' ,$dataNow)
                    ->where(function ($q){
                        $q->orwhere(function($query){
                            $query->where('assigntype', 'classes')
                                ->where('id_target', Auth::user()->class);
                        })->orwhere(function($query){
                            $query->where('assigntype', 'student')
                                ->where('id_target', Auth::user()->userid);
                        })->orwhere(function($query){
                            $groupsid= Auth::user()->assignsGroupsStudent->pluck('product_id');
                            $query->where('assigntype', 'group')
                                ->whereIn('id_target', $groupsid);
                        });
                    })
                    ->distinct()
                    ->get()
                    ->pluck('id_homework');

                $homeworks=DB::table('homeworks')
                    ->select('homeworks.*')
                    ->whereIn('homework_id',$homeworkassign)
                    ->where('category',$response[$i]['category_id'])
                    ->orderBy('created_at','DESC')
                    ->distinct()
                    ->take(2)
                    ->get();

                for ($k=0 ;$k <count($homeworks);$k++){

                    $homeworks[$k]->result = new \stdClass();
                    $homeworks[$k]->result= UserHelper::avgOneHomework(Auth::user()->userid,$homeworks[$k]->homework_id);
                    $homeworks[$k]->media= DB::table('homeworkmedia')
                        ->where('id_homework',$homeworks[$k]->homework_id)
                        ->orderBy('id','asc')
                        ->first();
                }



                $Lessons_[$i]['homeworks']=$homeworks;
                $quizides=DB::table('quizassign')
                    ->where('enddate','>=',$dataNow)
                    ->where(function ($q){
                        $q->orwhere(function($query){
                            $query->where('assigntype', 'class')
                                ->where('id_target', Auth::user()->class);
                        })->orwhere(function($query){
                                    $query->where('assigntype', 'student')
                                    ->where('id_target', Auth::user()->userid);
                        })->orwhere(function($query){
                                $groupsid= Auth::user()->assignsGroupsStudent->pluck('product_id');
                                $query->where('assigntype', 'group')
                                    ->whereIn('id_target', $groupsid);
                        });
                    })->get()
                    ->pluck('id_quiz')
                    ->toArray();

                $quiz=DB::table('quiz')
                    ->select('quiz.*')
                    ->where('category',$lesson['category_id'])
                    ->whereIn('quiz_id',$quizides)
                    ->where('level',Auth::user()->level )
                    ->distinct()
                    ->take(2)
                    ->get();

                for ($k=0 ;$k <count($quiz);$k++){
                    $quiz[$k]->media= DB::table('quiz_media')
                        ->where('id_quiz',$quiz[$k]->quiz_id)
                        ->orderBy('id','asc')
                        ->first();
                }
                $Lessons_[$i]['quiz']=$quiz;
                $i++;
            }
            return view('home/student')->with("Lessons", $Lessons_);
        }else if(Auth::user()->permession == Users::USER_PARENT) {


            $sonsId=Auth::user()->students->pluck('student_id');
            $childs=Auth::user()->students->pluck('studentInfo');
            $responses=array();
            $count=0;
            foreach ($sonsId as $id){
                $where = array(["users.userid", "=", $id]);
                $Lessons = DB::table('users')
                    ->select('users.userid','users.fullname','users.level','users.class','categories.category_id','categories.title_ar','categories.title_en')
                    ->join('lessons', 'lessons.level', '=', 'users.level')
                    ->join('categories', 'lessons.category', '=', 'categories.category_id')
                    ->where($where)
                    ->orderBy('lessons.created_at','DESC')
                    ->distinct()
                    ->get();
                $responses[$id] = json_decode($Lessons, true);
                $count++;
            }

            $mydate = date('Y-m-d');
            $index=0;
            $Lessons_=[];
            foreach ($responses as $response){
                $i=0;
                $Lessons_[$index]=[];
                foreach($response as $lesson){
                    $where = array(["lessons.level","=",$lesson['level']],["lessons.category","=",$lesson['category_id']],['close_date','<=',$mydate]);
//                    $Lessons_[$index][$i]['lesson']['end']=DB::table('lessons')
//                        ->select('lessons.id','lessons.title','lesson_media.thumbnail')
//                        ->join('lesson_media', 'lesson_media.id_lesson', '=', 'lessons.id')
//                        ->groupBy('lessons.title')
//                        ->orderBy('lessons.created_at','DESC')
//                        ->distinct()
//                        ->where($where)
//                        ->take(2)
//                        ->get();
                    $user=Users::find($lesson['userid']);

                    $lessonassign=DB::table('lessonassigns')->select('lessonassigns.id_lesson')
                        ->where('enddate','>=',$mydate)
                        ->where(function ($q)use ($user) {
                            $q->orwhere(function($query)use ($user){
                                $query->where('assigntype', 'class')
                                    ->where('id_target', $user->class);
                            })->orwhere(function($query)use($user){
                                $query->where('assigntype', 'student')
                                    ->where('id_target', $user->userid);
                            })->orwhere(function($query)use($user){
                                $groupsid=$user->assignsGroupsStudent->pluck('product_id');
                                $query->where('assigntype', 'group')
                                    ->whereIn('id_target', $groupsid);
                            });
                        })->get()->pluck('id_lesson')->toArray();

                    $where = array(["lessons.level","=",$lesson['level']],["lessons.category","=",$lesson['category_id']],['close_date','>=',$mydate]);
                    $Lessons_[$index][$i]['lesson']['str'] = DB::table('lessons')
                        ->select('lessons.id','lessons.title','lesson_media.thumbnail')
                        ->join('lesson_media', 'lesson_media.id_lesson', '=', 'lessons.id')
                        ->groupBy('lessons.title')
                        ->orderBy('lessons.created_at','DESC')
                        ->distinct()
                        ->where($where)
                        ->orWhere(function($q)use($lessonassign,$lesson){
                            $q->whereIn('lessons.id',$lessonassign);
                            $q->where('lessons.category',$lesson['category_id']);
                        })
                        ->take(2)
                        ->get();
                    $Lessons_[$index][$i]['info']=$lesson;



                    $homeworkassign=DB::table('homeworkassign')
                        ->select('homeworkassign.*')
                        ->where('enddate','>=',$mydate)
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

                    $homeworks=DB::table('homeworks')
                        ->select('homeworks.*')
                        ->whereIn('homework_id', $homeworkassign)
                        ->where('category',$lesson['category_id'])
                        ->orderBy('created_at','DESC')
                        ->take(2)
                        ->get();

                    for ($k=0 ;$k <count($homeworks);$k++){

                        $homeworks[$k]->result = new \stdClass();
                        $homeworks[$k]->result= UserHelper::avgOneHomework($user->userid,$homeworks[$k]->homework_id);
                        $homeworks[$k]->media= DB::table('homeworkmedia')
                            ->where('id_homework',$homeworks[$k]->homework_id)
                            ->orderBy('id','asc')
                            ->first();
                    }

                    $Lessons_[$index][$i]['homeworks']=$homeworks;

                    $quizides=DB::table('quizassign')
                        ->where('enddate','>=',$mydate)
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

                    $quiz=DB::table('quiz')
                        ->select('quiz.*')
                        ->where('category',$lesson['category_id'])
                        ->whereIn('quiz_id',$quizides)
                        ->where('level',$user->level )
                        ->distinct()
                        ->take(2)
                        ->get();
                    for ($k=0 ;$k <count($quiz);$k++){
                        $quiz[$k]->media= DB::table('quiz_media')
                            ->where('id_quiz',$quiz[$k]->quiz_id)
                            ->orderBy('id','asc')
                            ->first();
                    }

                    $Lessons_[$index][$i]['quiz']=$quiz;

                    $i++;
                }
                $index++;
            }

            return view('home/parent')->with("Lessons", $Lessons_)->with('childs',$childs);

        }

    }


    public function sendMessageView($lang,$userid){
        $user =Users::find($userid) ;
        return view('common.send-message')->with('user',$user);
    }

    public function sendMessage($lang,$userid,Request $request){
        MessageHelper::sendMessageToUser($request->message,$userid);
    }

}
