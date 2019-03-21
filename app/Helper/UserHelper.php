<?php
/**
 * Created by PhpStorm.
 * User: Saleh
 * Date: 9/17/2018
 * Time: 10:56 AM
 */

namespace App\Helper;


use App\Categories;
use App\Curriculums;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserHelper
{
    public static function getAvgCategories($collection){
        $sum =0;
        if(empty($collection)){
          return 0;
        }
        foreach ($collection as $item){
            $sum+=$item->percent;
        }
        if(! $collection->count()){
            return 0;
        }
        return round($sum/$collection->count(),2);
    }


    public static function  getAvgLesson($collection,$userid ,$idlesson){
        $sum =0;
        foreach ($collection as $item){
            if( ! $item->result($userid,$idlesson)== null){
                $sum+=$item->result($userid,$idlesson)['result'];
            }else{
                $sum+=0;
            }

        }
        if(! $collection->count()){
            return 0;
        }
        return round($sum/$collection->count(),2);
    }

    public static function  getAvgLessonOneByOne($lessonId,$userid){
        $total_points=0;
        $lesson_points=0;
        $play_count=0;
        $i=0;
        $Media = DB::table('lesson_media')
            ->leftJoin('result_media', function($join)use ($userid){
                $join->on('result_media.id_media', '=', 'lesson_media.id')
                    ->where([["result_media.user_id","=",$userid]]);
            })
            ->select('lesson_media.id as media_id','lesson_media.*','result_media.*')
            ->where([["lesson_media.id_lesson","=",$lessonId]])
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
        return ['percent'=>$progress,'result'=>$user_points];
    }


    public static function  getAvgHomeworkOneByOne($homeworkid,$userid){
        $total_points=0;
        $lesson_points=0;
        $play_count=0;
        $i=0;
        $Media = DB::table('homeworkmedia')
            ->select('homeworkmedia.id as media_id','homeworkresult.result')
            ->join('homeworkresult', function($join)use ($userid){
                $join->on('homeworkresult.id_assign', '=', 'homeworkmedia.id_media')
                    ->where([["homeworkresult.id_user","=",$userid]]);
            })
            ->where([["homeworkmedia.id_homework","=",$homeworkid]])
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
        return ['percent'=>$progress,'result'=>$user_points];
    }


    public static function  getAvgQuizOneByOne($idquiz,$userid){
        $total_points=0;
        $lesson_points=0;
        $play_count=0;
        $i=0;
        $Media = DB::table('quiz_media')
            ->select('quiz_media.id as media_id','quizresult.result')
            ->join('quizresult', function($join)use ($userid){
                $join->on('quizresult.id_assign', '=', 'quiz_media.id_media')
                    ->where([["quizresult.id_user","=",$userid]]);
            })
            ->where([["quiz_media.id_quiz","=",$idquiz]])
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
        return ['percent'=>$progress,'result'=>$user_points];
    }


    public static function  getAvgLessonH($lessonId,$userid){
        $sum =0;
        $idesMedia=DB::table('lesson_media')->select('id')
               ->where('id_lesson',$lessonId)->get()->pluck('id');

        $lessonMedia=DB::table('result_media')->select('result')
            ->where('user_id',Auth::user()->userid)
            ->whereIn('id_media',$idesMedia)->get();

        if($lessonMedia->count()==0){
            return 0;
        }

        foreach ($lessonMedia as $lessonMediaa){
           $sum+= $lessonMediaa->result;
        }
        return round($sum/$lessonMedia->count(),2);
    }



    public  static function calculateUesrMarkCategory($userId,$categoryid){
        $total_points=0;
        $lesson_points=0;
        $play_count=0;
        $user= User::where('userid',$userId)->first();
        $category=Categories::find($categoryid);
        $lessonIds=DB::table('lessons')->where('category',$categoryid)
            ->select('id')
            ->where('level',$user->level)
            ->pluck('id')
            ->toArray();
        $i=0;
        $homeworkassign=DB::table('homeworkassign')
            ->select('homeworkassign.*')
            ->join('homeworks','homeworks.homework_id','=','homeworkassign.id_homework')
            ->where(function ($q)use ($user){
                $q->orwhere(function($query)use ($user){
                    $query->where('assigntype', 'classes')
                        ->where('id_target', $user->class);
                })->orwhere(function($query)use ($user){
                    $query->where('assigntype', 'student')
                        ->where('id_target', $user->userid);
                })->orwhere(function($query)use ($user){
                    $groupsid= $user->assignsGroupsStudent;
                    if(!is_null($groupsid)){
                        $query->where('assigntype', 'group')
                            ->whereIn('id_target', $groupsid);
                    }
                });
            })->where('homeworks.category',$categoryid)
            ->get()
            ->pluck('id_homework')
            ->toArray();

        $quizassign=DB::table('quizassign')
            ->select('quizassign.*')
            ->join('quiz','quiz.quiz_id','=','quizassign.id_quiz')
            ->where(function ($q)use ($user){
                $q->orwhere(function($query)use ($user){
                    $query->where('assigntype', 'class')
                        ->where('id_target', $user->class);
                })->orwhere(function($query)use ($user){
                    $query->where('assigntype', 'student')
                        ->where('id_target', $user->userid);
                })->orwhere(function($query)use ($user){
                    $groupsid= $user->assignsGroupsStudent;
                    if(!is_null($groupsid)){
                        $query->where('assigntype', 'group')
                            ->whereIn('id_target', $groupsid);
                    }
                });
            })->where('quiz.category',$categoryid)
            ->get()
            ->pluck('id_quiz')
            ->toArray();

        $mediahomework= DB::table('homeworkmedia')
            ->select('homeworkmedia.id  as media_id','homeworkresult.result as result')
            ->join('homeworkresult', function($join)use ($userId){
                $join->on('homeworkresult.id_assign', '=', 'homeworkmedia.id_media')
                    ->where([["homeworkresult.id_user","=",$userId]]);
            })
            ->whereIn("homeworkresult.id_homework",$homeworkassign);

        $mediaquiz= DB::table('quiz_media')
            ->select('quiz_media.id  as media_id','quizresult.result as result')
            ->join('quizresult', function($join)use ($userId){
                $join->on('quizresult.id_assign', '=', 'quiz_media.id_media')
                    ->where([["quizresult.id_user","=",$userId]]);
            })
            ->whereIn("quizresult.quiz_id",$quizassign);

        $Media = DB::table('lesson_media')
            ->select('lesson_media.id as media_id','result_media.result as result')
            ->leftJoin('result_media', function($join)use ($userId){
                $join->on('result_media.id_media', '=', 'lesson_media.id')
                    ->where([["result_media.user_id","=",$userId]]);
            })
            ->whereIn("lesson_media.id_lesson",$lessonIds)
            ->unionAll($mediahomework)
            ->unionAll($mediaquiz)
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
        return ['percent'=>$progress,'result'=>$user_points];

    }


    public static function AvgAllCategories($userId){

        $lessonAvg=0;
        $percent=0;
        $counter=0;
        $progressAll=0;
        $avgAll=0;
        $categories=Categories::all();
        foreach ($categories as $category){
            foreach ($category->lessons as $lesson){
                $lessonAvg+= Self::getAvgLesson($lesson->media,$userId,$lesson->id);
                $counter++;
            }
            $avgCategory=($counter==0 || $lessonAvg==0)?0:$lessonAvg/$counter;
            $avgAll+=$avgCategory;
        }

        return $avgAll/count($categories);
    }


    public static function avgOneHomework($userid,$idhomework){
        $Media = DB::table('homeworkmedia')
            ->leftJoin('homeworkresult', function($join)use ($userid,$idhomework){
                $join->on('homeworkresult.id_assign', '=', 'homeworkmedia.id_media')
                    ->where([["homeworkresult.id_user","=",$userid]])
                    ->where("homeworkresult.id_homework",$idhomework);
                ;
            })
            ->select('homeworkmedia.*','homeworkresult.*')
            ->where("homeworkmedia.id_homework",$idhomework)->get();

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
        return ['percent'=>$progress,'result'=>$user_points];
    }


    public static function avgCurriculum($userId,$curriculum){

        $categoryid=Curriculums::find($curriculum)->cu_category;
        $total_points=0;
        $lesson_points=0;
        $play_count=0;
        $user= User::where('userid',$userId)->first();
        $category=Categories::find($categoryid);
        $lessonIds=DB::table('lessons')->where('category',$categoryid)
            ->select('id')
            ->where('level',$user->level)
            ->pluck('id')
            ->toArray();
        $i=0;
        $homeworkassign=DB::table('homeworkassign')
            ->select('homeworkassign.*')
            ->join('homeworks','homeworks.homework_id','=','homeworkassign.id_homework')
            ->where(function ($q)use ($user){
                $q->orwhere(function($query)use ($user){
                    $query->where('assigntype', 'classes')
                        ->where('id_target', $user->class);
                })->orwhere(function($query)use ($user){
                    $query->where('assigntype', 'student')
                        ->where('id_target', $user->userid);
                })->orwhere(function($query)use ($user){
                    $groupsid= $user->assignsGroupsStudent;
                    if(!is_null($groupsid)){
                        $query->where('assigntype', 'group')
                            ->whereIn('id_target', $groupsid);
                    }
                });
            })->where('homeworks.category',$categoryid)
            ->get()
            ->pluck('id_homework')
            ->toArray();


        $quizassign=DB::table('quizassign')
            ->select('quizassign.*')
            ->join('quiz','quiz.quiz_id','=','quizassign.id_quiz')
            ->where(function ($q)use ($user){
                $q->orwhere(function($query)use ($user){
                    $query->where('assigntype', 'class')
                        ->where('id_target', $user->class);
                })->orwhere(function($query)use ($user){
                    $query->where('assigntype', 'student')
                        ->where('id_target', $user->userid);
                })->orwhere(function($query)use ($user){
                    $groupsid= $user->assignsGroupsStudent;
                    if(!is_null($groupsid)){
                        $query->where('assigntype', 'group')
                            ->whereIn('id_target', $groupsid);
                    }
                });
            })->where('quiz.category',$categoryid)
            ->get()
            ->pluck('id_quiz')
            ->toArray();

        $mediahomework= DB::table('homeworkmedia')
            ->select('homeworkmedia.id  as media_id','homeworkresult.result as result')
            ->join('homeworkresult', function($join)use ($userId){
                $join->on('homeworkresult.id_assign', '=', 'homeworkmedia.id_media')
                    ->where([["homeworkresult.id_user","=",$userId]]);
            })
            ->whereIn("homeworkresult.id_homework",$homeworkassign);

        $mediaquiz= DB::table('quiz_media')
            ->select('quiz_media.id  as media_id','quizresult.result as result')
            ->join('quizresult', function($join)use ($userId){
                $join->on('quizresult.id_assign', '=', 'quiz_media.id_media')
                    ->where([["quizresult.id_user","=",$userId]]);
            })
            ->whereIn("quizresult.quiz_id",$quizassign);


        $Media = DB::table('lesson_media')
            ->select('lesson_media.id as media_id','result_media.result as result')
            ->leftJoin('result_media', function($join)use ($userId){
                $join->on('result_media.id_media', '=', 'lesson_media.id')
                    ->where([["result_media.user_id","=",$userId]]);
            })
            ->whereIn("lesson_media.id_lesson",$lessonIds)
            ->unionAll($mediahomework)
            ->unionAll($mediaquiz)
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
        return ['percent'=>$progress,'result'=>$user_points];
    }



    public static function  createUserOnSiteManhal($user,$pass){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"https://www.manhal.com/APIs/manhal/lcms_api.php/action/adduser");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "lms_uname=".$user->uname."&email=".$user->email."&permission=".self::typeRowInMnahal($user->permession).
            "&birthdate=".self::formatDateBirthdate($user->birthdate).
            "&lms_id=".config('lms.lms_id')."&lms_userid=".$user->userid."&lms_password=".$pass);


        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close ($ch);
//        $data=[
//            'lms_uname'=>$user->uname,
//            'email'=>$user->email,
//            'permission'=>self::typeRowInMnahal($user->permession),
//            'birthdate'=>self::formatDateBirthdate($user->birthdate),
//            'lms_id'=>config('lms.lms_id'),
//            'lms_userid'=>$user->userid,
//            'lms_password'=>$pass
//        ];

        $response= json_decode($server_output,true);

            if($response['status']=="ok"){
                return  ['manhal_id'=>$response['manhal_id'],'status'=>'ok','code'=>201];
            }

        return  ['status'=>'fail','code'=>502];


        }

    private  static function typeRowInMnahal($permession){
        if($permession < 5){
            return 11;
        }
        return 10;
    }


    private static function  formatDateBirthdate($birthdate){
        return date("yyyy-mm-dd", strtotime($birthdate));
    }

}