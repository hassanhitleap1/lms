<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Classes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Levels extends Model {

	protected $table = 'levels';

    protected $primaryKey='level_id';
    public $timestamps = true;

    public function classesInfo(){
        $query=$this->hasMany(Classes::class,'level','level_id');
        if(Users::isTeacher()){
            $arrLevels=DB::table('schedule')
                ->select('class')
                ->where('teacher',Auth::user()->userid)
                ->get()
                ->pluck('class')
                ->toArray();
            $query->whereIn('classes.class_id',$arrLevels);
        }
        return $query;

    }

    public function classesInfoForTeatcher(){
        $query=$this->hasMany(Classes::class,'level','level_id');
        if(Users::isTeacher()){
            $arrLevels=DB::table('schedule')
                ->select('class')
                ->where('teacher',Auth::user()->userid)
                ->get()
                ->pluck('class')
                ->toArray();
            $query->whereIn('classes.class_id',$arrLevels);
        }

        return $query;
    }
    public function lessons(){
        return $this->hasMany(Lessons::class,'level','level_id');
    }

    public function students(){
        return $this->hasMany(Users::class,'level','level_id')->where('permession',Users::USER_STUDENT);
    }


    public function assignsForHomeworks(){
        return $this->hasMany(HomeworkAssign::class,'id_target','level_id')
            ->where('assigntype','level');
    }

    public function assignsForQuiz(){
        return $this->hasMany(QuizAssign::class,'id_target','level_id')
            ->where('assigntype','level');
    }
}