<?php
/**
 * Created by PhpStorm.
 * User: Saleh
 * Date: 10/17/2018
 * Time: 11:42 AM
 */

namespace App\Http\Controllers;


use App\Categories;
use App\Curriculums;
use App\Helper\NotificationHelper;
use App\Helper\SqlHelper;
use App\HomeworkAssign;
use App\Homeworks;
use App\LessonMidea;
use App\Lessons;
use App\Quiz;
use App\QuizAssign;
use App\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobController extends BaseController
{
     public function  finish($lang,Request $request){
         $type=$request->type;
         $idType=$request->idType;
         $userid=$request->userid;
         $countMidea=$request->count_media;
         $viewMedia=$request->view_media;
         $result=$request->result;
         $is_finished=$request->is_finished ; // 0 is not finished // 1 is finished
         $teacher=0;
         $query= Result::where('user_id',$userid)->where('lesson_id',$idType)->first();

         if(!empty($query)){
             $query->is_finished=$is_finished; $query->result=$result; $query->view_media=$viewMedia;
             $query->save();
         }else{
             DB::table('result')->insert([
                     'type' => $type,
                     'lesson_id' =>(integer) $idType,
                     'count_media'=>$countMidea,
                     'user_id'=>$userid,
                     'view_media'=>$viewMedia,
                     'result'=>$result,
                     'is_finished'=>$is_finished,
                 ]
             );
         }
         switch ($type) {
             case "quiz":
                 $quiz=Quiz::find($idType);
                 if($is_finished){
                     NotificationHelper::n_sendFinishQuizToTeacher($quiz->title,$quiz->teacher,'/quiz');
                 }
                 break;
             case "homework":
                 $homework=Homeworks::find($idType);
                 if($is_finished){
                     NotificationHelper::n_sendFinishHomeworkToTeacher($homework->title,$homework->teacher,'homework');
                 }
                 break;
             case "lesson":
                 $lesson=Lessons::find($idType);
                 if($is_finished){
                     NotificationHelper::n_sendFinishLessonToTeacher($lesson->title,$lesson->teacher,'/lesson');
                 }
                 break;
         }
     }


     public function calculateUesrMarkCategory($lang,Request $request){
         $userId=$request->userid;
         $categoryid=$request->categoryID;
         $lessonAvg=0;
         $percent=0;
         $counter=0;
         $category=Categories::find($categoryid);

         foreach ($category->lessons as $lesson){
             $lessonAvg+= UserHelper::getAvgLesson($lesson->media,$userId,$lesson->id);
             $percent+= $lesson->media->count();
             $counter++;
         }
         $progress= ($counter==0 || $lessonAvg==0)?0:($lessonAvg/$counter)-$percent;
         $avgCategory=($counter==0 || $lessonAvg==0)?0:$lessonAvg/$counter;

        DB::table('marks_user_categories')->insert([
            'user_id'=>$userId,
            'category_id'=>$categoryid,
            'result'=>$lessonAvg,
            'percent'=>$progress,
            'created_at'=>date('Y-m-d')
        ]);
     }


    public function calculateUesrMarkCurriculum($lang,Request $request){
        $userId=$request->userid;
        $curriculumid=$request->curriculum;
        $curriculum=Curriculums::find($curriculumid);
        $lessonAvg=0;
        $percent=0;
        $counter=0;

        foreach ($curriculum->lessons as $lesson){
            $lessonAvg+= UserHelper::getAvgLesson($lesson->media,$userId,$lesson->id);
            $percent+= $lesson->media->count();
            $counter++;
        }
        $progress= ($counter==0 || $lessonAvg==0)?0:($lessonAvg/$counter)-$percent;
        $avgCarecurm=($counter==0 || $lessonAvg==0)?0:$lessonAvg/$counter;
        DB::table('result_curriculum')->insert([
            'userid'=>$userId,
            'curriculum_id'=>$curriculumid,
            'category'=>$curriculum->cu_category,
            'progress'=>$progress,
            'result'=>$avgCarecurm
            ]);


    }



}