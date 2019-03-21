<?php

namespace App\Http\Controllers;

use App\Classes;
use App\Users;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Schedule;


class ScheduleController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function indexSchedule()
    {
        $class = 1;
        $section = 1;
        $steacher = 0;
        $Division = 0;
        $childs=null;
        $input = request()->all();
        if(Users::isStudent()){
                $class=Auth::user()->level;
                $Division =Auth::user()->class;
        }
        if (Users::isParent()){
            $class = (isset(Auth::user()->students->pluck('studentInfo.clsss')[0]))?Auth::user()->students->pluck('studentInfo.clsss')[0]:1;
            $Division = (isset(Auth::user()->students->pluck('studentInfo.level')[0]))?Auth::user()->students->pluck('studentInfo.level')[0]:1;
        }
        if (isset($input['class']) && $input['class'] != '' && $input['class'] != -1) {

            $class = $input['class'];
        }
        if (isset($input['section']) && $input['section'] != '' && $input['section'] != -1) {

            $section = $input['section'];
        }
        if (isset($input['steacher']) && $input['steacher'] != '' && $input['steacher'] !=0) {
            $steacher = $input['steacher'];
            $section=-1;//empty filters if teacher selected - by Hussam
            $class=-1;//empty filters if teacher selected - by Hussam
        }

        if(Users::isTeacher()){
            $steacher=Auth::user()->userid ;
        }
        if (Users::isParent()){
            $childs=Auth::user()->students->pluck('studentInfo');
        }


        if ($steacher == 0) {
            $allclasses = DB::table('levels')->select('levels.*');
            $section = DB::table('classes')->where('level', '=', $class);
            $teacher = DB::table('users')->where('permession', '=', 4);
            if(Users::isTeacher()){
                $teacher->where('userid',Auth::user()->userid);
            }
            $teacher=$teacher->get();
            $categories = DB::table('categories')->select('categories.*')->get();
            $Division = DB::table('classes')->where('level', '=', $class)->pluck('class_id')->first();
            if(Users::isStudent()){
                $allclasses->where('level_id',Auth::user()->level);
                $section->where('class_id',Auth::user()->class);
            }

            $section=  $section->get();
            $allclasses=  $allclasses->get();
            if (isset($input['Division']) && $input['Division'] != '' && $input['Division'] != -1) {
                $Division = $input['Division'];
            }

            $schedule = DB::table('schedule')
                ->leftJoin('categories', 'schedule.category', '=', 'categories.category_id')
                ->leftJoin('users', 'schedule.teacher', '=', 'users.userid')
                ->leftJoin('classes', 'schedule.class', '=', 'classes.class_id')
                ->where('permession', '=', 4)->where('schedule.class', '=', $Division)
                ->get();

            if(Users::isParent()){
                return view('schedule.indexparent', [
                    'steacher' => $steacher,
                    'Classroom' => $class,
                    'Division' => $Division,
                    'section' => $section,
                    'teacher' => $teacher,
                    'allclass' => $allclasses,
                    'section' => $section,
                    'categories' => $categories,
                    'schedule' => $schedule,
                    'childs'=>$childs]);
            }
            if(Users::isStudent()){
                return view('schedule.indexstudent', [
                    'steacher' => $steacher,
                    'Classroom' => $class,
                    'Division' => $Division,
                    'section' => $section,
                    'teacher' => $teacher,
                    'allclass' => $allclasses,
                    'section' => $section,
                    'categories' => $categories,
                    'schedule' => $schedule]);
            }
            return view('schedule.index', [
                'steacher' => $steacher,
                'Classroom' => $class,
                'Division' => $Division,
                'section' => $section,
                'teacher' => $teacher,
                'allclass' => $allclasses,
                'section' => $section,
                'categories' => $categories,
                'schedule' => $schedule,'childs'=>$childs]);

        } else {
            $allclasses = DB::table('levels')->select('levels.*')->get();
            $section2 = DB::table('classes')->select('classes.*')->get();
            $section = DB::table('classes')->where('level', '=', $class)->get();
            $teacher = DB::table('users')->where('permession', '=', 4);
            if(Users::isTeacher()){
                $teacher->where('userid',Auth::user()->userid);
            }
            $teacher=$teacher->get();
            $categories = DB::table('categories')->select('categories.*')->get();
            $Division = DB::table('classes')->where('level', '=', $class)->pluck('class_id')->first();
            if (isset($input['Division']) && $input['Division'] != '') {
                $Division = $input['Division'];
            }
            $schedule = DB::table('schedule')->select('levels.ltitle_ar AS title_ar','levels.level_id AS level_id', 'levels.ltitle_en AS title_en', 'schedule.dayofweek', 'classes.ctitle_en', 'classes.ctitle_ar','classes.class_id as class_id', 'schedule.period', 'schedule.category', 'schedule.teacher', 'schedule.teacher AS fullname','categories.title_en as cname_en','categories.title_ar as cname_ar')
                ->leftJoin('classes', 'schedule.class', '=', 'classes.class_id')
                ->leftJoin('categories', 'schedule.category', '=', 'categories.category_id')
                ->leftJoin('levels', 'classes.level', '=', 'levels.level_id')
                ->where('schedule.teacher', '=', $steacher)
                ->get();
            //return $categories;
            //dump($schedule[1]);
            if(Users::isTeacher()){
                return view('schedule.scheduleclass', ['steacher' => $steacher, 'Classroom' => $class, 'Division' => $Division, 'section' => $section,'section2' => $section2, 'teacher' => $teacher, 'allclass' => $allclasses, 'section' => $section, 'categories' => $categories, 'schedule' => $schedule]);
            }
            return view('schedule.indexteacher', ['steacher' => $steacher, 'Classroom' => $class, 'Division' => $Division, 'section' => $section,'section2' => $section2, 'teacher' => $teacher, 'allclass' => $allclasses, 'section' => $section, 'categories' => $categories, 'schedule' => $schedule]);

        }


    }

    public function ClassSchedule()
    {
        $input = request()->all();
        $class = 6;
        if (isset($input['class']) && $input['class'] != '') {
            $class = $input['class'];
        }
        $allclasses = DB::table('classes')->where('level', '=', $class)->get();
        return $allclasses;
    }
    public function SaveScheduleTeacher(){
        $input = request()->all();
        $steacher=-1;
        if (isset($input['steacher']) && $input['steacher'] != '') {
            $steacher = $input['steacher'];
        }

        foreach ($input['data'] as $key => $value) {
            $schedule = DB::table('schedule')
                        ->where('schedule.period', '=', $value['Period'])
                        ->where('schedule.dayofweek', '=', $value['Dayofweek'])
                        ->where(function ($q)use ($value){
                           $q->orwhere('schedule.class', '=', $value['Classroom']);
                           $q->orwhere('schedule.class', '=', -1);
                        })->get();
            if (!$schedule->isEmpty()) {
                $scheduleupdate = DB::table('schedule')
                    ->where('schedule_id', $schedule[0]->schedule_id)
                    ->update(array('teacher' => $steacher, 'Category' => $value['Category'],'class'=>$value['Division'], 'period' => $value['Period']));
            } else {
                $schedule = DB::table('schedule')
                    ->where('schedule.period', '=', $value['Period'])
                    ->where('schedule.dayofweek', '=', $value['Dayofweek'])
                    ->where('schedule.teacher', '=', $input['steacher'])
                    ->get();
                if (!$schedule->isEmpty()) {
                    DB::table('schedule')
                        ->where('schedule_id', $schedule[0]->schedule_id)
                        ->update(array('teacher' => $steacher, 'Category' => $value['Category'],'class'=>$value['Division'], 'period' => $value['Period']));
                }else{
                    DB::table('schedule')->insert(
                        array('school' => 1, 'class' => $value['Division'], 'period' => $value['Period'], 'dayofweek' => $value['Dayofweek'], 'teacher' => $steacher, 'Category' => $value['Category'])
                    );
                }

            }
        }

        $class = 1;

        $allclasses = DB::table('levels')->select('levels.*')->get();
        $section2 = DB::table('classes')->select('classes.*')->get();
        $section = DB::table('classes')->where('level', '=', $class)->get();
        $teacher = DB::table('users')->where('permession', '=', 4)->get();
        $categories = DB::table('categories')->select('categories.*')->get();
        $Division = DB::table('classes')->where('level', '=', $class)->pluck('class_id')->first();

        $schedule =  DB::table('schedule')->select('levels.ltitle_ar AS title_ar','levels.level_id AS level_id', 'levels.ltitle_en AS title_en', 'schedule.dayofweek', 'classes.ctitle_en', 'classes.ctitle_ar','classes.class_id as class_id', 'schedule.period', 'schedule.category', 'schedule.teacher', 'schedule.teacher AS fullname','categories.title_en as cname_en','categories.title_ar as cname_ar')
            ->leftJoin('classes', 'schedule.class', '=', 'classes.class_id')
            ->leftJoin('categories', 'schedule.category', '=', 'categories.category_id')
            ->leftJoin('levels', 'classes.level', '=', 'levels.level_id')
            ->where('schedule.teacher', '=', $steacher)
            ->get();

        return view('schedule.indexteacher', ['steacher' => $steacher, 'Classroom' => $class, 'Division' => $Division, 'section' => $section,'section2' => $section2, 'teacher' => $teacher, 'allclass' => $allclasses, 'section' => $section, 'categories' => $categories, 'schedule' => $schedule])->renderSections()['content'];


    }
    public function SaveSchedule($lang)
    {
        $section = 1;
        $steacher = 1;
        $input = request()->all();
        $class = 1;
        if (isset($input['class']) && $input['class'] != '') {
            $class = $input['class'];
        }

        foreach ($input['data'] as $key => $value) {
            $schedule = DB::table('schedule')->where('schedule.class', '=', $input['Division'])->where('schedule.period', '=', $value['Period'])->where('schedule.dayofweek', '=', $value['Dayofweek'])->get();
            if (!$schedule->isEmpty()) {
                $scheduleupdate = DB::table('schedule')
                    ->where('schedule_id', $schedule[0]->schedule_id)
                    ->update(array('teacher' => $value['Teacher'], 'Category' => $value['Category']));
            } else {
                $schedule = DB::table('schedule')
                    ->where('schedule.period', '=', $value['Period'])
                    ->where('schedule.dayofweek', '=', $value['Dayofweek'])
                    ->where('schedule.teacher', '=',  $value['Teacher'])
                    ->get();
                if (!$schedule->isEmpty()) {
                    $scheduleupdate = DB::table('schedule')
                        ->where('schedule_id', $schedule[0]->schedule_id)
                        ->update(array('teacher' => $value['Teacher'],
                            'Category' => $value['Category'],
                            'class'=>$input['Division']));
                }else{
                    DB::table('schedule')->insert(
                        array('school' => 1, 'class' => $input['Division'], 'period' => $value['Period'], 'dayofweek' => $value['Dayofweek'], 'teacher' => $value['Teacher'], 'Category' => $value['Category'])
                    );
                }

            }
        }
        $allclasses = DB::table('levels')->select('levels.*')->get();
        $section = DB::table('classes')->where('level', '=', $class)->get();
        $categories = DB::table('categories')->select('categories.*')->get();
        $teacher = DB::table('users')->where('permession', '=', 4)->get();
        $schedule = DB::table('schedule')
            ->leftJoin('categories', 'schedule.category', '=', 'categories.category_id')
            ->leftJoin('users', 'schedule.teacher', '=', 'users.userid')
            ->leftJoin('classes', 'schedule.class', '=', 'classes.class_id')
            ->where('permession', '=', 4)->where('schedule.class', '=', $input['Division'])
            ->get();

        return view('schedule.index', ['steacher' => $steacher, 'Classroom' => $class, 'Division' => $input['Division'], 'section' => $section, 'teacher' => $teacher, 'allclass' => $allclasses, 'section' => $section, 'categories' => $categories, 'schedule' => $schedule])->renderSections()['content'];
        // return view('schedule.index', ['Classroom' => $class,'Division' => $input['Division'], 'section' => $section, 'teacher' => $teacher, 'allclass' => $allclasses, 'section' => $section, 'categories' => $categories, 'schedule' => $schedule])->renderSections()['content'];
    }


}

?>