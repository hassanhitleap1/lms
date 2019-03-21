<?php
/**
 * Created by PhpStorm.
 * User: Saleh
 * Date: 10/3/2018
 * Time: 10:55 AM
 */

namespace App\ActiveQuery;


use App\HomeworkAssign;
use App\QuizAssign;
use App\Users;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class UserActiveQuery extends  Users
{
    public function assignsForHomeworks(){
        return $this->hasMany(HomeworkAssign::class,'id_target','userid')
            ->where('assigntype','student');
    }

    public function assignsForQuiz(){
        return $this->hasMany(QuizAssign::class,'id_target','userid')
            ->where('assigntype','student');
    }

    public static  function events($userId){
        $events=array();
        $user=Users::find($userId);
        if(! $user['assignsForHomeworks'] ==null){
            foreach ($user['assignsForHomeworks'] as $homework){
                $events[]=[
                    'title'=>'Homework',
                    'url'=> '/'.App::getLocale().'/calender',
                    'start'=>$homework->startdate,
                    'end'=>$homework->enddate,
                ];
            }
        }

        if(! $user['assignsForQuiz'] ==null){
            foreach ($user['assignsForQuiz'] as $quiz){
                $events[]=[
                    'title'=>'Quiz',
                    'url'=> '/'.App::getLocale().'/calender',
                    'start'=>$quiz->startdate,
                    'end'=>$quiz->enddate,
                ];
            }
        }


          return $events;
    }
}