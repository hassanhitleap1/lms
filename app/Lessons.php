<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Eloquent;
class Lessons extends Eloquent {

    const ProductName='lessons';
	protected $table = 'lessons';
	public $timestamps = true;
    protected $primaryKey = 'id';
	protected $dates = ['deleted_at'];
    public function level(){
        return $this->hasOne(Levels::class,'level','level_id');
    }
    public function media(){
        return $this->hasMany(LessonMidea::class,'id_lesson','id');
    }
    public function progres($userid){
        return $this->hasOne(Progress::class,'ref_id','id')
                    ->where('type',self::ProductName)
                    ->where('user_id',$userid)->first();
    }


    public function infoCategory(){
        return $this->hasOne(Categories::class,'category_id','category');
    }

    public function progressStudent($userid){
        return $this->hasMany(ResultMedia::class,'lesson_id','id')->where('user_id',$userid)->where('completed','completed');
    }
}