<?php
/**
 * Created by PhpStorm.
 * User: Saleh
 * Date: 11/15/2018
 * Time: 1:58 PM
 */

namespace App\Helper;


use App\Classes;
use App\Groups;
use App\Homeworks;
use App\Lessons;
use App\Levels;
use App\Quiz;
use App\Users;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class EventHelperCalender
{
    /**
     * get user event helper
     * @param integer $userId
     * @return Array of event
     */
    public static  function  getEventUser($userId){


        $events=array();
        $user=Users::find($userId);
        $homeworks=DB::table('homeworkassign')
            ->join('homeworks','homeworks.homework_id','=','homeworkassign.id_homework')
            ->where('homeworkassign.enddate','>=' ,date('Y-m-d'))
            ->where(function ($query)use ($userId,$user){
                $query->orWhere(function ($q) use ($userId){
                    $q->where('assigntype','student');
                    $q->where('id_target',$userId);
                }) ->orWhere(function ($q) use ($user){
                    $q->where('assigntype','classes');
                    $q->where('id_target',$user->class);
                })->orWhere(function ($q) use ($user){
                    $q->where('assigntype','group');
                    $q->whereIn('id_target',$user->assignsGroupsStudent->pluck('product_id')->toArray());
                });
            })->distinct()->get();

        foreach ($homeworks as $homework){
            $events[]=[
                'title'=>'Homework:- '.$homework->title,
                'url'=> '/'.App::getLocale().'/homework/'.$homework->id_homework,
                'start'=> $homework->startdate,
                'end'=>$homework->enddate,
            ];
        }

        $quizs=DB::table('quizassign')
            ->join('quiz','quiz.quiz_id','=','quizassign.id_quiz')
            ->orWhere(function ($q) use ($userId){
                $q->where('assigntype','student');
                $q->where('id_target',$userId);
            }) ->orWhere(function ($q) use ($user){
                $q->where('assigntype','class');
                $q->where('id_target',$user->class);
            })->orWhere(function ($q) use ($user){
                $q->where('assigntype','group');
                $q->whereIn('id_target',$user->assignsGroupsStudent->pluck('product_id')->toArray());
            })->distinct()->get();

        foreach ($quizs as $quiz){
            $events[]=[
                'title'=>'Quiz:- '.$quiz->title,
                'url'=> '/'.App::getLocale().'/quiz/'.$quiz->quiz_id,
                'start'=>$quiz->startdate,
                'end'=>$quiz->enddate,
            ];
        }

        $lessonassign=DB::table('lessonassigns')->select('lessonassigns.id_lesson')
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
            })
            ->get()->pluck('id_lesson')->toArray();

        $lessons = DB::table('users')
            ->select('users.*','categories.*','lessons.*')
            ->join('lessons', 'lessons.level', '=', 'users.level')
            ->join('categories', 'lessons.category', '=', 'categories.category_id')
            ->where('userid',$userId)
            ->orwhereIn('lessons.id',$lessonassign)
            ->distinct()
            ->get();


        foreach ($lessons as $lesson){
            $lesson=(array)$lesson;
            $events[]=[
                'title'=>'lesson:- '.$lesson['title_'.App::getLocale()].' '.$lesson['title'],
                'url'=> '/'.App::getLocale().'/lessonsviewer/'.$lesson['id'],
                'start'=>$lesson['start_date'],
                'end'=>$lesson['close_date'],
            ];
        }


        return $events;
    }

    /**
     * get event for groups
     * @param integer $groupId
     * @return Array of events
     */
    public static function  getEventGroup($groupId){
        $events=array();
        $group=Groups::find($groupId);
        if(! $group['assignsForHomeworks'] ==null){
            foreach ($group['assignsForHomeworks'] as $homework){

                $events[]=[
                    'title'=>'Homework:- '.$homework->homework['title'],
                    'url'=> '/'.App::getLocale().'/homework/'.$homework->id_homework,
                    'start'=>$homework->startdate,
                    'end'=>$homework->enddate,
                ];
            }
        }

        if(! $group['assignsForQuiz'] ==null){
            foreach ($group['assignsForQuiz'] as $quiz){
                $events[]=[
                    'title'=>'Quiz:- ' .$quiz->quizInfo['title'],
                    'url'=> '/'.App::getLocale().'/quiz/'.$quiz->id_quiz,
                    'start'=>$quiz->startdate,
                    'end'=>$quiz->enddate,
                ];
            }
        }
        $lessons=DB::table('lessonassigns')
                ->select('lessonassigns.*','lessons.*')
                ->join('lessons','lessons.id','=','lessonassigns.id_lesson')
                ->where('lessonassigns.id_target','=',$groupId)
                ->where('lessonassigns.assigntype','=','group')
                ->get();


        foreach ($lessons as $lesson){
            $events[]=[
                'title'=>'lesson:- '.$lesson->title,
                'url'=> '/'.App::getLocale().'/lessonsviewer/'.$lesson->id_lesson,
                'start'=>$lesson->startdate,
                'end'=>$lesson->enddate,
            ];
        }

        return $events;
    }

    /**
     * get event for clasesses
     * @param integer $classId
     * @return Array of events
     */
    public static function  getEventClass($classId){
        $events=array();
        $classModel=Classes::find($classId);
        if(! $classModel['assignsForHomeworks'] ==null){
            foreach ($classModel['assignsForHomeworks'] as $homework){
                $events[]=[
                    'title'=>'Homework:- '.$homework->homework['title'],
                    'url'=> '/'.App::getLocale().'/homework/'.$homework->id_homework,
                    'start'=>$homework->startdate,
                    'end'=>$homework->enddate,
                ];
            }
        }

        if(! $classModel['assignsForQuiz'] ==null){
            foreach ($classModel['assignsForQuiz'] as $quiz){
                $events[]=[
                    'title'=>'Quiz:- ' .$quiz->quizInfo['title'],
                    'url'=> '/'.App::getLocale().'/quiz/'.$quiz->id_quiz,
                    'start'=>$quiz->startdate,
                    'end'=>$quiz->enddate,
                ];
            }
        }


        return $events;
    }
    /**
     * get event for levels
     * @param integer $levelId
     * @return Array of events
     */
    public static function  getEventLevel($levelId){

        $events=array();
        $level=Levels::find($levelId);
        foreach ($level->classesInfo as $classModel){
            if(! $classModel['assignsForHomeworks'] ==null){
                foreach ($classModel['assignsForHomeworks'] as $homework){
                    $events[]=[
                        'title'=>'Homework:- '.$homework->homework['title'],
                        'url'=> '/'.App::getLocale().'/homework/'.$homework->id_homework,
                        'start'=>$homework->startdate,
                        'end'=>$homework->enddate,
                    ];
                }
            }

            if(! $classModel['assignsForQuiz'] ==null){
                foreach ($classModel['assignsForQuiz'] as $quiz){
                    $events[]=[
                        'title'=>'Quiz:- ' .$quiz->quizInfo['title'],
                        'url'=> '/'.App::getLocale().'/quiz/'.$quiz->id_quiz,
                        'start'=>$quiz->startdate,
                        'end'=>$quiz->enddate,
                    ];
                }
            }
        }


        $lessons = DB::table('lessons')
            ->select('categories.*','lessons.*')
            ->join('categories', 'lessons.category', '=', 'categories.category_id')
            ->where('lessons.level',$levelId)
            ->get();
        foreach ($lessons as $lesson){
            $events[]=[
                'title'=>'lesson:- '.$lesson->{'title_'.App::getLocale()}.' '.$lesson->title,
                'url'=> '/'.App::getLocale().'/lessonsviewer/'.$lesson->id,
                'start'=>$lesson->start_date,
                'end'=>$lesson->close_date,
            ];
        }

        return $events;
    }

    /**
     * et event for parent
     * @param $parentId
     *  @return Array of events
     */
    public static function  getEventParent($parentId){

        $events=array();
        $childsId=DB::table('parents')
            ->join('users','users.userid','=','parents.student_id')
            ->where('parent_id',$parentId)->get()->pluck('userid')->toArray();
        foreach ($childsId as $userId){
            $user=Users::find($userId);
            $homeworks=DB::table('homeworkassign')
                ->join('homeworks','homeworks.homework_id','=','homeworkassign.id_homework')
                ->where('homeworkassign.enddate','>=' ,date('Y-m-d'))

                ->where(function ($query)use ($userId,$user){
                    $query->orWhere(function ($q) use ($userId){
                        $q->where('assigntype','student');
                        $q->where('id_target',$userId);
                    }) ->orWhere(function ($q) use ($user){
                        $q->where('assigntype','classes');
                        $q->where('id_target',$user->class);
                    })->orWhere(function ($q) use ($user){
                        $q->where('assigntype','group');
                        $q->whereIn('id_target',$user->assignsGroupsStudent->pluck('product_id')->toArray());
                    });
                })->distinct()->get();

            foreach ($homeworks as $homework){
                $events[]=[
                    'title'=>'Homework:- '.$homework->title,
                    'url'=> '/'.App::getLocale().'/homework/'.$homework->id_homework,
                    'start'=> $homework->startdate,
                    'end'=>$homework->enddate,
                ];
            }

            $quizs=DB::table('quizassign')
                ->join('quiz','quiz.quiz_id','=','quizassign.id_quiz')
                ->orWhere(function ($q) use ($userId){
                    $q->where('assigntype','student');
                    $q->where('id_target',$userId);
                }) ->orWhere(function ($q) use ($user){
                    $q->where('assigntype','class');
                    $q->where('id_target',$user->class);
                })->orWhere(function ($q) use ($user){
                    $q->where('assigntype','group');
                    $q->whereIn('id_target',$user->assignsGroupsStudent->pluck('product_id')->toArray());
                })->distinct()->get();

            foreach ($quizs as $quiz){
                $events[]=[
                    'title'=>'Quiz:- '.$quiz->title,
                    'url'=> '/'.App::getLocale().'/quiz/'.$quiz->quiz_id,
                    'start'=>$quiz->startdate,
                    'end'=>$quiz->enddate,
                ];
            }


            $lessons = DB::table('users')
                ->select('users.*','categories.*','lessons.*')
                ->join('lessons', 'lessons.level', '=', 'users.level')
                ->join('categories', 'lessons.category', '=', 'categories.category_id')
                ->where('userid',$userId)
                ->get();


            foreach ($lessons as $lesson){
                $lesson=(array)$lesson;
                $events[]=[
                    'title'=>'lesson:- '.$lesson['title_'.App::getLocale()].' '.$lesson['title'],
                    'url'=> '/'.App::getLocale().'/lessonsviewer/'.$lesson['id'],
                    'start'=>$lesson['start_date'],
                    'end'=>$lesson['close_date'],
                ];
            }
        }


        return $events;

    }

    /**
     * @param $teacherid
     * @return array
     */
    public static function  getEventTeacher($teacherid){

        $events=array();
        $idlevels=DB::table('schedule')
            ->join('classes','classes.class_id','=','schedule.class')
            ->where('teacher',$teacherid)->distinct()->get()->pluck('level')->toArray();
        $lessons=Lessons::where(function ($q) use ($teacherid,$idlevels){
                    $q->orWhere('teacher',$teacherid);
                    $q->orWhereIn('level',$idlevels);
                 })->get();


        foreach ($lessons as $lesson){
            $events[]=[
                'title'=>'lesson:- '.$lesson->title,
                'url'=> '/'.App::getLocale().'/lessonsviewer/'.$lesson->id,
                'start'=>$lesson->start_date,
                'end'=>$lesson->close_date,
            ];
        }

        $homeworks= Homeworks::where('teacher',$teacherid)->get();


        foreach ($homeworks as $homework){
            $date=DB::table('homeworkassign')->select('startdate','enddate')->where('id_homework',$homework->homework_id)->first();
            $events[]=[
                'title'=>'Homework:- '.$homework->title,
                'url'=> '/'.App::getLocale().'/homework/'.$homework->homework_id,
                'start'=>$date->startdate,
                'end'=>$date->enddate,
            ];
        }

        $quizs= Quiz::where('teacher',$teacherid)->get();

        foreach ($quizs as $quiz){
            $date=DB::table('quizassign')->select('startdate','enddate')->where('id_quiz',$quiz->quiz_id)->first();
            $events[]=[
                'title'=>'Quiz:- '.$quiz->title,
                'url'=> '/'.App::getLocale().'/quiz/'.$quiz->quiz_id,
                'start'=>$date->startdate,
                'end'=>$date->enddate,
            ];
        }
        return $events;

    }


}